@extends('laboratory.layouts.app')
@section('title','Create Package')
@section('page-title','Create Lab Package')
@section('page-subtitle','Bundle multiple tests into a discounted package')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('laboratory.packages.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Package Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Package Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="package_name" value="{{ old('package_name') }}" required
                        placeholder="e.g. Full Body Health Check, Diabetes Panel..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none @error('package_name') border-red-400 @enderror">
                    @error('package_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Price --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Package Price (Rs.) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" id="price"
                        value="{{ old('price') }}"
                        step="0.01" min="0" required placeholder="0.00"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none @error('price') border-red-400 @enderror"
                        oninput="calcFinal()">
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Discount --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Discount (%)</label>
                    <input type="number" name="discount_percentage" id="discount"
                        value="{{ old('discount_percentage', 0) }}"
                        step="0.01" min="0" max="100" placeholder="0"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
                        oninput="calcFinal()">
                    <p class="text-xs text-gray-400 mt-1">
                        Final Price:
                        <span id="finalPrice" class="font-semibold text-teal-600">Rs. 0.00</span>
                    </p>
                </div>

                {{-- Active Status --}}
                <div class="flex items-center gap-3 pt-2">
                    <label class="text-sm font-semibold text-gray-700">Active Status</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-teal-500
                                    after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                    after:bg-white after:rounded-full after:h-5 after:w-5
                                    after:transition-all peer-checked:after:translate-x-5"></div>
                        <span class="ml-2 text-sm text-gray-600">Active</span>
                    </label>
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                        placeholder="Brief description about this package and what it covers..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none resize-none">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Test Selection --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Include Tests
                    <span class="ml-2 text-xs font-normal text-gray-400">(select one or more tests)</span>
                </label>

                @if($tests->isEmpty())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 text-sm text-yellow-700 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        No active tests found.
                        <a href="{{ route('laboratory.tests.create') }}" class="underline font-semibold ml-1">Add tests first →</a>
                    </div>
                @else
                    {{-- Search --}}
                    <input type="text" id="testSearch" placeholder="Search tests by name or category..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none mb-3"
                        oninput="filterTests()">

                    {{-- Select All + Count --}}
                    <div class="flex items-center gap-2 mb-2 px-1">
                        <input type="checkbox" id="selectAll" class="w-4 h-4 accent-teal-600"
                            onchange="toggleAll(this)">
                        <label for="selectAll" class="text-xs text-gray-500 cursor-pointer select-none">
                            Select All
                        </label>
                        <span id="selectedCount" class="ml-auto text-xs text-teal-600 font-semibold"></span>
                    </div>

                    {{-- Test List --}}
                    <div id="testList"
                         class="border border-gray-200 rounded-xl overflow-hidden divide-y divide-gray-100 max-h-72 overflow-y-auto">
                        @foreach($tests as $test)
                        <label class="test-item flex items-center gap-3 px-4 py-3 hover:bg-teal-50 cursor-pointer transition"
                               data-name="{{ strtolower($test->test_name) }}"
                               data-cat="{{ strtolower($test->test_category ?? '') }}">
                            <input type="checkbox"
                                name="tests[]"
                                value="{{ $test->id }}"
                                class="test-checkbox w-4 h-4 accent-teal-600 flex-shrink-0"
                                {{ in_array($test->id, old('tests', [])) ? 'checked' : '' }}
                                onchange="updateCount()">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $test->test_name }}</p>
                                <p class="text-xs text-gray-400">{{ $test->test_category ?? 'General' }}</p>
                            </div>
                            <span class="text-sm font-semibold text-teal-700 flex-shrink-0">
                                Rs. {{ number_format($test->price, 2) }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    @error('tests')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                @endif
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-teal-600 text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:bg-teal-700 transition shadow-sm">
                    <i class="fas fa-box-open mr-2"></i> Create Package
                </button>
                <a href="{{ route('laboratory.packages.index') }}"
                    class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function calcFinal() {
    const price    = parseFloat(document.getElementById('price').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const final    = price - (price * discount / 100);
    document.getElementById('finalPrice').textContent = 'Rs. ' + final.toFixed(2);
}

function filterTests() {
    const q = document.getElementById('testSearch').value.toLowerCase();
    document.querySelectorAll('.test-item').forEach(item => {
        const match = item.dataset.name.includes(q) || item.dataset.cat.includes(q);
        item.style.display = match ? '' : 'none';
    });
}

function toggleAll(master) {
    document.querySelectorAll('.test-checkbox').forEach(cb => {
        if (cb.closest('.test-item').style.display !== 'none') {
            cb.checked = master.checked;
        }
    });
    updateCount();
}

function updateCount() {
    const count = document.querySelectorAll('.test-checkbox:checked').length;
    const el = document.getElementById('selectedCount');
    el.textContent = count > 0 ? count + ' test' + (count > 1 ? 's' : '') + ' selected' : '';
}

calcFinal();
updateCount();
</script>
@endpush
