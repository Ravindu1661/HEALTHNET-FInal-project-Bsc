@extends('laboratory.layouts.app')
@section('title','Add Test')
@section('page-title','Add New Test')
@section('page-subtitle','Create a new laboratory test')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('laboratory.tests.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Test Name <span class="text-red-500">*</span></label>
                    <input type="text" name="test_name" value="{{ old('test_name') }}" required
                        placeholder="e.g. Complete Blood Count (CBC)"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none @error('test_name') border-red-400 @enderror">
                    @error('test_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category</label>
    <select name="test_category"
        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
        <option value="" {{ old('test_category') ? '' : 'selected' }}>Select category</option>

        @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ old('test_category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
        @endforeach

        <option value="Hematology" {{ old('test_category') == 'Hematology' ? 'selected' : '' }}>Hematology</option>
        <option value="Biochemistry" {{ old('test_category') == 'Biochemistry' ? 'selected' : '' }}>Biochemistry</option>
        <option value="Microbiology" {{ old('test_category') == 'Microbiology' ? 'selected' : '' }}>Microbiology</option>
        <option value="Immunology" {{ old('test_category') == 'Immunology' ? 'selected' : '' }}>Immunology</option>
        <option value="Hormones" {{ old('test_category') == 'Hormones' ? 'selected' : '' }}>Hormones</option>
        <option value="Urine Analysis" {{ old('test_category') == 'Urine Analysis' ? 'selected' : '' }}>Urine Analysis</option>
        <option value="Radiology" {{ old('test_category') == 'Radiology' ? 'selected' : '' }}>Radiology</option>
        <option value="Other" {{ old('test_category') == 'Other' ? 'selected' : '' }}>Other</option>
    </select>
</div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Price (Rs.) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required
                        placeholder="0.00"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none @error('price') border-red-400 @enderror">
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Duration (Hours)</label>
                    <input type="number" name="duration_hours" value="{{ old('duration_hours') }}" min="0"
                        placeholder="e.g. 24"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <label class="text-sm font-semibold text-gray-700">Active Status</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-teal-500 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                        <span class="ml-2 text-sm text-gray-600">Active</span>
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                        placeholder="Brief description about this test..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none resize-none">{{ old('description') }}</textarea>
                </div>

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
@endsection
