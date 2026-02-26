<?php
namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabChatController extends Controller
{
    private function getLab(): Laboratory
    {
        return Laboratory::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $lab = $this->getLab();
        $myId = Auth::id();

        // Get unique conversation partners
        $conversations = DB::table('chat_messages')
            ->where(function($q) use ($myId) {
                $q->where('sender_id', $myId)->orWhere('receiver_id', $myId);
            })
            ->select(DB::raw("
                CASE WHEN sender_id = $myId THEN receiver_id ELSE sender_id END as partner_id,
                MAX(created_at) as last_message_at
            "))
            ->groupBy('partner_id')
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function($row) use ($myId) {
                $user = \App\Models\User::find($row->partner_id);
                $unread = DB::table('chat_messages')
                    ->where('sender_id', $row->partner_id)
                    ->where('receiver_id', $myId)
                    ->where('is_read', false)->count();
                $last = DB::table('chat_messages')
                    ->where(function($q) use ($myId, $row) {
                        $q->where('sender_id', $myId)->where('receiver_id', $row->partner_id);
                    })->orWhere(function($q) use ($myId, $row) {
                        $q->where('sender_id', $row->partner_id)->where('receiver_id', $myId);
                    })->latest('created_at')->first();
                return [
                    'user'        => $user,
                    'partner_id'  => $row->partner_id,
                    'unread'      => $unread,
                    'last_message'=> $last?->message ?? '',
                    'last_at'     => $row->last_message_at,
                ];
            });

        return view('laboratory.chat.index', compact('lab', 'conversations'));
    }

    public function conversation($userId)
    {
        $lab = $this->getLab();
        $myId = Auth::id();
        $partner = \App\Models\User::findOrFail($userId);

        // Mark as read
        DB::table('chat_messages')
            ->where('sender_id', $userId)
            ->where('receiver_id', $myId)
            ->update(['is_read' => true, 'read_at' => now()]);

        $messages = DB::table('chat_messages')
            ->where(function($q) use ($myId, $userId) {
                $q->where('sender_id', $myId)->where('receiver_id', $userId);
            })->orWhere(function($q) use ($myId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $myId);
            })->orderBy('created_at')->get();

        return view('laboratory.chat.conversation', compact('lab', 'partner', 'messages', 'myId'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message'     => 'required_without:attachment|nullable|string|max:2000',
            'attachment'  => 'nullable|file|max:5120',
        ]);

        $myId = Auth::id();
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')
                ->store('chat-attachments/'.$myId, 'public');
        }

        DB::table('chat_messages')->insert([
            'sender_id'       => $myId,
            'receiver_id'     => $request->receiver_id,
            'message'         => $request->message ?? '',
            'attachment_path' => $attachmentPath,
            'is_read'         => false,
            'created_at'      => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function getMessages($userId)
    {
        $myId = Auth::id();
        $messages = DB::table('chat_messages')
            ->where(function($q) use ($myId, $userId) {
                $q->where('sender_id', $myId)->where('receiver_id', $userId);
            })->orWhere(function($q) use ($myId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $myId);
            })->orderBy('created_at')->get();

        return response()->json($messages);
    }
}
