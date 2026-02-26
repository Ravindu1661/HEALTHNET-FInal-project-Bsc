@extends('laboratory.layouts.app')
@section('title','Add Test')
@section('page-title','Add New Test')
@section('page-subtitle','Create a new laboratory test')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('laboratory.tests.store') }}" class="space-y-5">
            @csrf

            @php
                /**
                 * ✅ Common categories (expandable)
                 * You can also pass $categories from controller; we will merge both safely.
                 */
                $defaultCategories = [
                    'Hematology',
                    'Biochemistry',
                    'Microbiology',
                    'Immunology / Serology',
                    'Hormones / Endocrinology',
                    'Urine & Stool',
                    'Pathology / Histopathology',
                    'Radiology / Imaging',
                    'Cardiology',
                    'Infectious Diseases',
                    'Tumor Markers',
                    'Allergy',
                    'Genetic / Molecular',
                    'Prenatal / Fertility',
                    'Other',
                ];

                // merge controller categories if available (avoid duplicates)
                $controllerCategories = isset($categories) && is_array($categories) ? $categories : [];
                $allCategories = array_values(array_unique(array_merge($controllerCategories, $defaultCategories)));

                /**
                 * ✅ Test name list (grouped by category)
                 * - Includes your provided test names
                 * - Adds few common tests
                 * - Supports "Other / Custom" (text input will appear)
                 */
                $testsByCategory = [
                    'Hematology' => [
                        'Complete Blood Count (CBC)',
                        'Erythrocyte Sedimentation Rate (ESR)',
                        'Blood Film / Peripheral Smear',
                        'Platelet Count',
                        'Hemoglobin (Hb)',
                        'Blood Grouping & Rh Factor',
                        'Coagulation Profile (PT/INR, APTT)',
                    ],
                    'Biochemistry' => [
                        'Blood Sugar (FBS / RBS / PPBS)',
                        'HbA1c',
                        'Lipid Profile',
                        'Liver Function Tests (LFT)',
                        'Kidney Function Tests (KFT)',
                        'Electrolytes (Na, K, Cl)',
                        'Uric Acid',
                        'CRP (C-Reactive Protein)',
                    ],
                    'Urine & Stool' => [
                        'Urine Full Report (UFR) / Urine Analysis',
                        'Urine Culture',
                        'Stool Full Report (SFR)',
                        'Stool Culture',
                    ],
                    'Cardiology' => [
                        'ECG',
                        'Echo Cardiogram (2D Echo)',
                    ],
                    'Radiology / Imaging' => [
                        'X-Ray',
                        'Ultrasound Scan',
                        'CT Scan',
                        'MRI Scan',
                    ],
                    'Immunology / Serology' => [
                        'Allergy Tests',
                        'COVID-19 PCR Test',
                        'Dengue NS1 / IgM / IgG',
                        'HIV 1&2 (Screening)',
                        'VDRL / RPR (Syphilis)',
                    ],
                    'Hormones / Endocrinology' => [
                        'Thyroid Function Tests (TFT)',
                        'Hormone Tests',
                        'Vitamin D',
                        'Vitamin B12',
                    ],
                    'Tumor Markers' => [
                        'Cancer Markers / Tumor Markers',
                        'PSA',
                        'CA-125',
                        'CEA',
                    ],
                    'Microbiology' => [
                        'Microbiology Tests',
                        'Sputum AFB',
                        'Blood Culture',
                        'Throat Swab Culture',
                    ],
                    'Pathology / Histopathology' => [
                        'Pathology Services',
                        'Biopsy',
                        'Pap Smear',
                        'FNAC',
                    ],
                    'Prenatal / Fertility' => [
                        'Pregnancy Test (Urine / Serum β-hCG)',
                    ],
                    'Other' => [
                        'Other / Custom Test',
                    ],
                ];

                // flatten for the dropdown list with "Category - Test"
                $testOptions = [];
                foreach ($testsByCategory as $cat => $tests) {
                    foreach ($tests as $t) {
                        $testOptions[] = [
                            'category' => $cat,
                            'label' => $t,
                            'value' => $t,
                        ];
                    }
                }

                $oldTestName = old('test_name');
                $oldCategory  = old('test_category');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- ✅ Test Name as dropdown (with custom option) --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Test Name <span class="text-red-500">*</span>
                    </label>

                    <select id="test_name_select" name="test_name" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none @error('test_name') border-red-400 @enderror">
                        <option value="" {{ $oldTestName ? '' : 'selected' }}>Select test</option>

                        @foreach($testsByCategory as $cat => $tests)
                            <optgroup label="{{ $cat }}">
                                @foreach($tests as $t)
                                    <option value="{{ $t }}" {{ $oldTestName === $t ? 'selected' : '' }}>
                                        {{ $t }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('test_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                    {{-- ✅ Custom Test Name input (only shows when Other/Custom selected) --}}
                    <div id="custom_test_wrap" class="mt-3 hidden">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Custom Test Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="custom_test_input"
                            placeholder="Type your custom test name..."
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                        <p class="text-xs text-gray-500 mt-1">
                            Select “Other / Custom Test” above to type your own test name.
                        </p>
                    </div>
                </div>

                {{-- ✅ Category dropdown (common categories added) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category</label>
                    <select name="test_category" id="test_category"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                        <option value="" {{ $oldCategory ? '' : 'selected' }}>Select category</option>

                        @foreach($allCategories as $cat)
                            <option value="{{ $cat }}" {{ $oldCategory === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Price --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Price (Rs.) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required
                        placeholder="0.00"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none @error('price') border-red-400 @enderror">
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Duration --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Duration (Hours)</label>
                    <input type="number" name="duration_hours" value="{{ old('duration_hours') }}" min="0"
                        placeholder="e.g. 24"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                </div>

                {{-- Active Status --}}
                <div class="flex items-center gap-3 pt-2">
                    <label class="text-sm font-semibold text-gray-700">Active Status</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-teal-500 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                        <span class="ml-2 text-sm text-gray-600">Active</span>
                    </label>
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                        placeholder="Brief description about this test..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none resize-none">{{ old('description') }}</textarea>
                </div>

                {{-- Requirements --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Patient Requirements</label>
                    <textarea name="requirements" rows="2"
                        placeholder="e.g. Fasting required 8 hours before test..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none resize-none">{{ old('requirements') }}</textarea>
                </div>

            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-teal-600 text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:bg-teal-700 transition shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Add Test
                </button>
                <a href="{{ route('laboratory.tests.index') }}"
                    class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ✅ JS: Auto-set category by selected test, and allow custom test name --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const testSelect = document.getElementById('test_name_select');
    const categorySelect = document.getElementById('test_category');
    const customWrap = document.getElementById('custom_test_wrap');
    const customInput = document.getElementById('custom_test_input');

    // Map test -> category (generated from blade)
    const testCategoryMap = @json(
        collect($testOptions)->mapWithKeys(fn($x) => [$x['value'] => $x['category']])->toArray()
    );

    function isCustomSelected() {
        return testSelect.value === 'Other / Custom Test';
    }

    function toggleCustomField() {
        if (isCustomSelected()) {
            customWrap.classList.remove('hidden');
            customInput.required = true;
        } else {
            customWrap.classList.add('hidden');
            customInput.required = false;
            customInput.value = '';
        }
    }

    function syncCategory() {
        const selected = testSelect.value;
        if (!selected) return;

        const cat = testCategoryMap[selected];
        if (cat && categorySelect) {
            categorySelect.value = cat;
        }

        // if selected "Other / Custom Test", category should be "Other"
        if (selected === 'Other / Custom Test') {
            categorySelect.value = 'Other';
        }
    }

    // When custom test is typed, replace the select's value on submit
    const form = testSelect.closest('form');
    form.addEventListener('submit', function () {
        if (isCustomSelected()) {
            const v = (customInput.value || '').trim();
            if (v.length > 0) {
                // overwrite dropdown value with custom name
                // (server will receive test_name as custom text)
                const opt = document.createElement('option');
                opt.value = v;
                opt.text = v;
                opt.selected = true;
                testSelect.appendChild(opt);
            }
        }
    });

    testSelect.addEventListener('change', function () {
        toggleCustomField();
        syncCategory();
    });

    // initial run (in case old values are present)
    toggleCustomField();
    syncCategory();

    // If old('test_name') is custom (not in dropdown), show it nicely
    // (Only runs if Blade old value exists but isn't in options)
    const current = testSelect.value;
    if (current && !testCategoryMap[current] && current !== 'Other / Custom Test') {
        // add old custom value as selected option
        const opt = document.createElement('option');
        opt.value = current;
        opt.text = current + ' (Custom)';
        opt.selected = true;
        testSelect.appendChild(opt);
        categorySelect.value = categorySelect.value || 'Other';
    }
});
</script>
@endsection
