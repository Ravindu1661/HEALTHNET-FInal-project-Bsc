@extends('admin.layouts.master')
@section('title', 'Support Request #' . $contact->id)
@section('page-title', 'Support Request Details')

@section('content')
<div class="row g-4">

    {{-- ── LEFT: Details + Reply ─────────────────────────── --}}
    <div class="col-lg-4">

        {{-- Contact Info Card --}}
        <div class="dashboard-card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Contact Details</h6>
                <span class="badge bg-{{ $contact->status==='pending'?'warning text-dark':($contact->status==='read'?'info':($contact->status==='replied'?'success':'secondary')) }}">
                    {{ ucfirst($contact->status) }}
                </span>
            </div>
            <div class="card-body p-0">
                @foreach([
                    ['icon'=>'user',     'label'=>'Name',     'value'=>$contact->name],
                    ['icon'=>'envelope', 'label'=>'Email',    'value'=>$contact->email ?? '—'],
                    ['icon'=>'phone',    'label'=>'Phone',    'value'=>$contact->phone ?? '—'],
                    ['icon'=>'tag',      'label'=>'Subject',  'value'=>$contact->subject ?? '—'],
                    ['icon'=>'calendar', 'label'=>'Submitted','value'=>\Carbon\Carbon::parse($contact->created_at)->format('M d, Y h:i A')],
                ] as $row)
                <div class="hn-detail-row">
                    <i class="fas fa-{{ $row['icon'] }} text-primary"></i>
                    <div>
                        <div class="hn-detail-label">{{ $row['label'] }}</div>
                        <div class="hn-detail-val">{{ $row['value'] }}</div>
                    </div>
                </div>
                @endforeach
                @if($contact->user_account_email)
                <div class="hn-detail-row">
                    <i class="fas fa-user-check text-success"></i>
                    <div>
                        <div class="hn-detail-label">Registered Account</div>
                        <div class="hn-detail-val">{{ $contact->user_account_email }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Original Message --}}
        <div class="dashboard-card mb-3">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-comment-alt me-2"></i>Message</h6></div>
            <div class="card-body">
                <div class="hn-msg-box">{{ $contact->message }}</div>
            </div>
        </div>

        {{-- Existing Reply --}}
        @if($contact->admin_reply)
        <div class="dashboard-card mb-3">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-reply me-2 text-success"></i>Your Reply</h6></div>
            <div class="card-body">
                <div class="hn-reply-box">{{ $contact->admin_reply }}</div>
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($contact->replied_at)->format('M d, Y h:i A') }}
                </small>
            </div>
        </div>
        @endif

        {{-- Reply Form --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-paper-plane me-2"></i>{{ $contact->admin_reply ? 'Update Reply' : 'Send Reply' }}</h6>
            </div>
            <div class="card-body">
                <textarea id="reply-text" class="form-control mb-3" rows="4" placeholder="Type your reply...">{{ $contact->admin_reply ?? '' }}</textarea>
                <div id="reply-alert" class="mb-2" style="display:none"></div>
                <div class="d-flex gap-2">
                    <button onclick="submitReply({{ $contact->id }})" id="reply-btn" class="btn btn-primary btn-sm">
                        <i class="fas fa-paper-plane me-1"></i>{{ $contact->admin_reply ? 'Update' : 'Send Reply' }}
                    </button>
                    <a href="{{ route('admin.chatbot.contacts') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- ── RIGHT: Conversation History ────────────────────── --}}
    <div class="col-lg-8">
        <div class="dashboard-card" style="display:flex;flex-direction:column;height:calc(100vh - 160px)">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fas fa-comments text-primary"></i>
                <h6 class="mb-0">Chat Conversation History</h6>
                @if($contact->conversation_id)
                <a href="{{ route('admin.chatbot.conversations.show', $contact->conversation_id) }}"
                   class="btn btn-xs btn-outline-primary ms-auto">
                    <i class="fas fa-external-link-alt me-1"></i>Full Conversation
                </a>
                @endif
            </div>
            <div class="hn-conv-area" id="convArea">
                @forelse($messages as $msg)
                <div class="hn-wa-row hn-wa-{{ $msg->sender }}">
                    <div class="hn-wa-icon">{{ $msg->sender==='user'?'👤':($msg->sender==='admin'?'👨‍⚕️':'🏥') }}</div>
                    <div class="hn-wa-bubble">
                        <div class="hn-wa-text">{{ $msg->message }}</div>
                        <div class="hn-wa-meta">
                            {{ ucfirst($msg->sender) }}
                            @if($msg->intent) · <span class="hn-intent-badge">{{ $msg->intent }}</span>@endif
                            · {{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-comment-slash fa-3x mb-3 d-block opacity-50"></i>
                    <p>No conversation history linked to this request.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
.hn-detail-row{display:flex;gap:12px;padding:.65rem 1rem;border-bottom:1px solid #f5f5f5;align-items:flex-start}
.hn-detail-row:last-child{border-bottom:none}
.hn-detail-row>i{width:18px;margin-top:3px;flex-shrink:0;font-size:.85rem}
.hn-detail-label{font-size:.7rem;color:#999;font-weight:500;text-transform:uppercase}
.hn-detail-val{font-size:.83rem;color:#333;font-weight:500}
.hn-msg-box{background:#f8f9fa;border:1px solid #e0e0e0;border-radius:10px;padding:.875rem;font-size:.84rem;line-height:1.6;white-space:pre-wrap;color:#333}
.hn-reply-box{background:#e8f5e9;border:1px solid #a5d6a7;border-radius:10px;padding:.875rem;font-size:.84rem;line-height:1.6;white-space:pre-wrap;color:#1b5e20}
.btn-xs{padding:.2rem .6rem;font-size:.73rem}

/* WhatsApp-style conversation */
.hn-conv-area{flex:1;overflow-y:auto;padding:16px;background:#e5ddd5;display:flex;flex-direction:column;gap:8px}
.hn-conv-area::-webkit-scrollbar{width:4px}
.hn-conv-area::-webkit-scrollbar-thumb{background:#bbb;border-radius:2px}

.hn-wa-row{display:flex;gap:7px;align-items:flex-end;max-width:78%}
.hn-wa-user{align-self:flex-end;flex-direction:row-reverse;margin-left:auto}
.hn-wa-bot,.hn-wa-admin{align-self:flex-start}

.hn-wa-icon{width:28px;height:28px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;font-size:.78rem;flex-shrink:0;box-shadow:0 1px 3px rgba(0,0,0,.15)}

.hn-wa-bubble{display:flex;flex-direction:column}
.hn-wa-text{padding:8px 12px;border-radius:12px;font-size:.82rem;line-height:1.5;word-wrap:break-word;white-space:pre-wrap;box-shadow:0 1px 2px rgba(0,0,0,.1)}
.hn-wa-bot   .hn-wa-text{background:#fff;color:#333;border-radius:4px 12px 12px 12px}
.hn-wa-admin .hn-wa-text{background:#d1f2d3;color:#1b5e20;border-radius:4px 12px 12px 12px}
.hn-wa-user  .hn-wa-text{background:#dcf8c6;color:#333;border-radius:12px 4px 12px 12px}
.hn-wa-meta{font-size:.65rem;color:#999;margin-top:3px;padding:0 4px}
.hn-wa-user .hn-wa-meta{text-align:right}
.hn-intent-badge{background:#e3f2fd;color:#1565c0;border-radius:4px;padding:0 4px;font-size:.62rem}
</style>
@endpush

@push('scripts')
<script>
const Toast = Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:2000,timerProgressBar:true});

function submitReply(id){
    const text = document.getElementById('reply-text').value.trim();
    if(!text){document.getElementById('reply-text').focus();return;}
    const btn = document.getElementById('reply-btn');
    const alrt = document.getElementById('reply-alert');
    btn.disabled = true; btn.innerHTML='<i class="fas fa-spinner fa-spin me-1"></i>Sending…';
    fetch(`/admin/chatbot/contacts/${id}/reply`,{
        method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
        body:JSON.stringify({reply:text})
    }).then(r=>r.json()).then(d=>{
        if(d.success){
            alrt.style.display='block';alrt.className='alert alert-success py-2 small';
            alrt.innerHTML=`<i class="fas fa-check me-1"></i>Reply sent at ${d.replied_at}`;
            btn.innerHTML='<i class="fas fa-check me-1"></i>Sent!';
            Toast.fire({icon:'success',title:'Reply sent!'});
        }else{btn.disabled=false;btn.innerHTML='<i class="fas fa-paper-plane me-1"></i>Send Reply';}
    });
}

document.addEventListener('DOMContentLoaded',()=>{
    const c=document.getElementById('convArea');
    if(c) c.scrollTop=c.scrollHeight;
});
</script>
@endpush
