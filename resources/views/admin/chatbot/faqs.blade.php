@extends('admin.layouts.master')
@section('title', 'FAQ Management')
@section('page-title', 'Chatbot FAQ Management')

@section('content')

<div class="dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-question-circle me-2"></i>FAQ / Quick Responses</h6>
        <button onclick="openFaqModal()" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Add FAQ
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="hn-table">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Question / Answer</th>
                        <th>Intent Keywords</th>
                        <th>Link Route</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th width="100" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                    <tr>
                        <td class="text-muted">{{ $faq->id }}</td>
                        <td>
                            <div class="fw-bold" style="font-size:.83rem">{{ Str::limit($faq->question,50) }}</div>
                            <small class="text-muted">{{ Str::limit($faq->answer,65) }}</small>
                        </td>
                        <td>
                            @if($faq->intent_key)
                            @foreach(explode(',', $faq->intent_key) as $kw)
                            <span class="badge bg-light text-dark border me-1" style="font-size:.68rem">{{ trim($kw) }}</span>
                            @endforeach
                            @else<span class="text-muted">—</span>@endif
                        </td>
                        <td>
                            @if($faq->route_name)
                            <code style="font-size:.72rem">{{ $faq->route_name }}</code>
                            @if($faq->route_label)<br><small class="text-muted">{{ $faq->route_label }}</small>@endif
                            @else<span class="text-muted">—</span>@endif
                        </td>
                        <td><span class="badge bg-secondary">{{ $faq->sort_order }}</span></td>
                        <td>
                            <span class="badge bg-{{ $faq->is_active?'success':'danger' }}">
                                {{ $faq->is_active?'Active':'Inactive' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button onclick="editFaq({{ json_encode($faq) }})" class="btn btn-warning" title="Edit" data-bs-toggle="tooltip"><i class="fas fa-edit"></i></button>
                                <button onclick="deleteFaq({{ $faq->id }},'{{ addslashes($faq->question) }}')" class="btn btn-danger" title="Delete" data-bs-toggle="tooltip"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">
                        <i class="fas fa-question-circle fa-3x mb-2 d-block opacity-50"></i>
                        No FAQs yet. Add your first FAQ.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="faqModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="faqModalTitle">Add FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="faq-id">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Question <span class="text-danger">*</span></label>
                        <input type="text" id="faq-question" class="form-control" placeholder="e.g. How to book an appointment?">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Answer <span class="text-danger">*</span></label>
                        <textarea id="faq-answer" class="form-control" rows="4" placeholder="Bot response..."></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Intent Keywords <small class="text-muted">(comma separated)</small></label>
                        <input type="text" id="faq-intent" class="form-control form-control-sm" placeholder="appointment,book,schedule">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Route Name</label>
                        <input type="text" id="faq-route" class="form-control form-control-sm" placeholder="patient.appointments.index">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Route Label (Button Text)</label>
                        <input type="text" id="faq-label" class="form-control form-control-sm" placeholder="📅 My Appointments">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sort Order</label>
                        <input type="number" id="faq-sort" class="form-control form-control-sm" value="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select id="faq-active" class="form-select form-select-sm">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="faq-save-btn" onclick="saveFaq()">
                    <i class="fas fa-save me-1"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.hn-table{width:100%;border-collapse:collapse}
.hn-table thead th{background:#f8f9fa;padding:.75rem 1rem;font-size:.74rem;text-transform:uppercase;font-weight:600;color:#555;border-bottom:2px solid #e0e0e0}
.hn-table tbody td{padding:.75rem 1rem;vertical-align:middle;border-bottom:1px solid #f0f0f0;font-size:.83rem}
.hn-table tbody tr:hover{background:#fafafa}
</style>
@endpush

@push('scripts')
<script>
const Toast=Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:1800,timerProgressBar:true});
const csrf=document.querySelector('meta[name="csrf-token"]').content;
let faqModal;
document.addEventListener('DOMContentLoaded',()=>{
    faqModal=new bootstrap.Modal(document.getElementById('faqModal'));
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el=>new bootstrap.Tooltip(el));
});

function openFaqModal(){
    document.getElementById('faqModalTitle').textContent='Add FAQ';
    ['faq-id','faq-question','faq-answer','faq-intent','faq-route','faq-label'].forEach(id=>document.getElementById(id).value='');
    document.getElementById('faq-sort').value='0';
    document.getElementById('faq-active').value='1';
    faqModal.show();
}

function editFaq(f){
    document.getElementById('faqModalTitle').textContent='Edit FAQ';
    document.getElementById('faq-id').value=f.id;
    document.getElementById('faq-question').value=f.question;
    document.getElementById('faq-answer').value=f.answer;
    document.getElementById('faq-intent').value=f.intent_key??'';
    document.getElementById('faq-route').value=f.route_name??'';
    document.getElementById('faq-label').value=f.route_label??'';
    document.getElementById('faq-sort').value=f.sort_order??0;
    document.getElementById('faq-active').value=f.is_active??1;
    faqModal.show();
}

function saveFaq(){
    const id=document.getElementById('faq-id').value;
    const q=document.getElementById('faq-question').value.trim();
    const a=document.getElementById('faq-answer').value.trim();
    if(!q||!a){alert('Question and Answer required.');return;}
    const btn=document.getElementById('faq-save-btn');
    btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin me-1"></i>Saving…';
    fetch(id?`/admin/chatbot/faqs/${id}`:'/admin/chatbot/faqs',{
        method:id?'PUT':'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
        body:JSON.stringify({question:q,answer:a,intent_key:document.getElementById('faq-intent').value||null,route_name:document.getElementById('faq-route').value||null,route_label:document.getElementById('faq-label').value||null,sort_order:parseInt(document.getElementById('faq-sort').value)||0,is_active:parseInt(document.getElementById('faq-active').value)})
    }).then(r=>r.json()).then(d=>{
        if(d.success){faqModal.hide();Toast.fire({icon:'success',title:id?'Updated!':'Added!'});setTimeout(()=>location.reload(),900);}
    }).finally(()=>{btn.disabled=false;btn.innerHTML='<i class="fas fa-save me-1"></i>Save';});
}

function deleteFaq(id,q){
    Swal.fire({title:'Delete FAQ?',html:`<small>${q}</small>`,icon:'error',showCancelButton:true,confirmButtonColor:'#dc3545',confirmButtonText:'Delete'})
    .then(r=>{if(!r.isConfirmed)return;
        fetch(`/admin/chatbot/faqs/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrf}})
        .then(r=>r.json()).then(d=>{if(d.success){Toast.fire({icon:'success',title:'Deleted!'});setTimeout(()=>location.reload(),900);}});
    });
}
</script>
@endpush
