@extends('laboratory.layouts.app')
@section('title','Packages')
@section('page-title','Test Packages')
@section('page-subtitle','Manage bundled test packages')

@section('content')
<div class="flex justify-between items-center mb-5">
    <p class="text-sm text-gray-500">{{ $packages->total() }} packages total</p>
    <a href="{{ route('laboratory.packages.create') }}"
       class="bg-teal-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-teal-700 transition flex items-center gap-2 shadow-sm">
        <i class="fas fa-plus"></i> Create Package
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($packages as $pkg)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-5 py-4">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-white font-bold text-base">{{ $pkg->package_name }}</h3>
                    <p class="text-teal-200 text-xs mt-0.5">{{ $pkg->tests->count() }} tests included</p>
                </div>
                <button onclick="togglePackage({{ $pkg->id }}, this)"
                    class="text-white/70 hover:text-white transition text-sm">
                    <span class="{{ $pkg->is_active ? 'bg-green-400' : 'bg-gray-400' }} text-white text-xs px-2 py-1 rounded-full font-medium">
                        {{ $pkg->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </button>
            </div>
        </div>
        <div class="p-5">
            @if($pkg->description)
            <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $pkg->description }}</p>
            @endif

            {{-- Price --}}
            <div class="flex items-baseline gap-2 mb-4">
                @if($pkg->discount_percentage > 0)
                <span class="text-2xl font-bold text-gray-900">Rs. {{ number_format($pkg->discounted_price, 0) }}</span>
                <span class="text-sm text-gray-400 line-through">Rs. {{ number_format($pkg->price, 0) }}</span>
                <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-semibold">{{ $pkg->discount_percentage }}% OFF</span>
                @else
                <span class="text-2xl font-bold text-gray-900">Rs. {{ number_format($pkg->price, 0) }}</span>
                @endif
            </div>

            {{-- Included Tests --}}
            @if($pkg->tests->count())
            <div class="mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Included Tests</p>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($pkg->tests->take(4) as $test)
                    <span class="text-xs bg-teal-50 text-teal-700 px-2.5 py-1 rounded-full">{{ $test->test_name }}</span>
                    @endforeach
                    @if($pkg->tests->count() > 4)
                    <span class="text-xs bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full">+{{ $pkg->tests->count() - 4 }} more</span>
                    @endif
                </div>
            </div>
            @endif

            {{-- Actions --}}
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <a href="{{ route('laboratory.packages.edit', $pkg) }}"
                   class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition text-center">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form method="POST" action="{{ route('laboratory.packages.destroy', $pkg) }}"
                      onsubmit="return confirm('Delete this package?')" class="flex-1">
                    @csrf @method('DELETE')
                    <button class="w-full bg-red-50 text-red-600 py-2 rounded-lg text-sm font-medium hover:bg-red-100 transition">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-gray-100">
        <i class="fas fa-box-open text-gray-200 text-5xl mb-3 block"></i>
        <p class="text-gray-400 font-medium">No packages yet</p>
        <a href="{{ route('laboratory.packages.create') }}" class="mt-3 inline-block text-teal-600 text-sm hover:underline">Create your first package →</a>
    </div>
    @endforelse
</div>

@if($packages->hasPages())
<div class="mt-5">{{ $packages->links() }}</div>
@endif
@endsection

@push('scripts')
<script>
function togglePackage(id, btn) {
    fetch(`/laboratory/packages/${id}/toggle`, {
        method:'POST',
        headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}
    })
    .then(r => r.json())
    .then(data => {
        const span = btn.querySelector('span');
        span.className = `${data.is_active ? 'bg-green-400' : 'bg-gray-400'} text-white text-xs px-2 py-1 rounded-full font-medium`;
        span.textContent = data.is_active ? 'Active' : 'Inactive';
    });
}
</script>
@endpush
