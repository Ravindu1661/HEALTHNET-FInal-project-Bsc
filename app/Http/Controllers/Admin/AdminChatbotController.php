<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminChatbotController extends Controller
{
    // =====================================================================
    //  BASE QUERY — conversations + display info (all user roles)
    // =====================================================================

   private function conversationBaseQuery(): \Illuminate\Database\Query\Builder
{
    return DB::table('chatbot_conversations as cc')
        ->leftJoin('users as u', 'u.id', '=', 'cc.user_id')
        // patient
        ->leftJoin('patients as p', function ($j) {
            $j->on('p.user_id', '=', 'cc.user_id')
              ->where('u.user_type', '=', 'patient');
        })
        // doctor
        ->leftJoin('doctors as d', function ($j) {
            $j->on('d.user_id', '=', 'cc.user_id')
              ->where('u.user_type', '=', 'doctor');
        })
        // hospital
        ->leftJoin('hospitals as h', function ($j) {
            $j->on('h.user_id', '=', 'cc.user_id')
              ->where('u.user_type', '=', 'hospital');
        })
        // laboratory
        ->leftJoin('laboratories as lab', function ($j) {
            $j->on('lab.user_id', '=', 'cc.user_id')
              ->where('u.user_type', '=', 'laboratory');
        })
        // pharmacy
        ->leftJoin('pharmacies as ph', function ($j) {
            $j->on('ph.user_id', '=', 'cc.user_id')
              ->where('u.user_type', '=', 'pharmacy');
        })
        // medical centre
        ->leftJoin('medical_centres as mc', function ($j) {
            $j->on('mc.user_id', '=', 'cc.user_id')
              ->where('u.user_type', '=', 'medicalcentre');
        })
        ->select(
            'cc.id',
            'cc.session_id',
            'cc.user_id',
            'cc.mode',
            'cc.status',
            'cc.admin_id',
            'cc.guest_name',
            'cc.guest_email',
            'cc.created_at',
            'cc.updated_at',
            'u.user_type',
            'u.email as user_email',
            DB::raw("COALESCE(
                CASE
                    WHEN u.user_type = 'patient'       THEN CONCAT(p.first_name, ' ', p.last_name)
                    WHEN u.user_type = 'doctor'        THEN CONCAT(d.first_name, ' ', d.last_name)
                    WHEN u.user_type = 'hospital'      THEN h.name
                    WHEN u.user_type = 'laboratory'    THEN lab.name
                    WHEN u.user_type = 'pharmacy'      THEN ph.name
                    WHEN u.user_type = 'medicalcentre' THEN mc.name
                    WHEN u.user_type = 'admin'         THEN 'Admin'
                END,
                cc.guest_name,
                'Guest'
            ) as display_name"),
            DB::raw("COALESCE(u.email, cc.guest_email, '-') as display_email"),
            DB::raw("(
                SELECT COUNT(*) FROM chatbot_messages cm
                WHERE cm.conversation_id = cc.id
                  AND cm.is_read = 0
                  AND cm.sender_type = 'user'
            ) as unread_count"),
            DB::raw("(
                SELECT cm2.message FROM chatbot_messages cm2
                WHERE cm2.conversation_id = cc.id
                ORDER BY cm2.created_at DESC LIMIT 1
            ) as last_message"),
            DB::raw("(
                SELECT cm3.created_at FROM chatbot_messages cm3
                WHERE cm3.conversation_id = cc.id
                ORDER BY cm3.created_at DESC LIMIT 1
            ) as last_message_at")
        );
}

    // =====================================================================
    //  DASHBOARD
    // =====================================================================

    public function index(Request $request)
    {
        $query = $this->conversationBaseQuery();
        $this->applyFilters($query, $request);

        $conversations = $query->orderByDesc('cc.updated_at')->paginate(20);

        $stats = [
            'total'      => DB::table('chatbot_conversations')->count(),
            'active'     => DB::table('chatbot_conversations')->where('status', 'active')->count(),
            'admin_mode' => DB::table('chatbot_conversations')->where('mode', 'admin')->where('status', 'active')->count(),
            'unread'     => DB::table('chatbot_messages')->where('is_read', 0)->where('sender_type', 'user')->count(),
        ];

        return view('admin.chatbot.index', compact('conversations', 'stats'));
    }

    // =====================================================================
    //  CONVERSATIONS
    // =====================================================================

    public function conversations(Request $request)
    {
        $query = $this->conversationBaseQuery();
        $this->applyFilters($query, $request);

        $conversations = $query->orderByDesc('cc.updated_at')->paginate(20);

        return view('admin.chatbot.conversations', compact('conversations'));
    }

    public function showConversation($id)
    {
        $conversation = $this->conversationBaseQuery()
            ->where('cc.id', $id)
            ->first();

        if (!$conversation) {
            abort(404, 'Conversation not found.');
        }

        $messages = DB::table('chatbot_messages')
            ->where('conversation_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark user messages as read
        DB::table('chatbot_messages')
            ->where('conversation_id', $id)
            ->where('sender_type', 'user')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return view('admin.chatbot.conversation-show', compact('conversation', 'messages'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $conversation = DB::table('chatbot_conversations')
            ->where('id', $id)
            ->where('status', 'active')
            ->first();

        if (!$conversation) {
            return response()->json(['ok' => false, 'error' => 'Conversation not found or closed.'], 404);
        }

        $admin = Auth::user();

        DB::table('chatbot_messages')->insert([
            'conversation_id' => $id,
            'sender_type'     => 'admin',
            'sender_id'       => $admin->id,
            'message'         => trim($request->message),
            'is_read'         => 0,
            'created_at'      => now(),
        ]);

        DB::table('chatbot_conversations')->where('id', $id)->update([
            'admin_id'   => $conversation->admin_id ?? $admin->id,
            'updated_at' => now(),
        ]);

        return response()->json(['ok' => true, 'message' => 'Reply sent.']);
    }

    public function pollMessages(Request $request, $id)
    {
        $after = (int) $request->query('after', 0);

        $messages = DB::table('chatbot_messages')
            ->where('conversation_id', $id)
            ->where('id', '>', $after)
            ->orderBy('created_at', 'asc')
            ->get(['id', 'sender_type', 'sender_id', 'message', 'is_read', 'created_at']);

        // Auto-mark user messages as read
        $userMsgIds = $messages->where('sender_type', 'user')->pluck('id');
        if ($userMsgIds->isNotEmpty()) {
            DB::table('chatbot_messages')
                ->whereIn('id', $userMsgIds)
                ->update(['is_read' => 1]);
        }

        return response()->json(['ok' => true, 'messages' => $messages]);
    }

    public function close($id)
    {
        DB::table('chatbot_conversations')
            ->where('id', $id)
            ->update(['status' => 'closed', 'updated_at' => now()]);

        DB::table('chatbot_messages')->insert([
            'conversation_id' => $id,
            'sender_type'     => 'bot',
            'sender_id'       => null,
            'message'         => 'This conversation has been closed by the admin. Thank you for contacting HealthNet.',
            'is_read'         => 0,
            'created_at'      => now(),
        ]);

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.chatbot.conversations')->with('success', 'Conversation closed.');
    }

    public function reopen($id)
    {
        DB::table('chatbot_conversations')
            ->where('id', $id)
            ->update(['status' => 'active', 'mode' => 'admin', 'updated_at' => now()]);

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.chatbot.conversations.show', $id)->with('success', 'Conversation reopened.');
    }

    public function destroy($id)
    {
        DB::table('chatbot_messages')->where('conversation_id', $id)->delete();
        DB::table('chatbot_conversations')->where('id', $id)->delete();

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.chatbot.conversations')->with('success', 'Conversation deleted.');
    }

    // =====================================================================
    //  FAQs
    // =====================================================================

public function faqs()
{
    $faqs = DB::table('chatbot_faqs')
        ->orderBy('sort_order')
        ->orderByDesc('created_at')
        ->paginate(20);

    // recent conversations 5ක් ගන්න
    $conversations = $this->conversationBaseQuery()
        ->orderByDesc('cc.updated_at')
        ->limit(5)
        ->get();

    return view('admin.chatbot.faqs', compact('faqs', 'conversations'));
}


    public function storeFaq(Request $request)
    {
        $request->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'category'   => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable',
        ]);

        DB::table('chatbot_faqs')->insert([
            'question'   => trim($request->question),
            'answer'     => trim($request->answer),
            'category'   => $request->category ?? 'General',
            'is_active'  => $request->boolean('is_active', true) ? 1 : 0,
            'sort_order' => (int)($request->sort_order ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.chatbot.faqs')->with('success', 'FAQ created successfully.');
    }

    public function updateFaq(Request $request, $id)
    {
        $request->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'category'   => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable',
        ]);

        DB::table('chatbot_faqs')->where('id', $id)->update([
            'question'   => trim($request->question),
            'answer'     => trim($request->answer),
            'category'   => $request->category ?? 'General',
            'is_active'  => $request->boolean('is_active', true) ? 1 : 0,
            'sort_order' => (int)($request->sort_order ?? 0),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.chatbot.faqs')->with('success', 'FAQ updated successfully.');
    }

    public function destroyFaq($id)
    {
        DB::table('chatbot_faqs')->where('id', $id)->delete();
        return redirect()->route('admin.chatbot.faqs')->with('success', 'FAQ deleted.');
    }

    public function toggleFaq($id)
    {
        $faq = DB::table('chatbot_faqs')->where('id', $id)->first();
        if (!$faq) return response()->json(['ok' => false], 404);

        DB::table('chatbot_faqs')->where('id', $id)->update([
            'is_active'  => $faq->is_active ? 0 : 1,
            'updated_at' => now(),
        ]);

        return response()->json(['ok' => true, 'is_active' => !$faq->is_active]);
    }

    // =====================================================================
    //  QUICK LINKS  (url_path column — DB table structure අනුව)
    // =====================================================================

    public function links()
    {
        $links  = DB::table('chatbot_quick_links')->orderBy('sort_order')->get();
        $appUrl = rtrim(env('APP_URL', 'http://localhost:8000'), '/');

        return view('admin.chatbot.links', compact('links', 'appUrl'));
    }

    public function storeLink(Request $request)
    {
        $request->validate([
            'label'      => 'required|string|max:150',
            'url_path'   => 'required|string|max:500',
            'icon'       => 'nullable|string|max:100',
            'roles'      => 'nullable|array',
            'roles.*'    => 'string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable',
        ]);

        DB::table('chatbot_quick_links')->insert([
            'label'      => trim($request->label),
            'url_path'   => '/' . ltrim(trim($request->url_path), '/'),
            'icon'       => $request->icon ?? 'fas fa-link',
            'roles'      => json_encode($request->roles ?? []),
            'is_active'  => $request->boolean('is_active', true) ? 1 : 0,
            'sort_order' => (int)($request->sort_order ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.chatbot.links')->with('success', 'Quick link created.');
    }

    public function updateLink(Request $request, $id)
    {
        $request->validate([
            'label'      => 'required|string|max:150',
            'url_path'   => 'required|string|max:500',
            'icon'       => 'nullable|string|max:100',
            'roles'      => 'nullable|array',
            'roles.*'    => 'string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable',
        ]);

        DB::table('chatbot_quick_links')->where('id', $id)->update([
            'label'      => trim($request->label),
            'url_path'   => '/' . ltrim(trim($request->url_path), '/'),
            'icon'       => $request->icon ?? 'fas fa-link',
            'roles'      => json_encode($request->roles ?? []),
            'is_active'  => $request->boolean('is_active', true) ? 1 : 0,
            'sort_order' => (int)($request->sort_order ?? 0),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.chatbot.links')->with('success', 'Quick link updated.');
    }

    public function destroyLink($id)
    {
        DB::table('chatbot_quick_links')->where('id', $id)->delete();
        return redirect()->route('admin.chatbot.links')->with('success', 'Quick link deleted.');
    }

    public function toggleLink($id)
    {
        $link = DB::table('chatbot_quick_links')->where('id', $id)->first();
        if (!$link) return response()->json(['ok' => false], 404);

        DB::table('chatbot_quick_links')->where('id', $id)->update([
            'is_active'  => $link->is_active ? 0 : 1,
            'updated_at' => now(),
        ]);

        return response()->json(['ok' => true, 'is_active' => !$link->is_active]);
    }

    // =====================================================================
    //  PRIVATE HELPERS
    // =====================================================================

    private function applyFilters(\Illuminate\Database\Query\Builder $query, Request $request): void
    {
        if ($request->filled('status')) {
            $query->where('cc.status', $request->status);
        }

        if ($request->filled('mode')) {
            $query->where('cc.mode', $request->mode);
        }

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('u.email', 'like', $s)
                ->orWhere('cc.guest_name', 'like', $s)
                ->orWhere('cc.guest_email', 'like', $s)
                ->orWhere('p.first_name', 'like', $s)
                ->orWhere('p.last_name', 'like', $s)
                ->orWhere('d.first_name', 'like', $s)
                ->orWhere('d.last_name', 'like', $s)
                ->orWhere('h.name', 'like', $s)
                ->orWhere('lab.name', 'like', $s)
                ->orWhere('ph.name', 'like', $s)
                ->orWhere('mc.name', 'like', $s);
            });
        }
    }

}
