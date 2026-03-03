<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /* ═══════════════════════════════════════════════
     | START / RESUME session
    ═══════════════════════════════════════════════ */
    public function startSession(Request $request): JsonResponse
    {
        $sessionId = $request->input('session_id') ?? Str::uuid()->toString();

        // Check existing open conversation
        $conv = DB::table('chatbot_conversations')
            ->where('session_id', $sessionId)
            ->whereNull('ended_at')
            ->first();

        if (!$conv) {
            $userId    = Auth::check() ? Auth::id() : null;
            $guestName = $request->input('guest_name');

            // Auto-fill name from patient profile if logged in
            if ($userId && !$guestName) {
                $patient = DB::table('patients')->where('user_id', $userId)->first();
                if ($patient) {
                    $guestName = trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''));
                }
            }

            $convId = DB::table('chatbot_conversations')->insertGetId([
                'session_id'  => $sessionId,
                'user_id'     => $userId,
                'guest_name'  => $guestName,
                'mode'        => 'bot',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // Welcome message
            $name    = $guestName ? ", {$guestName}" : '';
            $welcome = "👋 Hello{$name}! I'm **HEALTHNET Assistant**.\n\nI can help you with:\n• 📅 Booking appointments\n• 🔬 Lab tests\n• 💊 Pharmacy orders\n• ❤️ Health tips\n• 📞 Contacting our support team\n\nHow can I help you today?";

            DB::table('chatbot_messages')->insert([
                'conversation_id' => $convId,
                'sender'          => 'bot',
                'message'         => $welcome,
                'intent'          => 'welcome',
                'created_at'      => now(),
            ]);

            return response()->json([
                'success'     => true,
                'session_id'  => $sessionId,
                'conv_id'     => $convId,
                'mode'        => 'bot',
                'guest_name'  => $guestName,
                'messages'    => [['sender' => 'bot', 'message' => $welcome, 'time' => now()->format('h:i A')]],
            ]);
        }

        // Resume existing
        $messages = DB::table('chatbot_messages')
            ->where('conversation_id', $conv->id)
            ->orderBy('created_at')
            ->get()
            ->map(fn($m) => [
                'sender'  => $m->sender,
                'message' => $m->message,
                'time'    => \Carbon\Carbon::parse($m->created_at)->format('h:i A'),
            ]);

        return response()->json([
            'success'    => true,
            'session_id' => $sessionId,
            'conv_id'    => $conv->id,
            'mode'       => $conv->mode,
            'guest_name' => $conv->guest_name,
            'messages'   => $messages,
        ]);
    }

    /* ═══════════════════════════════════════════════
     | SEND MESSAGE (user → bot or admin)
    ═══════════════════════════════════════════════ */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'conv_id' => 'required|integer',
            'message' => 'required|string|max:1000',
        ]);

        $conv = DB::table('chatbot_conversations')->find($request->conv_id);
        if (!$conv) {
            return response()->json(['success' => false, 'message' => 'Session not found.'], 404);
        }

        // Save user message
        DB::table('chatbot_messages')->insert([
            'conversation_id' => $conv->id,
            'sender'          => 'user',
            'message'         => $request->message,
            'created_at'      => now(),
        ]);

        // Admin mode — just save, admin will reply
        if ($conv->mode === 'admin') {
            // Notify admin via notification
            $this->notifyAdmins(
                "💬 New patient message",
                "Patient says: " . Str::limit($request->message, 80),
                $conv->id
            );

            return response()->json(['success' => true, 'mode' => 'admin']);
        }

        // BOT mode — generate response
        $reply = $this->generateBotReply($request->message, $conv->id);

        DB::table('chatbot_messages')->insert([
            'conversation_id' => $conv->id,
            'sender'          => 'bot',
            'message'         => $reply['text'],
            'intent'          => $reply['intent'] ?? null,
            'created_at'      => now(),
        ]);

        return response()->json([
            'success'    => true,
            'mode'       => 'bot',
            'reply'      => $reply['text'],
            'route_name' => $reply['route_name'] ?? null,
            'route_label'=> $reply['route_label'] ?? null,
            'time'       => now()->format('h:i A'),
        ]);
    }

    /* ═══════════════════════════════════════════════
     | SWITCH TO ADMIN LIVE CHAT
    ═══════════════════════════════════════════════ */
    public function switchToAdmin(Request $request): JsonResponse
    {
        $conv = DB::table('chatbot_conversations')->find($request->conv_id);
        if (!$conv) return response()->json(['success' => false], 404);

        DB::table('chatbot_conversations')
            ->where('id', $conv->id)
            ->update(['mode' => 'admin', 'updated_at' => now()]);

        $msg = "🔔 You are now connected to our **Support Team**.\nAn agent will respond shortly. Average response time: 5–10 minutes.\n\nType your message below and we'll get back to you! 😊";

        DB::table('chatbot_messages')->insert([
            'conversation_id' => $conv->id,
            'sender'          => 'bot',
            'message'         => $msg,
            'intent'          => 'switch_admin',
            'created_at'      => now(),
        ]);

        $this->notifyAdmins(
            "🆕 Patient wants to chat",
            ($conv->guest_name ?? 'A patient') . " has requested live support.",
            $conv->id
        );

        return response()->json([
            'success' => true,
            'message' => $msg,
            'time'    => now()->format('h:i A'),
        ]);
    }

    /* ═══════════════════════════════════════════════
     | SWITCH BACK TO BOT
    ═══════════════════════════════════════════════ */
    public function switchToBot(Request $request): JsonResponse
    {
        DB::table('chatbot_conversations')
            ->where('id', $request->conv_id)
            ->update(['mode' => 'bot', 'updated_at' => now()]);

        $msg = "🤖 Switched back to **AI Assistant** mode. How can I help you?";

        DB::table('chatbot_messages')->insert([
            'conversation_id' => $request->conv_id,
            'sender'          => 'bot',
            'message'         => $msg,
            'intent'          => 'switch_bot',
            'created_at'      => now(),
        ]);

        return response()->json(['success' => true, 'message' => $msg]);
    }

    /* ═══════════════════════════════════════════════
     | POLL NEW MESSAGES (long-poll)
    ═══════════════════════════════════════════════ */
    public function pollMessages(int $convId, Request $request): JsonResponse
    {
        $lastId = $request->input('last_id', 0);

        $messages = DB::table('chatbot_messages')
            ->where('conversation_id', $convId)
            ->where('id', '>', $lastId)
            ->orderBy('created_at')
            ->get()
            ->map(fn($m) => [
                'id'      => $m->id,
                'sender'  => $m->sender,
                'message' => $m->message,
                'time'    => \Carbon\Carbon::parse($m->created_at)->format('h:i A'),
            ]);

        $conv = DB::table('chatbot_conversations')->find($convId);

        return response()->json([
            'messages' => $messages,
            'mode'     => $conv->mode ?? 'bot',
        ]);
    }

    /* ═══════════════════════════════════════════════
     | SUBMIT CONTACT ADMIN FORM
    ═══════════════════════════════════════════════ */
    public function submitContact(Request $request): JsonResponse
    {
        $request->validate([
            'conv_id' => 'nullable|integer',
            'name'    => 'required|string|max:100',
            'email'   => 'nullable|email',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $contactId = DB::table('chatbot_admin_contacts')->insertGetId([
            'conversation_id' => $request->conv_id,
            'user_id'         => Auth::check() ? Auth::id() : null,
            'name'            => $request->name,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'subject'         => $request->subject ?? 'Chat Support Request',
            'message'         => $request->message,
            'status'          => 'pending',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Notify all admins
        $admins = DB::table('users')
            ->where('user_type', 'admin')
            ->where('status', 'active')
            ->get();

        foreach ($admins as $admin) {
            DB::table('notifications')->insert([
                'notifiable_type' => 'App\Models\User',
                'notifiable_id'   => $admin->id,
                'type'            => 'chatbot_contact',
                'title'           => '📨 New Support Request',
                'message'         => $request->name . ' sent a support request: ' . Str::limit($request->message, 80),
                'related_type'    => 'chatbot_contact',
                'related_id'      => $contactId,
                'is_read'         => 0,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => '✅ Message sent! Our team will respond within 24 hours.',
        ]);
    }

    /* ═══════════════════════════════════════════════
     | GET FAQs (for quick reply buttons)
    ═══════════════════════════════════════════════ */
    public function getFaqs(): JsonResponse
    {
        $faqs = DB::table('chatbot_faqs')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'question', 'intent_key', 'route_name', 'route_label']);

        return response()->json(['faqs' => $faqs]);
    }

    /* ═══════════════════════════════════════════════
     | PRIVATE: Generate Bot Reply
    ═══════════════════════════════════════════════ */
    private function generateBotReply(string $userMessage, int $convId): array
    {
        $lower = strtolower($userMessage);

        // 1. Check FAQ keyword match first (fastest)
        $faqs = DB::table('chatbot_faqs')->where('is_active', 1)->get();
        foreach ($faqs as $faq) {
            if (!$faq->intent_key) continue;
            $keywords = array_map('trim', explode(',', $faq->intent_key));
            foreach ($keywords as $kw) {
                if ($kw && str_contains($lower, $kw)) {
                    return [
                        'text'        => $faq->answer,
                        'intent'      => 'faq',
                        'route_name'  => $faq->route_name,
                        'route_label' => $faq->route_label,
                    ];
                }
            }
        }

        // 2. Contact admin intent
        if (str_contains($lower, 'contact admin') ||
            str_contains($lower, 'talk to human') ||
            str_contains($lower, 'speak to agent') ||
            str_contains($lower, 'live chat')) {
            return [
                'text'   => "I'll connect you with our support team right away! Click the **\"💬 Contact Admin\"** button below.",
                'intent' => 'contact_admin',
            ];
        }

        // 3. Health tip keywords — use Gemini
        $healthTopics = ['diet','exercise','water','sleep','stress','diabetes','blood pressure',
                         'weight','nutrition','vitamin','fever','cough','headache','pregnancy',
                         'heart','kidney','sugar','cholesterol','mental health','anxiety'];

        $isHealthTopic = false;
        foreach ($healthTopics as $topic) {
            if (str_contains($lower, $topic)) { $isHealthTopic = true; break; }
        }

        if ($isHealthTopic) {
            return $this->callGemini($userMessage, 'health_tip');
        }

        // 4. General platform help — Gemini with system context
        $platformWords = ['how','what','where','can i','appointment','doctor','pharmacy','lab',
                          'payment','login','register','account','password','profile'];
        $isPlatformQ   = false;
        foreach ($platformWords as $w) {
            if (str_contains($lower, $w)) { $isPlatformQ = true; break; }
        }

        if ($isPlatformQ) {
            return $this->callGemini($userMessage, 'platform');
        }

        // 5. Fallback
        return [
            'text'   => "I'm not sure I understand that. Here are some things I can help you with:\n\n• 📅 Book appointments\n• 🔬 Lab tests\n• 💊 Pharmacy orders\n• ❤️ Health tips\n\nOr would you like to **contact our support team** directly?",
            'intent' => 'fallback',
        ];
    }

    /* ═══════════════════════════════════════════════
     | PRIVATE: Call Google Gemini API (FREE)
    ═══════════════════════════════════════════════ */
    private function callGemini(string $userMessage, string $context): array
    {
        $apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        $model  = config('services.gemini.model', 'gemini-1.5-flash');

        if (!$apiKey) {
            return ['text' => "I'm having trouble connecting to AI services right now. Please try again later or contact our support team.", 'intent' => 'ai_error'];
        }

        $systemPrompt = $context === 'health_tip'
            ? "You are HEALTHNET Assistant, a professional health advisor for a Sri Lankan healthcare platform. ONLY answer health-related questions: nutrition, exercise, wellness, disease prevention, symptoms, and healthy lifestyle. Keep responses friendly, clear, and under 150 words. Do NOT discuss unrelated topics. End with a tip or encouragement."
            : "You are HEALTHNET Assistant for a Sri Lankan healthcare management platform called HEALTHNET. The platform allows patients to: book doctor appointments, order lab tests, order medicines from pharmacies, track health metrics, and manage medical records. ONLY answer questions related to the HEALTHNET platform features. Keep answers under 120 words, friendly and helpful. If unsure, recommend contacting support.";

        try {
            $response = Http::timeout(10)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $systemPrompt . "\n\nUser question: " . $userMessage]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 200,
                        'temperature'     => 0.7,
                    ]
                ]
            );

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text') ?? '';
                if ($text) {
                    return ['text' => trim($text), 'intent' => 'ai'];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Gemini API error: ' . $e->getMessage());
        }

        return [
            'text'   => "I'm unable to process that right now. Please try rephrasing, or **contact our support team** for immediate assistance.",
            'intent' => 'ai_error',
        ];
    }

    /* ═══════════════════════════════════════════════
     | PRIVATE: Notify Admins
    ═══════════════════════════════════════════════ */
    private function notifyAdmins(string $title, string $message, int $convId): void
    {
        try {
            $admins = DB::table('users')
                ->where('user_type', 'admin')
                ->where('status', 'active')
                ->pluck('id');

            foreach ($admins as $adminId) {
                DB::table('notifications')->insert([
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id'   => $adminId,
                    'type'            => 'live_chat',
                    'title'           => $title,
                    'message'         => $message,
                    'related_type'    => 'chatbot_conversation',
                    'related_id'      => $convId,
                    'is_read'         => 0,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Admin chat notify error: ' . $e->getMessage());
        }
    }
}
