<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminChatbotController extends Controller
{
    /* ═══════════════════════════════════════════════════════
     | MIDDLEWARE — admin only
    ═══════════════════════════════════════════════════════ */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->user_type !== 'admin') {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    /* ═══════════════════════════════════════════════════════
     | GET /admin/chatbot
     | Route: admin.chatbot.index
     | Dashboard overview
    ═══════════════════════════════════════════════════════ */
    public function index()
    {
        $stats = [
            'total_conversations'  => DB::table('chatbot_conversations')->count(),
            'total_messages'       => DB::table('chatbot_messages')->count(),
            'total_contacts'       => DB::table('chatbot_admin_contacts')->count(),
            'pending_contacts'     => DB::table('chatbot_admin_contacts')
                                        ->where('status', 'pending')->count(),
            'replied_contacts'     => DB::table('chatbot_admin_contacts')
                                        ->where('status', 'replied')->count(),
            'today_conversations'  => DB::table('chatbot_conversations')
                                        ->whereDate('created_at', today())->count(),
            'ai_messages'          => DB::table('chatbot_messages')
                                        ->where('intent', 'ai')->count(),
            'faq_messages'         => DB::table('chatbot_messages')
                                        ->where('intent', 'faq')->count(),
            'live_conversations'   => DB::table('chatbot_conversations')
                                        ->where('mode', 'admin')
                                        ->whereNull('ended_at')
                                        ->count(),
        ];

        $recentContacts = DB::table('chatbot_admin_contacts')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $pendingContacts = DB::table('chatbot_admin_contacts')
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Active live chats
        $liveChats = DB::table('chatbot_conversations')
            ->leftJoin('users', 'chatbot_conversations.user_id', '=', 'users.id')
            ->select(
                'chatbot_conversations.*',
                'users.email as user_email',
                DB::raw('(SELECT COUNT(*) FROM chatbot_messages
                    WHERE chatbot_messages.conversation_id = chatbot_conversations.id
                    AND chatbot_messages.sender = "user"
                    AND chatbot_messages.is_read = 0) as unread_count')
            )
            ->where('chatbot_conversations.mode', 'admin')
            ->whereNull('chatbot_conversations.ended_at')
            ->orderByDesc('chatbot_conversations.updated_at')
            ->limit(5)
            ->get();

        return view('admin.chatbot.index', compact(
            'stats',
            'recentContacts',
            'pendingContacts',
            'liveChats'
        ));
    }

    /* ═══════════════════════════════════════════════════════
     | GET /admin/chatbot/contacts
     | Route: admin.chatbot.contacts
    ═══════════════════════════════════════════════════════ */
    public function contacts(Request $request)
    {
        $query = DB::table('chatbot_admin_contacts')
            ->leftJoin('users', 'chatbot_admin_contacts.user_id', '=', 'users.id');

        if ($request->filled('status')) {
            $query->where('chatbot_admin_contacts.status', $request->status);
        }

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('chatbot_admin_contacts.name',    'like', $s)
                  ->orWhere('chatbot_admin_contacts.email',   'like', $s)
                  ->orWhere('chatbot_admin_contacts.subject', 'like', $s)
                  ->orWhere('chatbot_admin_contacts.message', 'like', $s);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('chatbot_admin_contacts.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('chatbot_admin_contacts.created_at', '<=', $request->date_to);
        }

        $contacts = $query
            ->select(
                'chatbot_admin_contacts.*',
                'users.email as user_account_email',
                'users.user_type as user_role'
            )
            ->orderByRaw("FIELD(chatbot_admin_contacts.status, 'pending', 'read', 'replied', 'closed')")
            ->orderByDesc('chatbot_admin_contacts.created_at')
            ->paginate(20)
            ->withQueryString();

        $stats = DB::table('chatbot_admin_contacts')
            ->selectRaw("
                COUNT(*)                          AS total,
                SUM(status = 'pending')           AS pending,
                SUM(status = 'read')              AS read_count,
                SUM(status = 'replied')           AS replied,
                SUM(status = 'closed')            AS closed
            ")
            ->first();

        return view('admin.chatbot.contacts', compact('contacts', 'stats'));
    }

    /* ═══════════════════════════════════════════════════════
     | GET /admin/chatbot/contacts/{id}
     | Route: admin.chatbot.contacts.show
    ═══════════════════════════════════════════════════════ */
    public function showContact(int $id)
    {
        $contact = DB::table('chatbot_admin_contacts')
            ->leftJoin('users', 'chatbot_admin_contacts.user_id', '=', 'users.id')
            ->select(
                'chatbot_admin_contacts.*',
                'users.email as user_account_email',
                'users.user_type as user_role'
            )
            ->where('chatbot_admin_contacts.id', $id)
            ->first();

        if (! $contact) {
            abort(404);
        }

        // Auto mark as read
        if ($contact->status === 'pending') {
            DB::table('chatbot_admin_contacts')
                ->where('id', $id)
                ->update(['status' => 'read', 'updated_at' => now()]);
            $contact->status = 'read';
        }

        // Conversation messages if linked
        $messages = collect();
        if ($contact->conversation_id) {
            $messages = DB::table('chatbot_messages')
                ->where('conversation_id', $contact->conversation_id)
                ->orderBy('created_at')
                ->get();
        }

        // Related patient profile
        $patient = null;
        if ($contact->user_id) {
            $patient = DB::table('patients')
                ->where('user_id', $contact->user_id)
                ->first();
        }

        return view('admin.chatbot.contact-show', compact('contact', 'messages', 'patient'));
    }

    /* ═══════════════════════════════════════════════════════
     | POST /admin/chatbot/contacts/{id}/reply
     | Route: admin.chatbot.contacts.reply
    ═══════════════════════════════════════════════════════ */
    public function replyContact(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'reply' => 'required|string|max:2000',
        ]);

        $contact = DB::table('chatbot_admin_contacts')
            ->where('id', $id)
            ->first();

        if (! $contact) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found.',
            ], 404);
        }

        DB::table('chatbot_admin_contacts')
            ->where('id', $id)
            ->update([
                'admin_reply' => $request->reply,
                'replied_at'  => now(),
                'status'      => 'replied',
                'updated_at'  => now(),
            ]);

        // Notify the patient
        if ($contact->user_id) {
            try {
                DB::table('notifications')->insert([
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id'   => $contact->user_id,
                    'type'            => 'chatbot_reply',
                    'title'           => '💬 Support Team Replied',
                    'message'         => 'Your support request "'
                                       . Str::limit($contact->subject ?? 'Support Request', 50)
                                       . '" has been answered.',
                    'related_type'    => 'chatbot_contact',
                    'related_id'      => $id,
                    'is_read'         => 0,
                    'read_at'         => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            } catch (\Exception $e) {
                Log::warning('Reply notification failed: ' . $e->getMessage());
            }
        }

        // Also inject reply into linked conversation if it exists
        if ($contact->conversation_id) {
            try {
                DB::table('chatbot_messages')->insert([
                    'conversation_id' => $contact->conversation_id,
                    'sender'          => 'admin',
                    'message'         => $request->reply,
                    'intent'          => 'support_reply',
                    'is_read'         => 0,
                    'created_at'      => now(),
                ]);
            } catch (\Exception $e) {
                Log::warning('Reply inject to conversation failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Reply sent successfully.',
            'admin_reply' => $request->reply,
            'replied_at'  => now()->format('M d, Y h:i A'),
        ]);
    }

    /* ═══════════════════════════════════════════════════════
     | PUT /admin/chatbot/contacts/{id}/status
     | Route: admin.chatbot.contacts.status
    ═══════════════════════════════════════════════════════ */
    public function updateContactStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,read,replied,closed',
        ]);

        $updated = DB::table('chatbot_admin_contacts')
            ->where('id', $id)
            ->update([
                'status'     => $request->status,
                'updated_at' => now(),
            ]);

        if (! $updated) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status'  => $request->status,
        ]);
    }

    /* ═══════════════════════════════════════════════════════
     | DELETE /admin/chatbot/contacts/{id}
     | Route: admin.chatbot.contacts.destroy
    ═══════════════════════════════════════════════════════ */
    public function destroyContact(int $id): JsonResponse
    {
        DB::table('chatbot_admin_contacts')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted.',
        ]);
    }

    /* ═══════════════════════════════════════════════════════
     | GET /admin/chatbot/faqs
     | Route: admin.chatbot.faqs
    ═══════════════════════════════════════════════════════ */
    public function faqs()
    {
        $faqs = DB::table('chatbot_faqs')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $totalFaqs    = $faqs->count();
        $activeFaqs   = $faqs->where('is_active', 1)->count();
        $inactiveFaqs = $faqs->where('is_active', 0)->count();
        $withRoutes   = $faqs->whereNotNull('route_name')->count();

        return view('admin.chatbot.faqs', compact(
            'faqs',
            'totalFaqs',
            'activeFaqs',
            'inactiveFaqs',
            'withRoutes'
        ));
    }

    /* ═══════════════════════════════════════════════════════
     | POST /admin/chatbot/faqs
     | Route: admin.chatbot.faqs.store
    ═══════════════════════════════════════════════════════ */
    public function storeFaq(Request $request): JsonResponse
    {
        $request->validate([
            'question'    => 'required|string|max:500',
            'answer'      => 'required|string',
            'intent_key'  => 'nullable|string|max:100',
            'route_name'  => 'nullable|string|max:150',
            'route_label' => 'nullable|string|max:150',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $id = DB::table('chatbot_faqs')->insertGetId([
            'question'    => $request->question,
            'answer'      => $request->answer,
            'intent_key'  => $request->intent_key  ?: null,
            'route_name'  => $request->route_name  ?: null,
            'route_label' => $request->route_label ?: null,
            'is_active'   => 1,
            'sort_order'  => $request->sort_order  ?? 0,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $faq = DB::table('chatbot_faqs')->find($id);

        return response()->json([
            'success' => true,
            'id'      => $id,
            'faq'     => $faq,
        ]);
    }

    /* ═══════════════════════════════════════════════════════
     | PUT /admin/chatbot/faqs/{id}
     | Route: admin.chatbot.faqs.update
    ═══════════════════════════════════════════════════════ */
    public function updateFaq(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'question'    => 'required|string|max:500',
            'answer'      => 'required|string',
            'intent_key'  => 'nullable|string|max:100',
            'route_name'  => 'nullable|string|max:150',
            'route_label' => 'nullable|string|max:150',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $exists = DB::table('chatbot_faqs')->where('id', $id)->exists();
        if (! $exists) {
            return response()->json(['success' => false, 'message' => 'FAQ not found.'], 404);
        }

        DB::table('chatbot_faqs')->where('id', $id)->update([
            'question'    => $request->question,
            'answer'      => $request->answer,
            'intent_key'  => $request->intent_key  ?: null,
            'route_name'  => $request->route_name  ?: null,
            'route_label' => $request->route_label ?: null,
            'is_active'   => $request->has('is_active') ? (int) $request->is_active : 1,
            'sort_order'  => $request->sort_order ?? 0,
            'updated_at'  => now(),
        ]);

        $faq = DB::table('chatbot_faqs')->find($id);

        return response()->json([
            'success' => true,
            'faq'     => $faq,
        ]);
    }

    /* ═══════════════════════════════════════════════════════
     | DELETE /admin/chatbot/faqs/{id}
     | Route: admin.chatbot.faqs.destroy
    ═══════════════════════════════════════════════════════ */
    public function destroyFaq(int $id): JsonResponse
    {
        DB::table('chatbot_faqs')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'FAQ deleted.',
        ]);
    }

    /* ═══════════════════════════════════════════════════════
     | PATCH /admin/chatbot/faqs/{id}/toggle
     | Route: admin.chatbot.faqs.toggle
     | Toggle active/inactive
    ═══════════════════════════════════════════════════════ */
    public function toggleFaq(int $id): JsonResponse
    {
        $faq = DB::table('chatbot_faqs')->where('id', $id)->first();

        if (! $faq) {
            return response()->json(['success' => false, 'message' => 'FAQ not found.'], 404);
        }

        $newStatus = $faq->is_active ? 0 : 1;

        DB::table('chatbot_faqs')->where('id', $id)->update([
            'is_active'  => $newStatus,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success'   => true,
            'is_active' => $newStatus,
        ]);
    }

    /* ═══════════════════════════════════════════════════════
     | GET /admin/chatbot/conversations
     | Route: admin.chatbot.conversations
    ═══════════════════════════════════════════════════════ */
    public function conversations(Request $request)
    {
        $query = DB::table('chatbot_conversations')
            ->leftJoin('users', 'chatbot_conversations.user_id', '=', 'users.id');

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('chatbot_conversations.session_id', 'like', $s)
                  ->orWhere('chatbot_conversations.guest_name', 'like', $s)
                  ->orWhere('users.email', 'like', $s);
            });
        }

        if ($request->filled('mode')) {
            $query->where('chatbot_conversations.mode', $request->mode);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('chatbot_conversations.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('chatbot_conversations.created_at', '<=', $request->date_to);
        }

        $conversations = $query
            ->select(
                'chatbot_conversations.*',
                'users.email     as user_email',
                'users.user_type as user_role',
                DB::raw('(SELECT COUNT(*) FROM chatbot_messages
                    WHERE chatbot_messages.conversation_id = chatbot_conversations.id)
                    AS message_count'),
                DB::raw('(SELECT COUNT(*) FROM chatbot_messages
                    WHERE chatbot_messages.conversation_id = chatbot_conversations.id
                    AND chatbot_messages.sender = "user"
                    AND chatbot_messages.is_read = 0)
                    AS unread_count')
            )
            ->orderByDesc('chatbot_conversations.updated_at')
            ->paginate(20)
            ->withQueryString();

        $modeCounts = DB::table('chatbot_conversations')
            ->selectRaw("
                COUNT(*)              AS total,
                SUM(mode = 'bot')     AS bot_count,
                SUM(mode = 'admin')   AS admin_count
            ")
            ->first();

        return view('admin.chatbot.conversations', compact('conversations', 'modeCounts'));
    }

    /* ═══════════════════════════════════════════════════════
     | GET /admin/chatbot/conversations/{id}
     | Route: admin.chatbot.conversations.show
    ═══════════════════════════════════════════════════════ */
    public function showConversation(int $id)
    {
        $conversation = DB::table('chatbot_conversations')
            ->leftJoin('users', 'chatbot_conversations.user_id', '=', 'users.id')
            ->select(
                'chatbot_conversations.*',
                'users.email     as user_email',
                'users.user_type as user_role'
            )
            ->where('chatbot_conversations.id', $id)
            ->first();

        if (! $conversation) {
            abort(404);
        }

        $messages = DB::table('chatbot_messages')
            ->where('conversation_id', $id)
            ->orderBy('created_at')
            ->get();

        // Mark all user messages as read
        DB::table('chatbot_messages')
            ->where('conversation_id', $id)
            ->where('sender', 'user')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        // Patient profile if registered
        $patient = null;
        if ($conversation->user_id) {
            $patient = DB::table('patients')
                ->where('user_id', $conversation->user_id)
                ->first();
        }

        // Linked contact request (if any)
        $linkedContact = null;
        if ($conversation->id) {
            $linkedContact = DB::table('chatbot_admin_contacts')
                ->where('conversation_id', $conversation->id)
                ->orderByDesc('created_at')
                ->first();
        }

        return view('admin.chatbot.conversation-show', compact(
            'conversation',
            'messages',
            'patient',
            'linkedContact'
        ));
    }

    /* ═══════════════════════════════════════════════════════
     | POST /admin/chatbot/live-reply
     | Route: admin.chatbot.live.reply
     | Admin sends live message to patient
    ═══════════════════════════════════════════════════════ */
    public function liveReply(Request $request): JsonResponse
    {
        $request->validate([
            'conv_id' => 'required|integer',
            'message' => 'required|string|max:2000',
        ]);

        $conv = DB::table('chatbot_conversations')
            ->where('id', $request->conv_id)
            ->first();

        if (! $conv) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation not found.',
            ], 404);
        }

        // Insert admin message
        $msgId = DB::table('chatbot_messages')->insertGetId([
            'conversation_id' => $conv->id,
            'sender'          => 'admin',
            'message'         => $request->message,
            'intent'          => 'admin_reply',
            'is_read'         => 0,
            'created_at'      => now(),
        ]);

        // Update conversation timestamp
        DB::table('chatbot_conversations')
            ->where('id', $conv->id)
            ->update(['updated_at' => now()]);

        // Notify patient if registered
        if ($conv->user_id) {
            try {
                DB::table('notifications')->insert([
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id'   => $conv->user_id,
                    'type'            => 'live_chat',
                    'title'           => '💬 Support Team Replied',
                    'message'         => Str::limit($request->message, 100),
                    'related_type'    => 'chatbot_conversation',
                    'related_id'      => $conv->id,
                    'is_read'         => 0,
                    'read_at'         => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            } catch (\Exception $e) {
                Log::warning('Live reply notify error: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'msg_id'  => $msgId,
            'time'    => now()->format('h:i A'),
        ]);
    }

    /* ═══════════════════════════════════════════════════════
     | POST /admin/chatbot/conversations/{id}/end
     | Route: admin.chatbot.conversations.end
     | End / close a live conversation
    ═══════════════════════════════════════════════════════ */
    public function endConversation(int $id): JsonResponse
    {
        $conv = DB::table('chatbot_conversations')->where('id', $id)->first();

        if (! $conv) {
            return response()->json(['success' => false, 'message' => 'Not found.'], 404);
        }

        DB::table('chatbot_conversations')->where('id', $id)->update([
            'ended_at'   => now(),
            'mode'       => 'bot',
            'updated_at' => now(),
        ]);

        // Notify patient
        if ($conv->user_id) {
            try {
                DB::table('notifications')->insert([
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id'   => $conv->user_id,
                    'type'            => 'live_chat',
                    'title'           => '✅ Chat Session Ended',
                    'message'         => 'Your live support session has been closed by the admin. You can start a new chat anytime.',
                    'related_type'    => 'chatbot_conversation',
                    'related_id'      => $id,
                    'is_read'         => 0,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            } catch (\Exception $e) {
                Log::warning('End conversation notify error: ' . $e->getMessage());
            }
        }

        return response()->json(['success' => true]);
    }

    /* ═══════════════════════════════════════════════════════
     | DELETE /admin/chatbot/conversations/{id}
     | Route: admin.chatbot.conversations.destroy
     | Delete entire conversation + messages
    ═══════════════════════════════════════════════════════ */
    public function destroyConversation(int $id): JsonResponse
    {
        DB::table('chatbot_messages')->where('conversation_id', $id)->delete();
        DB::table('chatbot_conversations')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conversation deleted.',
        ]);
    }

    /* ═══════════════════════════════════════════════════════
     | GET /admin/chatbot/stats/ajax
     | Route: admin.chatbot.stats
     | AJAX stats refresh for dashboard
    ═══════════════════════════════════════════════════════ */
    public function ajaxStats(): JsonResponse
    {
        return response()->json([
            'pending_contacts'   => DB::table('chatbot_admin_contacts')
                                      ->where('status', 'pending')->count(),
            'live_conversations' => DB::table('chatbot_conversations')
                                      ->where('mode', 'admin')
                                      ->whereNull('ended_at')->count(),
            'today_chats'        => DB::table('chatbot_conversations')
                                      ->whereDate('created_at', today())->count(),
            'total_messages'     => DB::table('chatbot_messages')->count(),
        ]);
    }
}
