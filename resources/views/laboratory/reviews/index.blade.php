@extends('laboratory.layouts.app')
@section('title','Reviews')
@section('page-title','Reviews & Ratings')
@section('page-subtitle','Monitor patient feedback')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">

    {{-- Rating Summary --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
        <p class="text-5xl font-bold text-gray-900 mb-1">{{ number_format($summary['avg'], 1) }}</p>
        <div class="flex justify-center gap-1 text-yellow-400 mb-2">
            @for($i=1;$i<=5;$i++)
            <i class="fas fa-star text-lg {{ $i <= round($summary['avg']) ? '' : 'text-gray-200' }}"></i>
            @endfor
        </div>
        <p class="text-gray-400 text-sm">{{ $summary['total'] }} total reviews</p>

        <div class="mt-5 space-y-2">
            @for($star=5; $star>=1; $star--)
            @php $count = $summary['dist'][$star] ?? 0; $pct = $summary['total'] ? round($count/$summary['total']*100) : 0; @endphp
            <div class="flex items-center gap-2 text-sm">
                <span class="text-gray-500 w-3">{{ $star }}</span>
                <i class="fas fa-star text-yellow-400 text-xs"></i>
                <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                    <div class="bg-yellow-400 h-1.5 rounded-full" style="width:{{ $pct }}%"></div>
                </div>
                <span class="text-gray-400 text-xs w-6">{{ $count }}</span>
            </div>
            @endfor
        </div>
    </div>

    {{-- Reviews List --}}
    <div class="lg:col-span-3 space-y-4">
        @forelse($ratings as $rating)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="bg-teal-100 w-10 h-10 rounded-full flex items-center justify-center font-bold text-teal-700">
                        {{ strtoupper(substr($rating->patient->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $rating->patient->user->name ?? 'Patient' }}</p>
                        <p class="text-xs text-gray-400">{{ $rating->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="flex gap-0.5 text-yellow-400">
                    @for($i=1;$i<=5;$i++)<i class="fas fa-star text-sm {{ $i<=$rating->rating ? '' : 'text-gray-200' }}"></i>@endfor
                </div>
            </div>
            @if($rating->review)
            <p class="text-gray-700 text-sm leading-relaxed">{{ $rating->review }}</p>
            @endif
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-16 text-center">
            <i class="fas fa-star text-gray-200 text-5xl mb-3 block"></i>
            <p class="text-gray-400 font-medium">No reviews yet</p>
            <p class="text-gray-300 text-sm">Reviews from patients will appear here</p>
        </div>
        @endforelse
        @if($ratings->hasPages())
        <div>{{ $ratings->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
