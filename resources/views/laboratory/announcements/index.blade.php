@extends('laboratory.layouts.app')
@section('title','Announcements')
@section('page-title','Announcements & Offers')
@section('page-subtitle','Manage public announcements')

@section('content')
<div class="flex justify-between items-center mb-5">
    <div class="flex gap-2">
        <a href="{{ route('laboratory.announcements.index') }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('filter') ? 'bg-teal-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">All</a>
        <a href="{{ route('laboratory.announcements.index', ['filter'=>'active']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('filter')=='active' ? 'bg-teal-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">Active</a>
        <a href="{{ route('laboratory.announcements.index', ['filter'=>'expired']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('filter')=='expired' ? 'bg-teal-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">Expired</a>
    </div>
    <a href="{{ route('laboratory.announcements.create') }}"
       class="bg-teal-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-teal-700 transition flex items-center gap-2 shadow-sm">
        <i class="fas fa-plus"></i> New Announcement
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
@forelse($announcements as $ann)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">

    @if($ann->image_path)
        <img src="{{ asset('storage/' . $ann->image_path) }}" class="w-full h-36 object-cover">
    @else
        <div class="h-16 bg-gradient-to-r from-teal-500 to-teal-600 flex items-center px-5">
            <i class="fas fa-bullhorn text-white text-2xl"></i>
        </div>
    @endif

    <div class="p-5">
        <div class="flex items-start justify-between mb-2">
            <span class="text-xs bg-teal-50 text-teal-700 px-2.5 py-1 rounded-full font-medium capitalize">
                {{ str_replace('_', ' ', $ann->announcement_type ?? '') }}  {{-- ✅ ?? '' added --}}
            </span>
            <button onclick="toggleAnnouncement({{ $ann->id }}, this)"
                class="{{ ($ann->is_active ?? false) ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }} text-xs px-2.5 py-1 rounded-full font-medium hover:opacity-80 transition">
                {{ ($ann->is_active ?? false) ? 'Active' : 'Inactive' }}  {{-- ✅ ?? false --}}
            </button>
        </div>

        <h3 class="font-bold text-gray-900 mb-1">{{ $ann->title ?? '' }}</h3>
        <p class="text-gray-500 text-sm line-clamp-2 mb-3">{{ $ann->content ?? '' }}</p>  {{-- ✅ content fix --}}

        @if(($ann->start_date ?? null) || ($ann->end_date ?? null))
        <p class="text-xs text-gray-400 mb-3">
            <i class="fas fa-calendar-alt mr-1"></i>
            {{ optional($ann->start_date)->format('d M Y') ?? '—' }}
            →
            {{ optional($ann->end_date)->format('d M Y') ?? 'Ongoing' }}  {{-- ✅ optional() use --}}
        </p>
        @endif

        <div class="flex gap-2 pt-3 border-t border-gray-100">
            <a href="{{ route('laboratory.announcements.edit', $ann) }}"
               class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-lg text-xs font-medium hover:bg-gray-200 transition text-center">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <form method="POST" action="{{ route('laboratory.announcements.destroy', $ann) }}"
                  onsubmit="return confirm('Delete?')" class="flex-1">
                @csrf @method('DELETE')
                <button class="w-full bg-red-50 text-red-600 py-2 rounded-lg text-xs font-medium hover:bg-red-100 transition">
                    <i class="fas fa-trash mr-1"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>
@empty
    <div class="col-span-3 bg-white rounded-2xl border border-gray-100 py-16 text-center">
        <i class="fas fa-bullhorn text-gray-200 text-5xl mb-3 block"></i>
        <p class="text-gray-400 font-medium">No announcements yet</p>
        <a href="{{ route('laboratory.announcements.create') }}" class="mt-3 inline-block text-teal-600 text-sm hover:underline">Create your first announcement →</a>
    </div>
    @endforelse
</div>
@if($announcements->hasPages())<div class="mt-5">{{ $announcements->links() }}</div>@endif
@endsection

@push('scripts')
<script>
function toggleAnnouncement(id, btn) {
    fetch(`/laboratory/announcements/${id}/toggle`, {
        method:'POST',
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}
    }).then(r=>r.json()).then(data=>{
        btn.className = `${data.is_active?'bg-green-100 text-green-700':'bg-gray-100 text-gray-500'} text-xs px-2.5 py-1 rounded-full font-medium hover:opacity-80 transition`;
        btn.textContent = data.is_active ? 'Active' : 'Inactive';
    });
}
</script>
@endpush
