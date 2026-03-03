@extends('admin.layouts.master')
@section('title', 'Chatbot Quick Links')
@section('page-title', 'Chatbot Quick Links')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Tab Navigation --}}
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.chatbot.index') }}">
            <i class="fas fa-chart-bar me-1"></i> Overview
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.chatbot.contacts') }}">
            <i class="fas fa-envelope me-1"></i> Contact Requests
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('admin.chatbot.quick-links') }}">
            <i class="fas fa-link me-1"></i> Quick Links
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.chatbot.faqs') }}">
            <i class="fas fa-question-circle me-1"></i> FAQs
        </a>
    </li>
</ul>

<div class="row g-4">
    {{-- Add / Routes Reference Column --}}
    <div class="col-lg-4">

        {{-- Add Form --}}
        <div class="dashboard-card mb-4">
            <div class="card-header">
                <h6><i class="fas fa-plus-circle me-2"></i>Add New Quick Link</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.chatbot.quick-links.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label form-label-sm fw-semibold">Label <span class="text-danger">*</span></label>
                        <input type="text" name="label" required placeholder="e.g. Book Appointment"
                            class="form-control form-control-sm @error('label') is-invalid @enderror">
                        @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-sm fw-semibold">URL / Path <span class="text-danger">*</span></label>
                        <input type="text" name="url" required placeholder="/patient/appointments/create"
                            class="form-control form-control-sm @error('url') is-invalid @enderror">
                        <div class="form-text">Use relative paths like <code>/patient/doctors</code></div>
                        @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-sm fw-semibold">Description</label>
                        <input type="text" name="description" placeholder="Short description (optional)"
                            class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-sm fw-semibold">Target Users</label>
                        <select name="target_user_type" class="form-select form-select-sm">
                            <option value="patient">Patient Only</option>
                            <option value="all">All Users</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-sm fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" value="0" min="0"
                            class="form-control form-control-sm">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-plus me-1"></i> Add Quick Link
                    </button>
                </form>
            </div>
        </div>

        {{-- Patient Routes Reference --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-map-signs me-2"></i>Patient Routes Reference</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach([
                        ['/patient/appointments/create','Book Appointment','calendar-plus','success'],
                        ['/patient/appointments','My Appointments','calendar-check','primary'],
                        ['/patient/doctors','Find Doctors','user-md','info'],
                        ['/patient/hospitals','Hospitals','hospital','warning'],
                        ['/patient/laboratories','Laboratories','flask','danger'],
                        ['/patient/pharmacies','Pharmacies','pills','secondary'],
                        ['/patient/lab-orders','Lab Orders','vials','primary'],
                        ['/patient/medicine-reminders','Medicine Reminders','bell','warning'],
                        ['/patient/profile','Profile','user','info'],
                        ['/patient/notifications','Notifications','bell','danger'],
                        ['/patient/Main-page','Dashboard','tachometer-alt','success'],
                    ] as [$url, $name, $icon, $color])
                    <div class="list-group-item list-group-item-action py-2 px-3 d-flex align-items-center justify-content-between"
                         style="cursor:pointer;" onclick="fillUrl('{{ $url }}', '{{ $name }}')"
                         title="Click to fill URL">
                        <span class="small fw-medium">
                            <i class="fas fa-{{ $icon }} text-{{ $color }} me-2"></i>{{ $name }}
                        </span>
                        <code class="text-primary small">{{ $url }}</code>
                    </div>
                    @endforeach
                </div>
                <div class="p-2 text-center">
                    <small class="text-muted"><i class="fas fa-hand-pointer me-1"></i>Click any row to fill URL field</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Links Table --}}
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-list me-2"></i>All Quick Links
                    <span class="badge bg-primary ms-2">{{ $links->count() }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="data-table table-hover">
                        <thead>
                            <tr>
                                <th width="50">Order</th>
                                <th>Label</th>
                                <th>URL</th>
                                <th>Target</th>
                                <th>Status</th>
                                <th class="text-center" width="130">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($links as $link)
                            <tr class="{{ !$link->is_active ? 'table-secondary opacity-75' : '' }}">
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $link->sort_order }}</span>
                                </td>
                                <td>
                                    <strong class="d-block">{{ $link->label }}</strong>
                                    @if($link->description)
                                        <small class="text-muted">{{ $link->description }}</small>
                                    @endif
                                </td>
                                <td>
                                    <code class="small text-primary">{{ $link->url }}</code>
                                </td>
                                <td>
                                    <span class="badge {{ $link->target_user_type == 'all' ? 'bg-info' : 'bg-primary' }}">
                                        <i class="fas fa-{{ $link->target_user_type == 'all' ? 'users' : 'user' }} me-1"></i>
                                        {{ ucfirst($link->target_user_type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($link->is_active)
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-pause-circle"></i> Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button onclick="editLink({{ json_encode($link) }})"
                                            class="btn btn-warning" title="Edit" data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteLink({{ $link->id }}, '{{ addslashes($link->label) }}')"
                                            class="btn btn-danger" title="Delete" data-bs-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-link fa-3x text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted">No quick links added yet</h5>
                                    <p class="text-muted small">Add links from the form on the left</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Quick Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label form-label-sm fw-semibold">Label *</label>
                        <input type="text" name="label" id="edit-label" required class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-sm fw-semibold">URL / Path *</label>
                        <input type="text" name="url" id="edit-url" required class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-sm fw-semibold">Description</label>
                        <input type="text" name="description" id="edit-desc" class="form-control form-control-sm">
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm fw-semibold">Target Users</label>
                            <select name="target_user_type" id="edit-target" class="form-select form-select-sm">
                                <option value="patient">Patient Only</option>
                                <option value="all">All Users</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" id="edit-sort" min="0" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-check mt-3">
                        <input type="checkbox" name="is_active" id="edit-active" value="1" class="form-check-input">
                        <label class="form-check-label small" for="edit-active">Active (visible in chatbot)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm text-dark">
                        <i class="fas fa-save me-1"></i> Update Link
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete form --}}
<form id="deleteLinkForm" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

@endsection

@push('styles')
<style>
    .data-table { width:100%; }
    .data-table thead th {
        background:#f8f9fa; border-bottom:2px solid #dee2e6;
        font-weight:600; text-transform:uppercase; font-size:0.72rem;
        padding:0.75rem; vertical-align:middle;
    }
    .data-table tbody td { padding:0.75rem; vertical-align:middle; border-top:1px solid #dee2e6; }
    .table-hover tbody tr:hover { background:#f8f9fa; }
    .nav-tabs .nav-link { color:#666; font-size:0.875rem; }
    .nav-tabs .nav-link.active { color:#4285F4; font-weight:600; }
    .list-group-item:hover { background:#f0f7ff !important; }
    .swal2-popup { font-size:0.875rem !important; }
    .swal2-title { font-size:1.1rem !important; }
</style>
@endpush

@push('scripts')
<script>
const Toast = Swal.mixin({
    toast:true, position:'top-end',
    showConfirmButton:false, timer:1800, timerProgressBar:true
});

function fillUrl(url, name) {
    document.querySelector('input[name="url"]').value  = url;
    document.querySelector('input[name="label"]').value = name;
    document.querySelector('input[name="url"]').focus();
    Toast.fire({ icon:'info', title:'URL filled: ' + url });
}

function editLink(link) {
    document.getElementById('editForm').action       = '/admin/chatbot/quick-links/' + link.id;
    document.getElementById('edit-label').value      = link.label;
    document.getElementById('edit-url').value        = link.url;
    document.getElementById('edit-desc').value       = link.description || '';
    document.getElementById('edit-target').value     = link.target_user_type;
    document.getElementById('edit-sort').value       = link.sort_order || 0;
    document.getElementById('edit-active').checked   = link.is_active == 1;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

function deleteLink(id, name) {
    Swal.fire({
        title: 'Delete Quick Link?',
        html: `<small class="text-muted">${name}</small><br><small class="text-danger">This link will be removed from chatbot.</small>`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then(result => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteLinkForm');
            form.action = `/admin/chatbot/quick-links/${id}`;
            form.submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
});
</script>
@endpush
