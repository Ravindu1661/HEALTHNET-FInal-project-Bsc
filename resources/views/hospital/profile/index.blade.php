@extends('hospital.layouts.app')
@section('title','Hospital Profile')
@section('page-title','Hospital Profile')
@section('page-subtitle','Manage your hospital information')

@section('content')
<div x-data="{ tab: 'info' }">

    <!-- Tabs -->
    <div class="flex gap-2 mb-6 overflow-x-auto">
        @foreach([['info','building','Basic Information'],['contact','phone','Contact & Location'],['documents','file-alt','Documents']] as [$k,$i,$l])
        <button @click="tab='{{ $k }}'"
                :class="tab==='{{ $k }}' ? 'bg-teal-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium transition border border-gray-200 whitespace-nowrap">
            <i class="fas fa-{{ $i }}"></i> {{ $l }}
        </button>
        @endforeach
    </div>

    <!-- BASIC INFO -->
    <div x-show="tab==='info'">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Logo / Photo -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="relative inline-block mb-4">
                    <div class="w-28 h-28 rounded-2xl bg-teal-100 flex items-center justify-center overflow-hidden mx-auto border-4 border-teal-200">
                        @if($hospital->profile_image)
                            <img src="{{ asset('storage/'.$hospital->profile_image) }}"
                                 alt="Hospital" class="w-full h-full object-cover" id="hImgPreview">
                        @else
                            <i class="fas fa-hospital text-teal-400 text-4xl"></i>
                            <img id="hImgPreview" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                    <label for="hImgInput"
                           class="absolute bottom-0 right-0 w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-teal-700 transition shadow">
                        <i class="fas fa-camera text-white text-xs"></i>
                    </label>
                </div>
                <h3 class="font-bold text-gray-800 text-lg">{{ $hospital->name }}</h3>
                <p class="text-sm text-gray-500 capitalize">{{ $hospital->type }}</p>
                <p class="text-sm text-gray-400 mt-1">{{ $hospital->city }}, {{ $hospital->province }}</p>
                <div class="mt-3 flex items-center justify-center gap-1">
                    @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star text-sm {{ $i <= round($hospital->rating) ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                    @endfor
                    <span class="text-sm text-gray-500 ml-1">{{ number_format($hospital->rating,1) }}</span>
                </div>
                <div class="mt-3">
                    @if($hospital->status === 'approved')
                        <span class="inline-flex items-center gap-1.5 text-xs text-green-700 bg-green-100 px-3 py-1.5 rounded-full">
                            <i class="fas fa-shield-alt"></i> Verified Hospital
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs text-yellow-700 bg-yellow-100 px-3 py-1.5 rounded-full">
                            <i class="fas fa-clock"></i> Pending Verification
                        </span>
                    @endif
                </div>
                <form action="{{ route('hospital.profile.photo') }}" method="POST" enctype="multipart/form-data" id="hPhotoForm">
                    @csrf
                    <input type="file" id="hImgInput" name="profile_image" accept="image/*" class="hidden"
                           onchange="previewHospitalPhoto(this); document.getElementById('hPhotoForm').submit()">
                </form>
            </div>

            <!-- Edit Form -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100">Hospital Information</h3>
                <form action="{{ route('hospital.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div class="col-span-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Hospital Name</label>
                            <input type="text" name="name" value="{{ old('name', $hospital->name) }}" required
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Registration Number</label>
                            <input type="text" value="{{ $hospital->registration_number }}" disabled
                                   class="w-full border border-gray-100 bg-gray-50 rounded-lg px-4 py-2.5 text-sm text-gray-400 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Hospital Type</label>
                            <select name="type" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                                @foreach(['government','private','clinic','dispensary','specialty'] as $t)
                                <option value="{{ $t }}" {{ $hospital->type===$t ? 'selected' : '' }} class="capitalize">{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                            <input type="tel" name="phone" value="{{ old('phone', $hospital->phone) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email', $hospital->email) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">City</label>
                            <input type="text" name="city" value="{{ old('city', $hospital->city) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Province</label>
                            <input type="text" name="province" value="{{ old('province', $hospital->province) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Website</label>
                            <input type="url" name="website" value="{{ old('website', $hospital->website) }}"
                                   placeholder="https://..."
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div class="col-span-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Address</label>
                            <input type="text" name="address" value="{{ old('address', $hospital->address) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div class="col-span-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                            <textarea name="description" rows="4"
                                      class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"
                                      placeholder="Describe your hospital services...">{{ old('description', $hospital->description) }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                                class="px-6 py-2.5 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition flex items-center gap-2">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CONTACT & LOCATION -->
    <div x-show="tab==='contact'" x-cloak>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
            <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100">Contact Information</h3>
            <form action="{{ route('hospital.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $hospital->email) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone', $hospital->phone) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Address</label>
                        <textarea name="address" rows="2"
                                  class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('address', $hospital->address) }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">City</label>
                            <input type="text" name="city" value="{{ old('city', $hospital->city) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Province</label>
                            <input type="text" name="province" value="{{ old('province', $hospital->province) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                            class="px-6 py-2.5 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Update Contact
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DOCUMENTS -->
    <div x-show="tab==='documents'" x-cloak>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
            <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100">Verification Documents</h3>
            @if($hospital->document_path)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-3 mb-5">
                <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                <div>
                    <p class="text-sm font-medium text-gray-800">Document Uploaded</p>
                    <a href="{{ asset('storage/'.$hospital->document_path) }}" target="_blank"
                       class="text-xs text-teal-600 hover:underline">View Document</a>
                </div>
            </div>
            @endif
            <form action="{{ route('hospital.profile.documents') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-teal-400 transition cursor-pointer"
                     onclick="document.getElementById('docInput').click()">
                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-300 mb-3"></i>
                    <p class="text-sm font-medium text-gray-600">Click to upload verification document</p>
                    <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG (Max 5MB)</p>
                </div>
                <input type="file" id="docInput" name="document" accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                       onchange="document.getElementById('docName').textContent = this.files[0]?.name || ''">
                <p id="docName" class="text-sm text-gray-600 mt-2 text-center"></p>
                <div class="flex justify-end mt-5">
                    <button type="submit"
                            class="px-6 py-2.5 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition flex items-center gap-2">
                        <i class="fas fa-upload"></i> Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewHospitalPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const prev = document.getElementById('hImgPreview');
            prev.src = e.target.result;
            prev.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
