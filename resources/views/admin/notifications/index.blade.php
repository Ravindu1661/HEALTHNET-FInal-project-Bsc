@extends('admin.layouts.master')

@section('title', 'Notifications — Admin')
@section('page-title', 'Notifications')

@push('styles')
<style>
:root {
    --green:   #42a649;
    --green-dk:#2d7a32;
    --navy:    #1a3a5c;
    --radius:  12px;
    --border:  #e8ecf0;
    --bg:      #f4f6f9;
    --muted:   #7a8795;
}

.nw { max-width: 920px; }

/* ── Tabs ── */
.ntabs {
    display:flex; gap:.35rem; flex-wrap:wrap;
    background:#fff; border-radius:var(--radius);
    padding:.4rem; border:1px solid var(--border);
    margin-bottom:1rem;
    box-shadow:0 1px 6px rgba(0,0,0,.04);
}
.ntab {
    padding:.38rem .9rem; border-radius:8px;
    font-size:.76rem; font-weight:600; cursor:pointer;
    text-decoration:none; color:var(--muted);
    transition:all .17s; border:none; background:transparent;
    display:inline-flex; align-items:center; gap:.35rem;
}
.ntab:hover       { background:var(--bg); color:var(--navy); text-decoration:none; }
.ntab.active      { background:var(--navy); color:#fff; }
.ntab .cnt        { background:rgba(255,255,255,.22); font-size:.64rem; font-weight:700;
                    padding:.08rem .42rem; border-radius:20px; }
.ntab:not(.active) .cnt { background:#e8ecf0; color:var(--navy); }

/* ── Toolbar ── */
.ntoolbar {
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:.55rem; margin-bottom:.8rem;
}
.btn-t {
    padding:.38rem .8rem; border-radius:8px;
    font-size:.75rem; font-weight:600; cursor:pointer;
    border:1.5px solid var(--border); background:#fff;
    color:var(--navy); transition:all .17s; text-decoration:none;
    display:inline-flex; align-items:center; gap:.32rem;
}
.btn-t:hover              { border-color:var(--navy); background:var(--bg); }
.btn-t.danger             { border-color:#fca5a5; color:#dc2626; }
.btn-t.danger:hover       { background:#fef2f2; }
.btn-t.green              { background:var(--green); border-color:var(--green); color:#fff; }
.btn-t.green:hover        { background:var(--green-dk); }

/* ── Notification Card ── */
.ncard {
    background:#fff; border-radius:var(--radius);
    border:1.5px solid var(--border);
    box-shadow:0 1px 6px rgba(0,0,0,.04);
    overflow:hidden;
}

/* ── Notification Row ── */
.nrow {
    display:flex; align-items:flex-start; gap:.85rem;
    padding:.95rem 1.1rem;
    border-bottom:1px solid #f3f4f6;
    transition:background .15s;
    position:relative;
}
.nrow:last-child { border-bottom:none; }
.nrow:hover      { background:#fafbfc; }
.nrow.unread     { background:#f0f7ff; }
.nrow.unread::before {
    content:''; position:absolute; left:0; top:0; bottom:0;
    width:3px; background:#3b82f6; border-radius:2px;
}

/* ── Icon ── */
.nico {
    width:40px; height:40px; border-radius:10px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:.9rem;
}
.ico-appointment        { background:#e0f0ff; color:#2563eb; }
.ico-payment            { background:#d1fae5; color:#059669; }
.ico-workplace_request  { background:#fef3c7; color:#d97706; }
.ico-workplace_approved { background:#d1fae5; color:#059669; }
.ico-workplace_rejected { background:#fee2e2; color:#dc2626; }
.ico-prescription       { background:#ede9fe; color:#7c3aed; }
.ico-lab_report,
.ico-labreport          { background:#f0fdf4; color:#16a34a; }
.ico-reminder           { background:#fef9c3; color:#ca8a04; }
.ico-announcement       { background:#dbeafe; color:#2563eb; }
.ico-general            { background:#f3f4f6; color:#6b7280; }

/* ── Body ── */
.nbody     { flex:1; min-width:0; }
.ntitle    { font-size:.83rem; font-weight:700; color:#1e2a35; margin-bottom:.15rem; }
.nmsg      { font-size:.76rem; color:#4b5563; line-height:1.5; margin-bottom:.35rem; }
.nmeta     { display:flex; align-items:center; gap:.55rem; flex-wrap:wrap; margin-bottom:.5rem; }
.ntime     { font-size:.67rem; color:var(--muted); }
.ntype-tag {
    font-size:.6rem; font-weight:700;
    padding:.1rem .45rem; border-radius:20px;
    background:#e8ecf0; color:var(--navy);
    text-transform:uppercase; letter-spacing:.04em;
}

/* ── Workplace Request Action Panel ── */
.wp-action-panel {
    background:linear-gradient(135deg,#fffbeb,#fef9e7);
    border:1.5px solid #fde68a;
    border-radius:10px;
    padding:.7rem .9rem;
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:.6rem;
}
.wp-action-info { font-size:.76rem; color:#78350f; }
.wp-action-info strong { color:#92400e; }
.wp-action-btns { display:flex; gap:.5rem; }

.btn-approve {
    padding:.4rem .9rem; border-radius:8px;
    font-size:.75rem; font-weight:700; cursor:pointer;
    background:var(--green); border:none; color:#fff;
    display:inline-flex; align-items:center; gap:.35rem;
    transition:all .18s; box-shadow:0 2px 8px rgba(66,166,73,.3);
}
.btn-approve:hover    { background:var(--green-dk); transform:translateY(-1px); }
.btn-approve:disabled { opacity:.6; cursor:not-allowed; transform:none; }

.btn-reject {
    padding:.4rem .9rem; border-radius:8px;
    font-size:.75rem; font-weight:700; cursor:pointer;
    background:#fff; border:1.5px solid #fca5a5; color:#dc2626;
    display:inline-flex; align-items:center; gap:.35rem;
    transition:all .18s;
}
.btn-reject:hover    { background:#fef2f2; border-color:#ef4444; }
.btn-reject:disabled { opacity:.6; cursor:not-allowed; }

/* Approved / Rejected badge inside row */
.status-done {
    display:inline-flex; align-items:center; gap:.35rem;
    font-size:.74rem; font-weight:700; padding:.32rem .75rem;
    border-radius:20px;
}
.status-done.approved { background:#d1fae5; color:#065f46; }
.status-done.rejected { background:#fee2e2; color:#991b1b; }

/* ── Right actions ── */
.nactions { display:flex; gap:.28rem; flex-shrink:0; margin-top:2px; }
.na {
    width:29px; height:29px; border-radius:7px;
    border:1.5px solid var(--border); background:#fff;
    color:var(--muted); cursor:pointer; font-size:.7rem;
    display:flex; align-items:center; justify-content:center;
    transition:all .15s;
}
.na:hover         { border-color:var(--navy); color:var(--navy); }
.na.del:hover     { border-color:#fca5a5; color:#dc2626; background:#fef2f2; }
.na.check:hover   { border-color:var(--green); color:var(--green); }

/* ── Empty ── */
.nempty {
    text-align:center; padding:3.5rem 1rem; color:#c0c8d4;
}
.nempty i { font-size:2.5rem; display:block; margin-bottom:.65rem; }
.nempty p { font-size:.82rem; margin:0; }

/* Reject modal */
#rejectModal .modal-header { background:var(--navy); color:#fff; }
#rejectModal .modal-title  { font-size:.9rem; font-weight:700; }
</style>
@endpush

@section('content')
<div class="nw">

    {{-- Flash --}}
    @foreach(['success'=>'success','error'=>'danger'] as $sk=>$sc)
        @if(session($sk))
            <div class="alert alert-{{ $sc }} alert-dismissible fade show mb-3"
                 style="border-radius:10px;font-size:.82rem">
                <i class="fas fa-{{ $sc==='success'?'check-circle':'exclamation-circle' }} me-2"></i>
                {{ session($sk) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- ── Tabs ── --}}
    <div class="ntabs">
        <a href="{{ route('admin.notifications.index') }}"
           class="ntab {{ $filter==='all'&&!$type ? 'active':'' }}">
            <i class="fas fa-bell"></i> All
            <span class="cnt">{{ $totalCount }}</span>
        </a>
        <a href="{{ route('admin.notifications.index',['filter'=>'unread']) }}"
           class="ntab {{ $filter==='unread' ? 'active':'' }}">
            <i class="fas fa-circle" style="font-size:.52rem;"></i> Unread
            <span class="cnt">{{ $unreadCount }}</span>
        </a>
        <a href="{{ route('admin.notifications.index',['filter'=>'read']) }}"
           class="ntab {{ $filter==='read' ? 'active':'' }}">
            <i class="fas fa-check-double"></i> Read
        </a>

        @foreach([
            'workplace_request'  => ['fa-hospital','Join Requests'],
            'workplace_approved' => ['fa-check-circle','Approvals'],
            'workplace_rejected' => ['fa-times-circle','Rejections'],
            'appointment'        => ['fa-calendar-check','Appointments'],
            'payment'            => ['fa-money-bill-wave','Payments'],
        ] as $tk => [$ticon,$tlabel])
            @if(($typeCounts[$tk] ?? 0) > 0)
            <a href="{{ route('admin.notifications.index',['type'=>$tk]) }}"
               class="ntab {{ $type===$tk ? 'active':'' }}">
                <i class="fas {{ $ticon }}"></i> {{ $tlabel }}
                <span class="cnt">{{ $typeCounts[$tk] }}</span>
            </a>
            @endif
        @endforeach
    </div>

    {{-- ── Toolbar ── --}}
    <div class="ntoolbar">
        <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
            @if($unreadCount > 0)
            <form action="{{ route('admin.notifications.markAllRead') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-t green">
                    <i class="fas fa-check-double"></i> Mark All Read
                </button>
            </form>
            @endif
            <form action="{{ route('admin.notifications.destroyRead') }}" method="POST"
                  style="display:inline;"
                  onsubmit="return confirm('Delete all read notifications?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-t danger">
                    <i class="fas fa-trash-alt"></i> Clear Read
                </button>
            </form>
        </div>
        <span style="font-size:.73rem;color:var(--muted);">
            Showing {{ $notifications->firstItem()??0 }}–{{ $notifications->lastItem()??0 }}
            of {{ $notifications->total() }}
        </span>
    </div>

    {{-- ── List ── --}}
    <div class="ncard">

        @forelse($notifications as $n)
        @php
            $iconMap = [
                'appointment'        => ['ico-appointment',        'fa-calendar-check'],
                'payment'            => ['ico-payment',            'fa-money-bill-wave'],
                'workplace_request'  => ['ico-workplace_request',  'fa-hospital'],
                'workplace_approved' => ['ico-workplace_approved', 'fa-check-circle'],
                'workplace_rejected' => ['ico-workplace_rejected', 'fa-times-circle'],
                'prescription'       => ['ico-prescription',       'fa-prescription'],
                'lab_report'         => ['ico-lab_report',         'fa-flask'],
                'labreport'          => ['ico-labreport',          'fa-flask'],
                'reminder'           => ['ico-reminder',           'fa-bell'],
                'announcement'       => ['ico-announcement',       'fa-bullhorn'],
            ];
            [$icoClass,$icoIcon] = $iconMap[$n->type??'general'] ?? ['ico-general','fa-bell'];

            // Workplace request — related_id = doctor_workplaces.id
            $isWpRequest = ($n->type === 'workplace_request') && $n->related_id;

            // Load workplace status if this is a workplace request
            $wpStatus = null;
            $wpDoctor = null;
            $wpPlace  = null;
            if ($isWpRequest) {
                $wp = DB::table('doctor_workplaces')->where('id', $n->related_id)->first();
                $wpStatus = $wp->status ?? null;
                if ($wp) {
                    $wpDoctor = DB::table('doctors')->where('id', $wp->doctor_id)
                        ->select('id','first_name','last_name','specialization','profile_image','slmc_number','consultation_fee')
                        ->first();
                    $table   = $wp->workplace_type === 'hospital' ? 'hospitals' : 'medical_centres';
                    $wpPlace = DB::table($table)->where('id', $wp->workplace_id)
                        ->select('id','name','city','address')
                        ->first();
                }
            }
        @endphp

        <div class="nrow {{ $n->is_read ? '':'unread' }}" id="nr-{{ $n->id }}">

            {{-- Icon --}}
            <div class="nico {{ $icoClass }}">
                <i class="fas {{ $icoIcon }}"></i>
            </div>

            {{-- Body --}}
            <div class="nbody">
                <div class="ntitle">{{ $n->title ?? 'Notification' }}</div>
                <div class="nmsg">{{ $n->message ?? '' }}</div>
                <div class="nmeta">
                    <span class="ntime">
                        <i class="fas fa-clock" style="font-size:.6rem;"></i>
                        {{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}
                    </span>
                    <span class="ntype-tag">{{ str_replace('_',' ',$n->type??'general') }}</span>
                    @if(!$n->is_read)
                        <span style="width:7px;height:7px;border-radius:50%;
                                     background:#3b82f6;display:inline-block;"></span>
                    @endif
                </div>

                {{-- ── Workplace Request Action Panel ── --}}
                @if($isWpRequest)
                    @if($wpStatus === 'pending')
                    <div class="wp-action-panel" id="wpa-{{ $n->related_id }}">
                        {{-- Doctor + Location info --}}
                        <div class="wp-action-info">
                            @if($wpDoctor)
                                <strong>Dr. {{ $wpDoctor->first_name }} {{ $wpDoctor->last_name }}</strong>
                                @if($wpDoctor->specialization)
                                    <span style="color:#a16207;"> · {{ $wpDoctor->specialization }}</span>
                                @endif
                                @if($wpDoctor->slmc_number)
                                    <span style="color:#aaa;font-size:.68rem;"> · SLMC: {{ $wpDoctor->slmc_number }}</span>
                                @endif
                            @endif
                            @if($wpPlace)
                                <div style="margin-top:.2rem;font-size:.73rem;color:#92400e;">
                                    <i class="fas fa-hospital" style="font-size:.65rem;"></i>
                                    {{ $wpPlace->name }}
                                    @if($wpPlace->city) · {{ $wpPlace->city }} @endif
                                </div>
                            @endif
                        </div>

                        {{-- Approve / Reject buttons --}}
                        <div class="wp-action-btns">
                            <button class="btn-approve"
                                    id="btn-approve-{{ $n->related_id }}"
                                    onclick="approveWorkplace({{ $n->related_id }}, {{ $n->id }}, this)">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="btn-reject"
                                    id="btn-reject-{{ $n->related_id }}"
                                    onclick="openRejectModal({{ $n->related_id }}, {{ $n->id }})">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                    </div>

                    @elseif($wpStatus === 'approved')
                    <div>
                        <span class="status-done approved">
                            <i class="fas fa-check-circle"></i> Approved
                            @if(isset($wp) && $wp->approved_at)
                                <span style="font-weight:400;font-size:.68rem;opacity:.8;">
                                    · {{ \Carbon\Carbon::parse($wp->approved_at)->format('d M Y') }}
                                </span>
                            @endif
                        </span>
                    </div>

                    @elseif($wpStatus === 'rejected')
                    <div>
                        <span class="status-done rejected">
                            <i class="fas fa-times-circle"></i> Rejected
                        </span>
                    </div>

                    @else
                    <div style="font-size:.72rem;color:var(--muted);">
                        <i class="fas fa-exclamation-circle"></i> Workplace record not found.
                    </div>
                    @endif
                @endif
                {{-- ── end workplace panel ── --}}

            </div>

            {{-- Right action buttons --}}
            <div class="nactions">
                @if(!$n->is_read)
                <button class="na check" title="Mark as read"
                        onclick="markRead({{ $n->id }}, this)">
                    <i class="fas fa-check"></i>
                </button>
                @endif
                <form action="{{ route('admin.notifications.destroy', $n->id) }}"
                      method="POST" style="display:inline;"
                      onsubmit="return confirm('Delete this notification?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="na del" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>

        </div>
        @empty
        <div class="nempty">
            <i class="fas fa-bell-slash"></i>
            <p>No notifications found</p>
            @if($filter !== 'all' || $type)
                <a href="{{ route('admin.notifications.index') }}"
                   style="font-size:.78rem;color:#3b82f6;text-decoration:none;display:block;margin-top:.5rem;">
                    View all →
                </a>
            @endif
        </div>
        @endforelse

    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div style="background:#fff;border-radius:var(--radius);border:1px solid var(--border);
                margin-top:.6rem;padding:.8rem 1rem;">
        {{ $notifications->links() }}
    </div>
    @endif

</div>

{{-- ══════════════════════════════════════════
     REJECT MODAL
══════════════════════════════════════════ --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header" style="background:var(--navy);color:#fff;padding:.85rem 1.2rem;">
                <h5 class="modal-title" style="font-size:.9rem;font-weight:700;">
                    <i class="fas fa-times-circle me-2"></i> Reject Workplace Request
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.2rem;">
                <p style="font-size:.82rem;color:#555;margin-bottom:.8rem;">
                    Please provide a reason for rejection. The doctor will be notified.
                </p>
                <label style="font-size:.78rem;font-weight:600;color:var(--navy);display:block;margin-bottom:.35rem;">
                    Reason <span style="color:#dc2626;">*</span>
                </label>
                <textarea id="rejectReason" rows="3"
                          style="width:100%;padding:.65rem .85rem;border:1.5px solid var(--border);
                                 border-radius:9px;font-size:.82rem;resize:vertical;font-family:inherit;"
                          placeholder="e.g. Insufficient credentials, duplicate request..."></textarea>
                <div id="rejectReasonErr"
                     style="font-size:.72rem;color:#dc2626;margin-top:.25rem;display:none;">
                    Please provide a reason.
                </div>
            </div>
            <div class="modal-footer" style="padding:.75rem 1.2rem;gap:.5rem;">
                <button type="button" class="btn-t" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left"></i> Cancel
                </button>
                <button type="button" class="btn-reject" id="confirmRejectBtn"
                        style="padding:.45rem 1.1rem;"
                        onclick="confirmReject()">
                    <i class="fas fa-times"></i> Confirm Reject
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF    = document.querySelector('meta[name="csrf-token"]').content;
const BASE    = '{{ url("admin") }}';

let _rejectWpId   = null;
let _rejectNotifId = null;

/* ══ APPROVE ══════════════════════════════════════════════════ */
function approveWorkplace(wpId, notifId, btn) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Approving…';

    const rejectBtn = document.getElementById('btn-reject-' + wpId);
    if (rejectBtn) rejectBtn.disabled = true;

    fetch(`${BASE}/doctors/workplaces/${wpId}/approve`, {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            replaceActionPanel(wpId, 'approved');
            markReadSilent(notifId);
            showToast('✅ Workplace approved! Doctor has been notified.', 'success');
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Approve';
            if (rejectBtn) rejectBtn.disabled = false;
            showToast(d.message || 'Approval failed.', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Approve';
        if (rejectBtn) rejectBtn.disabled = false;
        showToast('Network error. Please try again.', 'error');
    });
}

/* ══ OPEN REJECT MODAL ════════════════════════════════════════ */
function openRejectModal(wpId, notifId) {
    _rejectWpId    = wpId;
    _rejectNotifId = notifId;
    document.getElementById('rejectReason').value = '';
    document.getElementById('rejectReasonErr').style.display = 'none';
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

/* ══ CONFIRM REJECT ═══════════════════════════════════════════ */
function confirmReject() {
    const reason = document.getElementById('rejectReason').value.trim();
    const errEl  = document.getElementById('rejectReasonErr');

    if (!reason) {
        errEl.style.display = 'block';
        return;
    }
    errEl.style.display = 'none';

    const btn = document.getElementById('confirmRejectBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rejecting…';

    fetch(`${BASE}/doctors/workplaces/${_rejectWpId}/reject`, {
        method:  'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept':       'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ reason }),
    })
    .then(r => r.json())
    .then(d => {
        bootstrap.Modal.getInstance(document.getElementById('rejectModal'))?.hide();
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-times"></i> Confirm Reject';

        if (d.success) {
            replaceActionPanel(_rejectWpId, 'rejected');
            markReadSilent(_rejectNotifId);
            showToast('❌ Workplace rejected. Doctor has been notified.', 'info');
        } else {
            showToast(d.message || 'Rejection failed.', 'error');
        }
    })
    .catch(() => {
        bootstrap.Modal.getInstance(document.getElementById('rejectModal'))?.hide();
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-times"></i> Confirm Reject';
        showToast('Network error. Please try again.', 'error');
    });
}

/* ══ REPLACE ACTION PANEL with status badge ═══════════════════ */
function replaceActionPanel(wpId, status) {
    const panel = document.getElementById('wpa-' + wpId);
    if (!panel) return;

    const icon  = status === 'approved' ? 'fa-check-circle' : 'fa-times-circle';
    const label = status === 'approved' ? 'Approved' : 'Rejected';
    const cls   = status === 'approved' ? 'approved' : 'rejected';

    panel.outerHTML = `
        <div>
            <span class="status-done ${cls}">
                <i class="fas ${icon}"></i> ${label}
            </span>
        </div>`;
}

/* ══ MARK READ (silent – no page reload) ═════════════════════ */
function markRead(id, btn) {
    fetch(`${BASE}/notifications/${id}/mark-read`, {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) return;
        const row = document.getElementById('nr-' + id);
        if (row) row.classList.remove('unread');
        if (btn) btn.closest('.na')?.remove();
        refreshBadge();
    });
}

function markReadSilent(id) {
    if (!id) return;
    fetch(`${BASE}/notifications/${id}/mark-read`, {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) return;
        const row = document.getElementById('nr-' + id);
        if (row) row.classList.remove('unread');
        const checkBtn = row?.querySelector('.na.check');
        if (checkBtn) checkBtn.remove();
        refreshBadge();
    })
    .catch(() => {});
}

/* ══ REFRESH TOPBAR BADGE ════════════════════════════════════ */
function refreshBadge() {
    fetch(`${BASE}/notifications/count`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        const badge = document.getElementById('notificationCount');
        if (!badge) return;
        const cnt = parseInt(d.unread_count ?? 0);
        badge.textContent   = cnt > 0 ? cnt : '';
        badge.style.display = cnt > 0 ? 'flex' : 'none';
    })
    .catch(() => {});
}

/* ══ TOAST ═══════════════════════════════════════════════════ */
function showToast(msg, type = 'success') {
    if (window.Swal) {
        Swal.fire({
            icon:              type === 'success' ? 'success' : type === 'info' ? 'info' : 'error',
            title:             type === 'success' ? 'Done!'  : type === 'info'  ? 'Done!' : 'Error',
            text:              msg,
            timer:             3000,
            timerProgressBar:  true,
            showConfirmButton: false,
            toast:             true,
            position:          'top-end',
        });
    } else {
        alert(msg);
    }
}

// Auto badge refresh every 60s
setInterval(refreshBadge, 60000);
</script>
@endpush
