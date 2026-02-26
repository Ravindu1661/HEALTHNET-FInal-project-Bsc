@extends('laboratory.layouts.app')
@section('title','Edit Test')
@section('page-title','Edit Test')
@section('page-subtitle','Update laboratory test details')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('laboratory.tests.update', $test) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Test Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Test Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="test_name"
                        value="{{ old('test_name', $test->test_name) }}" required
                        placeholder="e.g. Complete Blood Count (CBC)"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none @error('test_name') border-red-400 @enderror">
                    @error('test_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category</label>
                    <select name="test_category"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none bg-white text-gray-700 cursor-pointer">
                        <option value="">— Select Category —</option>

                        {{-- Existing categories from this lab --}}
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}"
                                {{ old('test_category', $test->test_category) == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach

                        <optgroup label="🩸 Haematology">
                            @php $haemItems = [
                                'Haematology'         => 'Haematology (General)',
                                'Full Blood Count'    => 'Full Blood Count (FBC)',
                                'Blood Grouping'      => 'Blood Grouping & Cross Match',
                                'Coagulation'         => 'Coagulation / Clotting Studies',
                                'ESR'                 => 'ESR / Sedimentation Rate',
                                'Peripheral Blood Film'=> 'Peripheral Blood Film',
                                'Haemoglobinopathy'   => 'Haemoglobinopathy (HbA1c, Hb Electrophoresis)',
                            ]; @endphp
                            @foreach($haemItems as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('test_category', $test->test_category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="🧪 Clinical Biochemistry">
                            @php $bioItems = [
                                'Clinical Biochemistry' => 'Clinical Biochemistry (General)',
                                'Liver Function Tests'  => 'Liver Function Tests (LFT)',
                                'Renal Function Tests'  => 'Renal Function Tests (RFT)',
                                'Lipid Profile'         => 'Lipid Profile / Cholesterol',
                                'Blood Glucose'         => 'Blood Glucose / Diabetes',
                                'Electrolytes'          => 'Electrolytes (Na, K, Cl, Bicarbonate)',
                                'Cardiac Markers'       => 'Cardiac Markers (Troponin, CK-MB, BNP)',
                                'Thyroid Function'      => 'Thyroid Function (TSH, T3, T4)',
                                'Bone Profile'          => 'Bone Profile (Calcium, Phosphate, ALP)',
                                'Iron Studies'          => 'Iron Studies (Serum Iron, Ferritin, TIBC)',
                                'Vitamins & Minerals'   => 'Vitamins & Minerals (B12, D3, Folate, Zinc)',
                                'Tumour Markers'        => 'Tumour Markers (PSA, CEA, CA-125, AFP)',
                                'Arterial Blood Gas'    => 'Arterial Blood Gas (ABG)',
                                'Drug Monitoring'       => 'Therapeutic Drug Monitoring',
                            ]; @endphp
                            @foreach($bioItems as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('test_category', $test->test_category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="🦠 Microbiology">
                            @php $microItems = [
                                'Microbiology'        => 'Microbiology (General)',
                                'Culture & Sensitivity'=> 'Culture & Sensitivity (C&S)',
                                'Bacteriology'        => 'Bacteriology',
                                'Parasitology'        => 'Parasitology (Malaria, Stool O&P)',
                                'Mycology'            => 'Mycology (Fungal Tests)',
                                'AFB / TB Studies'    => 'AFB / Tuberculosis Studies',
                            ]; @endphp
                            @foreach($microItems as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('test_category', $test->test_category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="🧬 Serology & Immunology">
                            @php $seroItems = [
                                'Serology'       => 'Serology (General)',
                                'Viral Markers'  => 'Viral Markers (Hepatitis B, C, HIV)',
                                'Dengue Tests'   => 'Dengue Tests (NS1, IgG/IgM)',
                                'Autoimmune'     => 'Autoimmune (ANA, RF, Anti-dsDNA)',
                                'Allergy Testing'=> 'Allergy Testing (IgE)',
                                'Widal / Typhoid'=> 'Widal / Typhoid Tests',
                                'COVID-19 Tests' => 'COVID-19 Tests (PCR, Antigen, Antibody)',
                                'Leptospira'     => 'Leptospira / MAT',
                                'VDRL / Syphilis'=> 'VDRL / Syphilis Tests',
                            ]; @endphp
                            @foreach($seroItems as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('test_category', $test->test_category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="⚗️ Hormones & Endocrinology">
                            @php $hormItems = [
                                'Hormones'             => 'Hormones (General)',
                                'Reproductive Hormones'=> 'Reproductive Hormones (FSH, LH, Estrogen)',
                                'Adrenal Hormones'     => 'Adrenal Hormones (Cortisol, ACTH, DHEA)',
                                'Fertility Tests'      => 'Fertility Tests (AMH, Semen Analysis)',
                                'Growth Hormone'       => 'Growth Hormone / IGF-1',
                                'Insulin & C-Peptide'  => 'Insulin & C-Peptide',
                            ]; @endphp
                            @foreach($hormItems as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('test_category', $test->test_category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="🔬 Urine & Body Fluid">
                            @php $urineItems = [
                                'Urine Analysis'    => 'Urine Full Report (UFR)',
                                'Urine Culture'     => 'Urine Culture & Sensitivity',
                                'Urine Biochemistry'=> 'Urine Biochemistry',
                                'Stool Analysis'    => 'Stool Full Report / Occult Blood',
                                'CSF Analysis'      => 'CSF Analysis',
                                'Sputum Analysis'   => 'Sputum Analysis',
                                'Body Fluid Analysis'=> 'Body Fluid Analysis',
                                'Semen Analysis'    => 'Semen Analysis',
                            ]; @endphp
                            @foreach($urineItems as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('test_category', $test->test_category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="🧫 Histopathology & Cytology">
                            @php $histoItems = [
                                'Histopathology' => 'Histopathology (Biopsy)',
                                'Cytology'       => 'Cytology (FNAC, Pap Smear)',
                                'Pap Smear'      => 'Pap Smear / Cervical Screening',
                            ]; @endphp
                            @foreach($histoItems as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('test_category', $test->test_category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="🧬 Genetics & Molecular">
                            @php $geneItems = [
                                'Molecular Diagnostics' => 'Molecular Diagnostics (PCR)',
                                'Genetic Testing'       => 'Genetic Testing',
                                'DNA Testing'           => 'DNA / Paternity Testing',
                            ]; @endphp
                            @foreach($geneItems as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('test_category', $test->test_category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="👶 Prenatal & Neonatal">
                            @php $prenatalItems = [
                                'Prenatal Screening' => 'Prenatal Screening',
                                'Antenatal Profile'  => 'Antenatal Profile',
                                'Neonatal Screening' => 'Neonatal Screening',
                                'TORCH Profile'      => 'TORCH Profile',
                            ]; @endphp
                            @foreach($prenatalItems as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('test_category', $test->test_category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="📦 Health Packages">
                            @php $pkgItems = [
                                'Health Screening' => 'Health Screening Panels',
                                'Pre-Employment'   => 'Pre-Employment Medical',
                                'Pre-Surgical'     => 'Pre-Surgical / Pre-Operative',
                                'Executive Health' => 'Executive Health Check',
                            ]; @endphp
                            @foreach($pkgItems as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('test_category', $test->test_category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="🏥 Other">
                            @php $otherItems = [
                                'Toxicology'        => 'Toxicology / Drug Screening',
                                'Transfusion Medicine'=> 'Transfusion Medicine',
                                'Point of Care'     => 'Point of Care Testing (POCT)',
                                'Radiology'         => 'Radiology',
                                'Other'             => 'Other',
                            ]; @endphp
                            @foreach($otherItems as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('test_category', $test->test_category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                {{-- Price --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Price (Rs.) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price"
                        value="{{ old('price', $test->price) }}"
                        step="0.01" min="0" required placeholder="0.00"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none @error('price') border-red-400 @enderror">
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Duration --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Duration (Hours)</label>
                    <input type="number" name="duration_hours"
                        value="{{ old('duration_hours', $test->duration_hours) }}"
                        min="0" placeholder="e.g. 24"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                </div>

                {{-- Active Status --}}
                <div class="flex items-center gap-3 pt-2">
                    <label class="text-sm font-semibold text-gray-700">Active Status</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                            {{ old('is_active', $test->is_active) ? 'checked' : '' }}>
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
                        placeholder="Brief description about this test..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none resize-none">{{ old('description', $test->description) }}</textarea>
                </div>

                {{-- Requirements --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Patient Requirements</label>
                    <textarea name="requirements" rows="2"
                        placeholder="e.g. Fasting required 8 hours before test..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none resize-none">{{ old('requirements', $test->requirements) }}</textarea>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-teal-600 text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:bg-teal-700 transition shadow-sm">
                    <i class="fas fa-save mr-2"></i> Save Changes
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
