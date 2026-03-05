<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminChatbotController extends Controller
{
    // =====================================================================
    //  BASE QUERY — conversations + display info (all user roles)
    // =====================================================================

    private function conversationBaseQuery(): \Illuminate\Database\Query\Builder
    {
        return DB::table('chatbot_conversations as cc')
            ->leftJoin('users as u', 'u.id', '=', 'cc.user_id')
            ->leftJoin('patients as p', function ($j) {
                $j->on('p.user_id', '=', 'cc.user_id')
                  ->where('u.user_type', '=', 'patient');
            })
            ->leftJoin('doctors as d', function ($j) {
                $j->on('d.user_id', '=', 'cc.user_id')
                  ->where('u.user_type', '=', 'doctor');
            })
            ->leftJoin('hospitals as h', function ($j) {
                $j->on('h.user_id', '=', 'cc.user_id')
                  ->where('u.user_type', '=', 'hospital');
            })
            ->leftJoin('laboratories as lab', function ($j) {
                $j->on('lab.user_id', '=', 'cc.user_id')
                  ->where('u.user_type', '=', 'laboratory');
            })
            ->leftJoin('pharmacies as ph', function ($j) {
                $j->on('ph.user_id', '=', 'cc.user_id')
                  ->where('u.user_type', '=', 'pharmacy');
            })
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

    // =====================================================================
    //  REPLY — message store + email send
    // =====================================================================

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

        // Store admin message
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

        // ── Determine recipient email + name ──────────────────────────────
        $recipientEmail = null;
        $recipientName  = 'User';

        if ($conversation->user_id) {
            // Logged-in user
            $user = DB::table('users')->where('id', $conversation->user_id)->first();
            if ($user) {
                $recipientEmail = $user->email;

                // Try to get display name from relevant profile table
                $recipientName = match($user->user_type ?? '') {
                    'patient' => $this->_getProfileName(
                        DB::table('patients')->where('user_id', $user->id)->first(),
                        ['first_name', 'last_name']
                    ),
                    'doctor' => $this->_getProfileName(
                        DB::table('doctors')->where('user_id', $user->id)->first(),
                        ['first_name', 'last_name']
                    ),
                    'hospital'      => DB::table('hospitals')->where('user_id', $user->id)->value('name') ?? '',
                    'laboratory'    => DB::table('laboratories')->where('user_id', $user->id)->value('name') ?? '',
                    'pharmacy'      => DB::table('pharmacies')->where('user_id', $user->id)->value('name') ?? '',
                    'medicalcentre' => DB::table('medical_centres')->where('user_id', $user->id)->value('name') ?? '',
                    default         => $user->name ?? strtok($user->email, '@'),
                };

                $recipientName = $recipientName ?: strtok($user->email, '@');
            }
        } elseif ($conversation->guest_email) {
            // Guest user
            $recipientEmail = $conversation->guest_email;
            $recipientName  = $conversation->guest_name ?? 'Guest';
        }

        // ── Send email if recipient exists ────────────────────────────────
        $emailSent = false;
        if ($recipientEmail) {
            $emailSent = $this->sendReplyEmail(
                $recipientEmail,
                $recipientName,
                trim($request->message),
                $conversation,
                $admin
            );
        }

        return response()->json([
            'ok'            => true,
            'message'       => 'Reply sent.',
            'email_sent'    => $emailSent,
            'email_address' => $recipientEmail,
        ]);
    }

    // =====================================================================
    //  EMAIL HELPER
    // =====================================================================

    private function sendReplyEmail(
        string $toEmail,
        string $toName,
        string $replyMessage,
        object $conversation,
        object $admin
    ): bool {
        try {
            $adminName = $admin->name ?? 'HealthNet Support';
            $appName   = config('app.name', 'HealthNet');
            $appUrl    = rtrim(config('app.url', 'https://healthnet.lk'), '/');
            $convId    = $conversation->id;

            Mail::send([], [], function ($mail) use (
                $toEmail, $toName, $replyMessage,
                $adminName, $appName, $appUrl, $convId
            ) {
                $safeToName      = htmlspecialchars($toName,      ENT_QUOTES, 'UTF-8');
                $safeReply       = nl2br(htmlspecialchars($replyMessage, ENT_QUOTES, 'UTF-8'));
                $safeAdminName   = htmlspecialchars($adminName,   ENT_QUOTES, 'UTF-8');
                $safeAppName     = htmlspecialchars($appName,     ENT_QUOTES, 'UTF-8');

                $htmlBody = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>HealthNet Support Reply</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:'Segoe UI',Arial,sans-serif">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7fb;padding:30px 0">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0"
             style="background:#fff;border-radius:14px;overflow:hidden;
                    box-shadow:0 4px 24px rgba(13,110,253,.10);max-width:600px">

        <!-- Header -->
        <tr>
          <td style="background:linear-gradient(135deg,#0d6efd,#0a58ca);padding:32px;text-align:center">
            <div style="font-size:28px;margin-bottom:8px">❤️</div>
            <h1 style="color:#fff;margin:0;font-size:22px;font-weight:700">{$safeAppName} Support</h1>
            <p style="color:rgba(255,255,255,.85);margin:6px 0 0;font-size:13px">
              Reply to your chat message
            </p>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:32px 36px">
            <p style="font-size:16px;font-weight:600;color:#1e293b;margin:0 0 16px">
              Hello {$safeToName},
            </p>
            <p style="font-size:14px;color:#475569;margin:0 0 16px;line-height:1.6">
              Our support team has replied to your <strong>{$safeAppName}</strong> chat message:
            </p>

            <!-- Reply box -->
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="background:#f0f4ff;border-left:4px solid #0d6efd;
                           border-radius:8px;padding:16px 20px">
                  <p style="font-size:15px;color:#1e293b;margin:0;line-height:1.7">
                    {$safeReply}
                  </p>
                </td>
              </tr>
            </table>

            <!-- Replied by -->
            <p style="font-size:12px;color:#94a3b8;margin:10px 0 24px;text-align:right">
              Replied by: <strong style="color:#64748b">{$safeAdminName}</strong>
            </p>

            <!-- CTA note -->
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="background:#f8faff;border:1px solid #e2e8f0;
                           border-radius:10px;padding:14px 18px">
                  <p style="margin:0;font-size:13px;color:#475569;line-height:1.6">
                    💬 <strong>Need to continue the conversation?</strong><br>
                    Visit
                    <a href="{$appUrl}" style="color:#0d6efd;text-decoration:none;font-weight:600">
                      {$appUrl}
                    </a>
                    and use the chat widget to send another message.
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#f8faff;padding:18px 36px;text-align:center;
                     border-top:1px solid #e2e8f0">
            <p style="margin:0;font-size:12px;color:#94a3b8">
              {$safeAppName} &bull;
              <a href="{$appUrl}" style="color:#0d6efd;text-decoration:none">Visit Website</a>
              &bull; Conversation #{$convId}
            </p>
            <p style="margin:6px 0 0;font-size:11px;color:#cbd5e1">
              This email was sent because you contacted us via the HealthNet chat widget.
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
HTML;

                $mail->to($toEmail, $toName)
                     ->subject("HealthNet Support Reply 💬 (Conversation #{$convId})")
                     ->html($htmlBody);
            });

            return true;

        } catch (\Exception $e) {
            Log::error('AdminChatbot reply email failed', [
                'to'              => $toEmail,
                'conversation_id' => $conversation->id,
                'error'           => $e->getMessage(),
            ]);
            return false;
        }
    }

    // =====================================================================
    //  PROFILE NAME HELPER
    // =====================================================================

    private function _getProfileName(?object $profile, array $fields): string
    {
        if (!$profile) return '';
        return trim(implode(' ', array_map(
            fn($f) => $profile->{$f} ?? '',
            $fields
        )));
    }

    // =====================================================================
    //  POLL MESSAGES
    // =====================================================================

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

    // =====================================================================
    //  CLOSE / REOPEN / DELETE
    // =====================================================================

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
    //  QUICK LINKS
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
                $q->where('u.email',          'like', $s)
                  ->orWhere('cc.guest_name',  'like', $s)
                  ->orWhere('cc.guest_email', 'like', $s)
                  ->orWhere('p.first_name',   'like', $s)
                  ->orWhere('p.last_name',    'like', $s)
                  ->orWhere('d.first_name',   'like', $s)
                  ->orWhere('d.last_name',    'like', $s)
                  ->orWhere('h.name',         'like', $s)
                  ->orWhere('lab.name',       'like', $s)
                  ->orWhere('ph.name',        'like', $s)
                  ->orWhere('mc.name',        'like', $s);
            });
        }
    }
    public function switchMode(Request $request, $id)
{
    $request->validate(['mode' => 'required|in:bot,admin']);

    DB::table('chatbot_conversations')
        ->where('id', $id)
        ->update([
            'mode'       => $request->mode,
            'updated_at' => now(),
        ]);

    if ($request->expectsJson()) {
        return response()->json(['ok' => true, 'mode' => $request->mode]);
    }

    return redirect()->back()->with('success', 'Mode switched to ' . $request->mode);
}

}
