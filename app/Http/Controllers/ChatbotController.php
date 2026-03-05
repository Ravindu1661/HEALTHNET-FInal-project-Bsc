<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    private string $hfToken;
    private string $hfModel = 'moonshotai/Kimi-K2-Instruct-0905';
    private string $hfUrl   = 'https://router.huggingface.co/v1/chat/completions';

    public function __construct()
    {
        $this->hfToken = env('HF_TOKEN', '');
    }

    /**
     * Start or resume a chatbot session
     */
    public function startSession(Request $request)
    {
        $sessionId = $request->input('session_id') ?? Str::uuid()->toString();
        $user      = Auth::user();

        // Check if conversation already exists
        $conversation = DB::table('chatbot_conversations')
            ->where('session_id', $sessionId)
            ->where('status', 'active')
            ->first();

        if (!$conversation) {
            $data = [
                'session_id' => $sessionId,
                'mode'       => 'bot',
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($user) {
                $data['user_id'] = $user->id;
            } else {
                $data['guest_name']  = $request->input('guest_name');
                $data['guest_email'] = $request->input('guest_email');
            }

            $convId = DB::table('chatbot_conversations')->insertGetId($data);
        } else {
            $convId = $conversation->id;
        }

        // Build user info
        $userInfo = $this->getUserInfo($user);

        // Get recent messages
        $messages = DB::table('chatbot_messages')
            ->where('conversation_id', $convId)
            ->orderBy('created_at', 'asc')
            ->get(['sender_type', 'message', 'created_at']);

        // Quick links for this user's role
        $quickLinks = $this->getQuickLinks($user);

        return response()->json([
            'ok'          => true,
            'session_id'  => $sessionId,
            'conv_id'     => $convId,
            'user'        => $userInfo,
            'messages'    => $messages,
            'quick_links' => $quickLinks,
            'mode'        => $conversation->mode ?? 'bot',
        ]);
    }

    /**
     * Send a message (to AI bot or admin)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conv_id'    => 'required|integer',
            'session_id' => 'required|string',
            'message'    => 'required|string|max:2000',
        ]);

        $convId    = $request->input('conv_id');
        $sessionId = $request->input('session_id');
        $message   = trim($request->input('message'));
        $user      = Auth::user();

        // Verify conversation
        $conversation = DB::table('chatbot_conversations')
            ->where('id', $convId)
            ->where('session_id', $sessionId)
            ->where('status', 'active')
            ->first();

        if (!$conversation) {
            return response()->json(['ok' => false, 'error' => 'Conversation not found'], 404);
        }

        // Store user message
        DB::table('chatbot_messages')->insert([
            'conversation_id' => $convId,
            'sender_type'     => 'user',
            'sender_id'       => $user?->id,
            'message'         => $message,
            'created_at'      => now(),
        ]);

        DB::table('chatbot_conversations')->where('id', $convId)->update(['updated_at' => now()]);

        // If mode is 'admin' - just store, admin will reply
        if ($conversation->mode === 'admin') {
            // Notify admin via DB notification
            $adminUser = DB::table('users')->where('user_type', 'admin')->first();
            if ($adminUser) {
                DB::table('notifications')->insert([
                    'notifiable_type' => 'App\\Models\\User',
                    'notifiable_id'   => $adminUser->id,
                    'type'            => 'chatbot_message',
                    'title'           => 'New Live Chat Message',
                    'message'         => ($conversation->guest_name ?? ($user?->name ?? 'User')) . ': ' . Str::limit($message, 80),
                    'related_type'    => 'chatbot_conversation',
                    'related_id'      => $convId,
                    'is_read'         => 0,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            return response()->json([
                'ok'   => true,
                'mode' => 'admin',
                'reply'=> null,
                'note' => 'Message sent to admin. Waiting for reply.',
            ]);
        }

        // BOT mode - call HF AI
        $reply = $this->callHfAi($message, $user, $convId);

        if ($reply === false) {
            return response()->json(['ok' => false, 'error' => 'AI service unavailable'], 500);
        }

        // Store bot reply
        DB::table('chatbot_messages')->insert([
            'conversation_id' => $convId,
            'sender_type'     => 'bot',
            'sender_id'       => null,
            'message'         => $reply,
            'created_at'      => now(),
        ]);

        // If guest - send email with reply
        if (!$user && $conversation->guest_email) {
            $this->sendGuestEmail($conversation, $message, $reply);
        }

        return response()->json([
            'ok'    => true,
            'mode'  => 'bot',
            'reply' => $reply,
        ]);
    }

    /**
     * Switch conversation to admin live chat
     */
    public function switchToAdmin(Request $request)
    {
        $convId    = $request->input('conv_id');
        $sessionId = $request->input('session_id');

        DB::table('chatbot_conversations')
            ->where('id', $convId)
            ->where('session_id', $sessionId)
            ->update(['mode' => 'admin', 'updated_at' => now()]);

        // System message
        DB::table('chatbot_messages')->insert([
            'conversation_id' => $convId,
            'sender_type'     => 'bot',
            'message'         => 'You have been connected to an admin. Please wait for a response. Our team will reply shortly.',
            'created_at'      => now(),
        ]);

        // Notify admin
        $adminUser = DB::table('users')->where('user_type', 'admin')->first();
        if ($adminUser) {
            DB::table('notifications')->insert([
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => $adminUser->id,
                'type'            => 'live_chat_request',
                'title'           => 'New Live Chat Request',
                'message'         => 'A user has requested live admin support.',
                'related_type'    => 'chatbot_conversation',
                'related_id'      => $convId,
                'is_read'         => 0,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        return response()->json(['ok' => true, 'mode' => 'admin']);
    }

    /**
     * Switch back to bot mode
     */
    public function switchToBot(Request $request)
    {
        $convId    = $request->input('conv_id');
        $sessionId = $request->input('session_id');

        DB::table('chatbot_conversations')
            ->where('id', $convId)
            ->where('session_id', $sessionId)
            ->update(['mode' => 'bot', 'updated_at' => now()]);

        return response()->json(['ok' => true, 'mode' => 'bot']);
    }

    /**
     * Contact admin form (guest)
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'message' => 'required|string|max:2000',
        ]);

        // Save to DB
        DB::table('chatbot_conversations')->insertGetId([
            'session_id'  => Str::uuid()->toString(),
            'guest_name'  => $request->input('name'),
            'guest_email' => $request->input('email'),
            'mode'        => 'admin',
            'status'      => 'active',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return response()->json(['ok' => true, 'message' => 'Your message has been sent to admin.']);
    }

    /**
     * Poll new messages (for live admin chat)
     */
    public function pollMessages(Request $request, $convId)
    {
        $after = $request->query('after', 0); // last message id
        $msgs  = DB::table('chatbot_messages')
            ->where('conversation_id', $convId)
            ->where('id', '>', $after)
            ->orderBy('created_at', 'asc')
            ->get(['id', 'sender_type', 'message', 'created_at']);

        return response()->json(['ok' => true, 'messages' => $msgs]);
    }

    /**
     * Get FAQs
     */
    public function getFaqs()
    {
        $faqs = DB::table('chatbot_faqs')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'question', 'answer', 'category']);

        return response()->json(['ok' => true, 'faqs' => $faqs]);
    }

    // ==================== PRIVATE HELPERS ====================

    private function callHfAi(string $userMessage, $user, int $convId): string|false
    {
        if (!$this->hfToken) return false;

        // Build system prompt with health context
        $systemPrompt = $this->buildSystemPrompt($user, $convId);

        $payload = [
            'model'       => $this->hfModel,
            'messages'    => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user',   'content' => $userMessage],
            ],
            'temperature' => 0.6,
            'max_tokens'  => 350,
        ];

        $ch = curl_init($this->hfUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->hfToken,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_USERAGENT      => 'HealthNet-Bot/1.0',
        ]);

        $response = curl_exec($ch);
        $errno    = curl_errno($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($errno || $httpCode >= 400) return false;

        $data  = json_decode($response, true);
        $reply = trim($data['choices'][0]['message']['content'] ?? '');

        return $reply ?: false;
    }

    private function buildSystemPrompt($user, int $convId): string
    {
        $context = "You are HealthNet AI Assistant, a helpful medical information chatbot for HealthNet healthcare platform in Sri Lanka.\n\n";
        $context .= "IMPORTANT RULES:\n";
        $context .= "- Only provide health-related information, medical advice guidance, and platform navigation help.\n";
        $context .= "- Always recommend consulting a real doctor for diagnosis or treatment.\n";
        $context .= "- Do NOT discuss non-health topics.\n";
        $context .= "- Be empathetic, clear, and concise.\n";
        $context .= "- Respond in the same language the user writes (Sinhala or English).\n\n";

        if ($user) {
            $context .= "CURRENT USER CONTEXT:\n";
            $context .= "- Name: " . ($user->name ?? 'User') . "\n";
            $context .= "- Role: " . $user->user_type . "\n";

            // Load patient health data if available
            if ($user->user_type === 'patient') {
                $patient = DB::table('patients')->where('user_id', $user->id)->first();
                if ($patient) {
                    $context .= "- Blood Group: " . ($patient->blood_group ?? 'Unknown') . "\n";
                    $context .= "- City: " . ($patient->city ?? 'Unknown') . "\n";
                }

                $healthData = DB::table('patient_health_data')
                    ->where('patient_id', $patient->id ?? 0)
                    ->orderBy('recorded_date', 'desc')
                    ->first();

                if ($healthData) {
                    $context .= "- Has Diabetes: " . ($healthData->has_diabetes ? 'Yes' : 'No') . "\n";
                    $context .= "- Has Hypertension: " . ($healthData->has_hypertension ? 'Yes' : 'No') . "\n";
                    $context .= "- Has Heart Disease: " . ($healthData->has_heart_disease ? 'Yes' : 'No') . "\n";
                    if ($healthData->allergies) {
                        $context .= "- Allergies: " . $healthData->allergies . "\n";
                    }
                    if ($healthData->current_medications) {
                        $context .= "- Current Medications: " . $healthData->current_medications . "\n";
                    }
                }
            }
        }

        // Recent conversation context (last 5 messages)
        $recentMsgs = DB::table('chatbot_messages')
            ->where('conversation_id', $convId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->reverse();

        if ($recentMsgs->count() > 0) {
            $context .= "\nRECENT CONVERSATION:\n";
            foreach ($recentMsgs as $msg) {
                $role = $msg->sender_type === 'user' ? 'User' : 'Assistant';
                $context .= $role . ': ' . Str::limit($msg->message, 150) . "\n";
            }
        }

        return $context;
    }

    private function getUserInfo($user): array
    {
        if (!$user) return ['logged_in' => false];

        $info = [
            'logged_in' => true,
            'name'      => $user->name,
            'email'     => $user->email,
            'role'      => $user->user_type,
        ];

        if ($user->user_type === 'patient') {
            $patient = DB::table('patients')->where('user_id', $user->id)->first();
            if ($patient) {
                $info['first_name']    = $patient->first_name;
                $info['last_name']     = $patient->last_name;
                $info['profile_image'] = $patient->profile_image;
            }
        }

        return $info;
    }

   private function getQuickLinks($user): array
{
    $role    = $user?->user_type ?? 'guest';
    $appUrl  = rtrim(env('APP_URL', 'http://localhost:8000'), '/');

    $links = DB::table('chatbot_quick_links')
        ->where('is_active', 1)
        ->orderBy('sort_order')
        ->get();

    $filtered = [];
    foreach ($links as $link) {
        $roles = json_decode($link->roles ?? '[]', true);
        if (in_array($role, $roles) || empty($roles)) {
            $path = ltrim($link->url_path ?? '', '/');
            $filtered[] = [
                'label' => $link->label,
                'url'   => $appUrl . '/' . $path,
                'icon'  => $link->icon,
            ];
        }
    }

    return $filtered;
}


    private function sendGuestEmail($conversation, string $userMessage, string $botReply): void
    {
        try {
            $to    = $conversation->guest_email;
            $name  = $conversation->guest_name ?? 'User';

            Mail::send('emails.chatbot-reply', [
                'name'       => $name,
                'userMessage'=> $userMessage,
                'botReply'   => $botReply,
            ], function ($m) use ($to, $name) {
                $m->to($to, $name)
                  ->from(config('mail.from.address'), config('mail.from.name'))
                  ->subject('HealthNet Assistant - Your Health Query Response');
            });
        } catch (\Exception $e) {
            \Log::error('Chatbot email failed: ' . $e->getMessage());
        }
    }
}
