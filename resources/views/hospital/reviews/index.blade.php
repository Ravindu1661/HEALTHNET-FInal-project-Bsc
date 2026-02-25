@extends('hospital.layouts.app')
@section('title','Reviews')
@section('page-title','Reviews & Ratings')
@section('page-subtitle','Patient feedback about your hospital')

@section('content')
<div x-data="hospitalReviews()" x-init="init()">

    <!-- Rating Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <!-- Overall Rating -->
        <div class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-xl p-6 text-white text-center shadow-sm">
            <p class="text-5xl font-bold mb-2" x-text="summary.avg || '0.0'"></p>
            <div class="flex items-center justify-center gap-1 mb-2">
                <template x-for="i in 5">
                    <i :class="i <= Math.round(summary.avg || 0) ? 'text-yellow-300' : 'text-teal-400'"
                       class="fas fa-star text-lg"></i>
                </template>
            </div>
            <p class="text-teal-100 text-sm">Overall Rating</p>
            <p class="text-teal-200 text-xs mt-1" x-text="(summary.total || 0) + ' reviews'"></p>
        </div>

        <!-- Rating Breakdown -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Rating Breakdown</h3>
            <div class="space-y-3">
                <template x-for="n in [5,4,3,2,1]">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 w-16 flex-shrink-0">
                            <span class="text-sm text-gray-600" x-text="n"></span>
                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                        </div>
                        <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full bg-yellow-400 rounded-full transition-all duration-500"
                                 :style="'width:' + getPercent(n) + '%'"></div>
                        </div>
                        <span class="text-xs text-gray-500 w-8 text-right"
                              x-text="breakdown[n] || 0"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" x-model="search" @input.debounce.500ms="load()"
                       placeholder="Search by patient name..."
                       class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <select x-model="ratingFilter" @change="load()"
                    class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">All Ratings</option>
                <option value="5">⭐⭐⭐⭐⭐ 5 Stars</option>
                <option value="4">⭐⭐⭐⭐ 4 Stars</option>
                <option value="3">⭐⭐⭐ 3 Stars</option>
                <option value="2">⭐⭐ 2 Stars</option>
                <option value="1">⭐ 1 Star</option>
            </select>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="space-y-4">
        <template x-for="review in reviews" :key="review.id">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4 flex-1">
                        <!-- Avatar -->
                        <div class="w-11 h-11 rounded-full bg-teal-100 flex items-center justify-center font-bold text-teal-700 flex-shrink-0"
                             x-text="review.patient_name?.charAt(0) || 'P'"></div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1 flex-wrap">
                                <p class="font-semibold text-gray-800 text-sm" x-text="review.patient_name"></p>
                                <span class="text-xs text-gray-400" x-text="review.date"></span>
                            </div>
                            <!-- Stars -->
                            <div class="flex items-center gap-0.5 mb-2">
                                <template x-for="i in 5">
                                    <i :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-200'"
                                       class="fas fa-star text-sm"></i>
                                </template>
                                <span class="text-xs text-gray-500 ml-2" x-text="review.rating + '/5'"></span>
                            </div>
                            <!-- Review Title -->
                            <p class="font-medium text-gray-700 text-sm mb-1" x-show="review.review_title"
                               x-text="review.review_title"></p>
                            <!-- Review Text -->
                            <p class="text-sm text-gray-600 leading-relaxed" x-text="review.review_text || 'No comment provided.'"></p>
                        </div>
                    </div>
                    <!-- Rating Badge -->
                    <div class="flex-shrink-0">
                        <span :class="{
                            'bg-green-100 text-green-700': review.rating >= 4,
                            'bg-yellow-100 text-yellow-700': review.rating === 3,
                            'bg-red-100 text-red-700': review.rating <= 2
                        }" class="text-xs px-2.5 py-1 rounded-full font-bold"
                        x-text="review.rating + '★'"></span>
                    </div>
                </div>
            </div>
        </template>

        <!-- Empty -->
        <div x-show="reviews.length === 0 && !loading"
             class="bg-white rounded-xl shadow-sm border border-gray-100 py-16 text-center">
            <i class="fas fa-star text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-400">No reviews yet</p>
            <p class="text-sm text-gray-300 mt-1">Patient reviews will appear here</p>
        </div>

        <!-- Loading -->
        <div x-show="loading" class="py-16 text-center text-gray-400">
            <i class="fas fa-spinner fa-spin text-3xl"></i>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-6 bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4"
         x-show="pg.last_page > 1">
        <p class="text-sm text-gray-500">
            Showing <span x-text="pg.from"></span>–<span x-text="pg.to"></span>
            of <span x-text="pg.total"></span>
        </p>
        <div class="flex gap-2">
            <button @click="changePage(pg.current_page - 1)" :disabled="pg.current_page === 1"
                    class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40 transition">
                <i class="fas fa-chevron-left"></i>
            </button>
            <span class="px-3 py-1.5 text-sm text-gray-600"
                  x-text="pg.current_page + ' / ' + pg.last_page"></span>
            <button @click="changePage(pg.current_page + 1)" :disabled="pg.current_page === pg.last_page"
                    class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40 transition">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function hospitalReviews() {
    return {
        reviews: [],
        summary: { avg: 0, total: 0 },
        breakdown: { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 },
        loading: true,
        search: '',
        ratingFilter: '',
        pg: { current_page: 1, last_page: 1, from: 0, to: 0, total: 0 },

        async init() { await this.load(); },

        async load(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({ search: this.search, rating: this.ratingFilter, page });
                const res  = await fetch(`{{ route('hospital.reviews.data') }}?${params}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.reviews = data.reviews.data;
                    const m     = data.reviews;
                    this.pg     = { current_page: m.current_page, last_page: m.last_page, from: m.from || 0, to: m.to || 0, total: m.total };

                    // Calculate summary from all reviews
                    if (data.summary) {
                        this.summary  = data.summary;
                        this.breakdown = data.breakdown || {};
                    } else {
                        const total = this.reviews.length;
                        const avg   = total ? (this.reviews.reduce((s, r) => s + r.rating, 0) / total).toFixed(1) : 0;
                        this.summary  = { avg, total };
                        this.breakdown = this.reviews.reduce((acc, r) => { acc[r.rating] = (acc[r.rating] || 0) + 1; return acc; }, {});
                    }
                }
            } catch(e) { console.error(e); }
            this.loading = false;
        },

        getPercent(n) {
            const total = Object.values(this.breakdown).reduce((s, v) => s + v, 0);
            return total ? Math.round(((this.breakdown[n] || 0) / total) * 100) : 0;
        },

        changePage(p) {
            if (p < 1 || p > this.pg.last_page) return;
            this.load(p);
        }
    }
}
</script>
@endpush
