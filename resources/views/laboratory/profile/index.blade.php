@extends('laboratory.layouts.app')
@section('title','Lab Profile')
@section('page-title','Lab Profile')
@section('page-subtitle','Manage your laboratory profile')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Profile Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-br from-teal-600 to-teal-700 h-24"></div>
        <div class="px-6 pb-6 -mt-10">
            <div class="relative inline-block mb-4">
                <img src="{{ $lab->profile_image ? asset('storage/'.$lab->profile_image) : asset('images/default-lab.png') }}"
                     class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-md"
                     onerror="this.src='{{ asset('images/default-lab.png') }}'">
                <label for="quickImageUpload" class="absolute -bottom-1 -right-1 bg-teal-600 text-white w-6 h-6 rounded-full flex items-center justify-center cursor-pointer hover:bg-teal-700 transition shadow">
                    <i class="fas fa-camera text-xs"></i>
                </label>
            </div>

            <form method="POST" action="{{ route('laboratory.profile.image') }}" enctype="multipart/form-data" id="imageForm">
                @csrf
                <input type="file" id="quickImageUpload" name="profile_image" accept="image/*" class="hidden"
                       onchange="document.getElementById('imageForm').submit()">
            </form>

            <h2 class="text-xl font-bold text-gray-900">{{ $lab->name }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $lab->city }}, {{ $lab->province }}</p>
            <div class="flex items-center gap-1.5 mt-2">
                @if($lab->status === 'approved')
                    <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                    <span class="text-green-600 text-sm font-medium">Verified Laboratory</span>
                @else
                    <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                    <span class="text-yellow-600 text-sm font-medium">{{ ucfirst($lab->status) }}</span>
                @endif
            </div>

            <div class="mt-5 space-y-3">
                @if($lab->phone)
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="fas fa-phone text-teal-500 w-4"></i> {{ $lab->phone }}
                </div>
                @endif
                @if($lab->email)
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="fas fa-envelope text-teal-500 w-4"></i> {{ $lab->email }}
                </div>
                @endif
                @if($lab->address)
                <div class="flex items-start gap-2 text-sm text-gray-600">
                    <i class="fas fa-map-marker-alt text-teal-500 w-4 mt-0.5"></i>
                    <span>{{ $lab->address }}, {{ $lab->city }}</span>
                </div>
                @endif
                @if($lab->rating)
                <div class="flex items-center gap-2 text-sm">
                    <div class="flex text-yellow-400 gap-0.5">
                        @for($i=1;$i<=5;$i++)<i class="fas fa-star text-xs {{ $i<=$lab->rating ? '' : 'text-gray-200' }}"></i>@endfor
                    </div>
                    <span class="text-gray-500">{{ number_format($lab->rating,1) }} ({{ $lab->total_ratings }} reviews)</span>
                </div>
                @endif
            </div>

            <a href="{{ route('laboratory.profile.edit') }}"
               class="mt-5 block w-full text-center bg-teal-600 text-white py-2.5 rounded-xl font-semibold text-sm hover:bg-teal-700 transition">
                <i class="fas fa-edit mr-2"></i> Edit Profile
            </a>
        </div>
    </div>

    {{-- Details + Verification --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Lab Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-teal-600"></i> Laboratory Details
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs uppercase font-semibold mb-1">Registration No.</p>
                    <p class="text-gray-900 font-medium">{{ $lab->registration_number ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs uppercase font-semibold mb-1">Operating Hours</p>
                    <p class="text-gray-900 font-medium">{{ $lab->operating_hours ?? '—' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-400 text-xs uppercase font-semibold mb-1">Description</p>
                    <p class="text-gray-700">{{ $lab->description ?? 'No description provided.' }}</p>
                </div>
                @if($lab->services)
                <div class="col-span-2">
                    <p class="text-gray-400 text-xs uppercase font-semibold mb-2">Services</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach((array)$lab->services as $service)
                        <span class="bg-teal-50 text-teal-700 px-3 py-1 rounded-full text-xs font-medium">{{ $service }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Verification Status --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-shield-alt text-teal-600"></i> Verification Status
            </h3>
            <div class="flex items-center gap-4 p-4 rounded-xl
                {{ $lab->status === 'approved' ? 'bg-green-50 border border-green-200' : ($lab->status === 'pending' ? 'bg-yellow-50 border border-yellow-200' : 'bg-red-50 border border-red-200') }}">
                <div class="{{ $lab->status === 'approved' ? 'bg-green-100' : ($lab->status === 'pending' ? 'bg-yellow-100' : 'bg-red-100') }} p-3 rounded-xl">
                    <i class="fas {{ $lab->status === 'approved' ? 'fa-check-circle text-green-600' : ($lab->status === 'pending' ? 'fa-clock text-yellow-600' : 'fa-times-circle text-red-600') }} text-xl"></i>
                </div>
                <div>
                    <p class="font-bold {{ $lab->status === 'approved' ? 'text-green-800' : ($lab->status === 'pending' ? 'text-yellow-800' : 'text-red-800') }}">
                        {{ ucfirst($lab->status) }}
                    </p>
                    <p class="text-sm {{ $lab->status === 'approved' ? 'text-green-600' : ($lab->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                        @if($lab->status === 'approved') Your laboratory is verified and live
                        @elseif($lab->status === 'pending') Under review by admin. Please upload documents
                        @else Your verification was rejected. Please contact support @endif
                    </p>
                </div>
            </div>

            @if($lab->document_path)
            <div class="mt-4 flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-700">Verification Document</p>
                    <a href="{{ asset('storage/'.$lab->document_path) }}" target="_blank"
                       class="text-xs text-teal-600 hover:underline">View Document →</a>
                </div>
            </div>
            @endif

            <div class="mt-4">
                <form method="POST" action="{{ route('laboratory.profile.documents') }}" enctype="multipart/form-data"
                      class="flex items-end gap-3">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload/Update Document</label>
                        <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <button type="submit" class="bg-teal-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition">
                        Upload
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
