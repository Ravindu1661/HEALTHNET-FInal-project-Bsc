@extends('admin.layouts.master')

@section('title', 'Chatbot Quick Links')
@section('page-title', 'Chatbot Quick Links')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-link me-2 text-primary"></i>Chatbot Quick Links
            </h5>
            <small class="text-muted">
                Manage links shown in chatbot “Links” tab for each user role.
            </small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#linkModal">
            <i class="fas fa-plus me-1"></i> Add Link
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="dashboard-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Label</th>
                        <th>URL</th>
                        <th>Icon</th>
                        <th>Roles</th>
                        <th style="width:80px;">Order</th>
                        <th style="width:70px;">Active</th>
                        <th class="text-end" style="width:120px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($links as $link)
                        <tr>
                            <td class="text-muted small">{{ $link->id }}</td>
                            <td class="fw-semibold" style="font-size:13px;">
                                <i class="{{ $link->icon }} me-1 text-primary"></i>
                                {{ $link->label }}
                            </td>
                            <td style="font-size:12px;">
                                <code>{{ $link->url_path }}</code>
                                <a href="{{ $appUrl . $link->url_path }}" target="_blank"
                                   class="ms-1 text-muted" title="Open">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </td>
                            <td><code style="font-size:12px;">{{ $link->icon }}</code></td>
                            <td>
                                @php $roles = json_decode($link->roles ?? '[]', true); @endphp
                                @if(empty($roles))
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                        All
                                    </span>
                                @else
                                    @foreach($roles as $role)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle me-1">
                                            {{ ucfirst($role) }}
                                        </span>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-muted small">{{ $link->sort_order }}</td>
                            <td>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox"
                                           {{ $link->is_active ? 'checked' : '' }}
                                           onchange="toggleLink({{ $link->id }}, this)">
                                </div>
                            </td>
                            <td class="text-end">
                                <button type="button"
                                        onclick="editLink({{ json_encode($link) }})"
                                        class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button"
                                        onclick="deleteLink({{ $link->id }})"
                                        class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-link fa-2x mb-2 d-block opacity-25"></i>
                                No quick links yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Link Modal --}}
<div class="modal fade" id="linkModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="link-form" method="POST" action="{{ route('admin.chatbot.links.store') }}">
                @csrf
                <input type="hidden" name="_method" id="link-method" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="link-modal-title">
                        <i class="fas fa-link me-2 text-primary"></i>Add Quick Link
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Label <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="label" id="link-label"
                                   class="form-control" maxlength="150" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                URL Path <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text text-muted" style="font-size:12px;">
                                    {{ $appUrl }}
                                </span>
                                <input type="text" name="url_path" id="link-route"
                                       class="form-control"
                                       placeholder="/patient/doctors" required>
                            </div>
                            <small class="text-muted">
                                Relative path only, e.g. <code>/patient/Main-page</code>,
                                <code>/appointments</code>, <code>/hospitals</code>
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Font Awesome Icon</label>
                            <input type="text" name="icon" id="link-icon"
                                   class="form-control" placeholder="fas fa-link">
                            <small class="text-muted">
                                e.g. <code>fas fa-user-md</code>, <code>fas fa-hospital</code>
                            </small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" id="link-sort"
                                   class="form-control" value="0" min="0">
                        </div>

                        <div class="col-md-3 d-flex align-items-end pb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       name="is_active" id="link-active" value="1" checked>
                                <label class="form-check-label" for="link-active">Active</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Applicable Roles</label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach(['patient','doctor','hospital','laboratory','pharmacy','medicalcentre'] as $role)
                                    <div class="form-check">
                                        <input class="form-check-input role-check" type="checkbox"
                                               name="roles[]" value="{{ $role }}" id="role-{{ $role }}">
                                        <label class="form-check-label" for="role-{{ $role }}">
                                            {{ ucfirst($role) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mt-1">
                                Leave empty for “All roles”.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Link
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';

function editLink(link) {
    document.getElementById('link-modal-title').innerHTML =
        '<i class="fas fa-edit me-2 text-primary"></i>Edit Quick Link';

    document.getElementById('link-form').action  = `/admin/chatbot/links/${link.id}`;
    document.getElementById('link-method').value = 'PUT';

    document.getElementById('link-label').value  = link.label;
    document.getElementById('link-route').value  = link.url_path;
    document.getElementById('link-icon').value   = link.icon ?? 'fas fa-link';
    document.getElementById('link-sort').value   = link.sort_order;
    document.getElementById('link-active').checked = link.is_active == 1;

    // reset roles
    document.querySelectorAll('.role-check').forEach(c => c.checked = false);
    const roles = typeof link.roles === 'string' ? JSON.parse(link.roles || '[]') : (link.roles ?? []);
    roles.forEach(r => {
        const el = document.getElementById('role-' + r);
        if (el) el.checked = true;
    });

    new bootstrap.Modal(document.getElementById('linkModal')).show();
}

document.getElementById('linkModal').addEventListener('hidden.bs.modal', () => {
    document.getElementById('link-modal-title').innerHTML =
        '<i class="fas fa-link me-2 text-primary"></i>Add Quick Link';
    document.getElementById('link-form').action  = '{{ route('admin.chatbot.links.store') }}';
    document.getElementById('link-method').value = 'POST';
    document.getElementById('link-form').reset();
    document.getElementById('link-active').checked = true;
    document.querySelectorAll('.role-check').forEach(c => c.checked = false);
});

async function toggleLink(id, el) {
    try {
        const res  = await fetch(`/admin/chatbot/links/${id}/toggle`, {
            method : 'POST',
            headers: {'X-CSRF-TOKEN': CSRF, 'Accept':'application/json'}
        });
        const data = await res.json();
        if (!data.ok) el.checked = !el.checked;
    } catch (e) {
        el.checked = !el.checked;
    }
}

function deleteLink(id) {
    if (!confirm('Delete this quick link?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/chatbot/links/${id}`;
    form.innerHTML = `<input name="_token" value="${CSRF}">
                      <input name="_method" value="DELETE">`;
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
