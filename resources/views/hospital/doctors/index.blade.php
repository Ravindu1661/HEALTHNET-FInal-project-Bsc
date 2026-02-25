@extends('hospital.layouts.app')
@section('title','Doctors')
@section('page-title','Doctors Management')
@section('page-subtitle','Manage affiliated doctors')

@section('content')
<div x-data="hospitalDoctors()" x-init="init()">

    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center">
                    <i class="fas fa-user-md text-teal-600"></i>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800" x-text="summary.total || 0"></p>
                    <p class="text-xs text-gray-500">Total Doctors</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800" x-text="summary.active || 0"></p>
                    <p class="text-xs text-gray-500">Active</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800" x-text="summary.pending || 0"></p>
                    <p class="text-xs text-gray-500">Pending</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-star text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800" x-text="summary.avg_rating || '0.0'"></p>
                    <p class="text-xs text-gray-500">Avg Rating</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter + Add Doctor Button -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" x-model="search" @input.debounce.500ms="load()"
                       placeholder="Search doctor name or specialty..."
                       class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm
                              focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <select x-model="statusFilter" @change="load()"
                    class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">All Status</option>
                <option value="approved">Active</option>
                <option value="pending">Pending</option>
                <option value="rejected">Rejected</option>
            </select>
            <button @click="openAddModal()"
                    class="px-5 py-2.5 bg-teal-600 text-white text-sm font-semibold rounded-lg
                           hover:bg-teal-700 transition flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-plus"></i> Add Doctor
            </button>
        </div>
    </div>

    <!-- Doctors Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        <template x-for="doc in doctors" :key="doc.id">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition flex flex-col">
                <div class="p-5 flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-14 h-14 rounded-full bg-teal-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                            <template x-if="doc.profile_image">
                                <img :src="'/storage/' + doc.profile_image" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!doc.profile_image">
                                <i class="fas fa-user-md text-teal-500 text-xl"></i>
                            </template>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate"
                               x-text="'Dr. ' + (doc.name || '')"></p>
                            <p class="text-xs text-gray-500 truncate"
                               x-text="doc.specialty || 'General'"></p>
                            <span :class="{
                                'bg-green-100 text-green-700':   doc.affiliation_status === 'approved',
                                'bg-yellow-100 text-yellow-700': doc.affiliation_status === 'pending',
                                'bg-red-100 text-red-700':       doc.affiliation_status === 'rejected'
                            }" class="inline-block text-xs px-2 py-0.5 rounded-full font-medium mt-1"
                            x-text="doc.affiliation_status === 'approved' ? 'Active' :
                                     doc.affiliation_status === 'pending'  ? 'Pending' : 'Rejected'">
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <div class="bg-gray-50 rounded-lg p-2 text-center">
                            <p class="text-sm font-bold text-gray-800" x-text="doc.experience_years || 0"></p>
                            <p class="text-xs text-gray-400">Exp</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2 text-center">
                            <p class="text-xs font-bold text-gray-800">
                                Rs.<span x-text="doc.consultation_fee
                                    ? Number(doc.consultation_fee).toLocaleString() : '0'"></span>
                            </p>
                            <p class="text-xs text-gray-400">Fee</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2 text-center">
                            <div class="flex items-center justify-center gap-0.5">
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <span class="text-sm font-bold text-gray-800"
                                      x-text="Number(doc.rating || 0).toFixed(1)"></span>
                            </div>
                            <p class="text-xs text-gray-400">Rating</p>
                        </div>
                    </div>
                    <div class="mb-3" x-show="doc.employment_type">
                        <span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded-full capitalize"
                              x-text="doc.employment_type"></span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500
                                border-t border-gray-100 pt-3">
                        <span class="flex items-center gap-1">
                            <span :class="doc.is_available ? 'bg-green-400' : 'bg-gray-300'"
                                  class="w-2 h-2 rounded-full inline-block"></span>
                            <span x-text="doc.is_available ? 'Available' : 'Unavailable'"></span>
                        </span>
                        <span :class="{
                            'text-green-600':  doc.doctor_status === 'active',
                            'text-yellow-600': doc.doctor_status === 'pending',
                            'text-red-600':    doc.doctor_status === 'suspended'
                        }" class="capitalize" x-text="'Dr: ' + (doc.doctor_status || '')"></span>
                    </div>
                </div>
                <div class="px-5 pb-4 pt-0">
                    <button @click="openDetails(doc)"
                            class="w-full mb-2 py-2 text-xs font-medium text-teal-700 bg-teal-50
                                   border border-teal-200 rounded-lg hover:bg-teal-100 transition
                                   flex items-center justify-center gap-1.5">
                        <i class="fas fa-eye"></i> View Details
                    </button>
                    <div x-show="doc.affiliation_status === 'pending'" class="flex gap-2">
                        <button @click="updateStatus(doc, 'approved')"
                                class="flex-1 py-2 text-xs font-medium text-green-700 bg-green-50
                                       border border-green-200 rounded-lg hover:bg-green-100 transition
                                       flex items-center justify-center gap-1">
                            <i class="fas fa-check"></i> Approve
                        </button>
                        <button @click="updateStatus(doc, 'rejected')"
                                class="flex-1 py-2 text-xs font-medium text-red-600 bg-red-50
                                       border border-red-200 rounded-lg hover:bg-red-100 transition
                                       flex items-center justify-center gap-1">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </div>
                    <div x-show="doc.affiliation_status === 'approved'">
                        <button @click="updateStatus(doc, 'rejected')"
                                class="w-full py-2 text-xs font-medium text-red-600 bg-red-50
                                       border border-red-200 rounded-lg hover:bg-red-100 transition
                                       flex items-center justify-center gap-1.5">
                            <i class="fas fa-user-times"></i> Revoke Access
                        </button>
                    </div>
                    <div x-show="doc.affiliation_status === 'rejected'">
                        <button @click="updateStatus(doc, 'approved')"
                                class="w-full py-2 text-xs font-medium text-green-700 bg-green-50
                                       border border-green-200 rounded-lg hover:bg-green-100 transition
                                       flex items-center justify-center gap-1.5">
                            <i class="fas fa-user-check"></i> Re-approve
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <div x-show="doctors.length === 0 && !loading" class="col-span-full py-16 text-center">
            <i class="fas fa-user-md text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-500 font-medium">No doctors found</p>
            <p class="text-sm text-gray-400 mt-1">No affiliated doctors for this hospital yet.</p>
        </div>
        <div x-show="loading" class="col-span-full py-16 text-center text-gray-400">
            <i class="fas fa-spinner fa-spin text-3xl"></i>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-6 bg-white rounded-xl shadow-sm
                border border-gray-100 px-6 py-4"
         x-show="pg.last_page > 1">
        <p class="text-sm text-gray-500">
            Showing <span x-text="pg.from"></span>–<span x-text="pg.to"></span>
            of <span x-text="pg.total"></span>
        </p>
        <div class="flex gap-2">
            <button @click="changePage(pg.current_page - 1)" :disabled="pg.current_page === 1"
                    class="px-3 py-1.5 text-sm rounded-lg border border-gray-200
                           hover:bg-gray-50 disabled:opacity-40 transition">
                <i class="fas fa-chevron-left"></i>
            </button>
            <span class="px-3 py-1.5 text-sm text-gray-600"
                  x-text="pg.current_page + ' / ' + pg.last_page"></span>
            <button @click="changePage(pg.current_page + 1)" :disabled="pg.current_page === pg.last_page"
                    class="px-3 py-1.5 text-sm rounded-lg border border-gray-200
                           hover:bg-gray-50 disabled:opacity-40 transition">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>


    <!-- ══════════════════════════════════════════
         ADD DOCTOR MODAL
    ═══════════════════════════════════════════ -->
    <div x-show="showAddModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
         @click.self="showAddModal = false"
         style="display:none">

        <div x-show="showAddModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            <!-- Header -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-t-2xl p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-lg">Add Doctor</h2>
                        <p class="text-teal-100 text-sm mt-0.5">Search and add a doctor to your hospital</p>
                    </div>
                    <button @click="showAddModal = false"
                            class="w-8 h-8 rounded-full bg-white/20 flex items-center
                                   justify-center hover:bg-white/30 transition">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <!-- Search Input -->
                <div class="relative mb-4">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text"
                           x-model="addSearch"
                           @input.debounce.400ms="searchDoctors()"
                           placeholder="Search by name, specialty or email..."
                           class="w-full pl-9 pr-10 py-3 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <div x-show="searchingDoctors"
                         class="absolute right-3 top-1/2 -translate-y-1/2">
                        <i class="fas fa-spinner fa-spin text-teal-500 text-sm"></i>
                    </div>
                </div>

                <!-- Employment Type -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Employment Type <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <template x-for="type in ['permanent','temporary','visiting']">
                            <label :class="addEmploymentType === type
                                ? 'border-teal-500 bg-teal-50 text-teal-700'
                                : 'border-gray-200 text-gray-600 hover:border-teal-300'"
                                class="flex items-center justify-center gap-1.5 px-3 py-2.5
                                       border-2 rounded-xl cursor-pointer text-xs font-medium
                                       transition capitalize">
                                <input type="radio" x-model="addEmploymentType" :value="type" class="hidden">
                                <i :class="{
                                    'fa-briefcase':  type==='permanent',
                                    'fa-clock':      type==='temporary',
                                    'fa-user-clock': type==='visiting'
                                }" class="fas text-xs"></i>
                                <span x-text="type"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Results Header -->
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">
                        <span x-show="!searchingDoctors && allDoctors.length > 0">
                            All Doctors
                        </span>
                        <span x-show="addSearch.length > 0">
                            — Search Results
                        </span>
                    </p>
                    <span x-show="searchResults.length > 0"
                          class="bg-teal-100 text-teal-700 px-2 py-0.5 rounded-full text-xs font-semibold"
                          x-text="searchResults.length + ' found'"></span>
                </div>

                <!-- Doctors List -->
                <div class="space-y-2 max-h-72 overflow-y-auto pr-1">

                    <!-- Loading state -->
                    <div x-show="searchingDoctors" class="text-center py-8 text-gray-400">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p class="text-sm">Loading doctors...</p>
                    </div>

                    <!-- Doctor cards -->
                    <template x-for="doc in searchResults" :key="doc.id">
                        <div
                            @click="!doc.already_affiliated && selectDoctor(doc)"
                            :class="{
                                'border-teal-500 bg-teal-50 shadow-sm':
                                    selectedAddDoc && selectedAddDoc.id === doc.id && !doc.already_affiliated,
                                'border-gray-200 hover:border-teal-300 hover:bg-gray-50':
                                    !doc.already_affiliated && !(selectedAddDoc && selectedAddDoc.id === doc.id),
                                'border-gray-100 bg-gray-50 opacity-70 cursor-not-allowed':
                                    doc.already_affiliated,
                            }"
                            class="flex items-center gap-3 p-3 border-2 rounded-xl transition"
                            :style="doc.already_affiliated ? 'cursor:not-allowed' : 'cursor:pointer'">

                            <!-- Avatar -->
                            <div class="w-11 h-11 rounded-full bg-teal-100 flex items-center
                                        justify-center overflow-hidden flex-shrink-0">
                                <template x-if="doc.profile_image">
                                    <img :src="'/storage/' + doc.profile_image"
                                         class="w-full h-full object-cover">
                                </template>
                                <template x-if="!doc.profile_image">
                                    <i class="fas fa-user-md text-teal-500"></i>
                                </template>
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-sm font-semibold text-gray-800 truncate"
                                       x-text="'Dr. ' + doc.name"></p>
                                    <!-- Already affiliated badge -->
                                    <span x-show="doc.already_affiliated"
                                          :class="{
                                              'bg-green-100 text-green-700':  doc.affiliation_status === 'approved',
                                              'bg-yellow-100 text-yellow-700': doc.affiliation_status === 'pending',
                                              'bg-red-100 text-red-700':       doc.affiliation_status === 'rejected',
                                          }"
                                          class="text-xs px-2 py-0.5 rounded-full font-medium whitespace-nowrap"
                                          x-text="doc.affiliation_status === 'approved' ? '✓ Already Added' :
                                                   doc.affiliation_status === 'pending'  ? '⏳ Pending Approval' :
                                                                                           '✗ Rejected'">
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 truncate"
                                   x-text="doc.specialization || 'General'"></p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-xs text-teal-600">
                                        <i class="fas fa-briefcase-medical text-xs"></i>
                                        <span x-text="(doc.experience_years || 0) + ' yrs'"></span>
                                    </span>
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs text-gray-500">
                                        Rs.<span x-text="doc.consultation_fee
                                            ? Number(doc.consultation_fee).toLocaleString() : '0'"></span>
                                    </span>
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs text-yellow-600">
                                        <i class="fas fa-star text-xs"></i>
                                        <span x-text="Number(doc.rating || 0).toFixed(1)"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Selected tick (only for non-affiliated) -->
                            <div x-show="selectedAddDoc && selectedAddDoc.id === doc.id && !doc.already_affiliated"
                                 class="w-6 h-6 bg-teal-500 rounded-full flex items-center
                                        justify-center flex-shrink-0">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>

                            <!-- Lock icon for already affiliated -->
                            <div x-show="doc.already_affiliated"
                                 class="w-6 h-6 bg-gray-200 rounded-full flex items-center
                                        justify-center flex-shrink-0">
                                <i class="fas fa-lock text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </template>

                    <!-- Empty: no doctors at all -->
                    <div x-show="searchResults.length === 0 && !searchingDoctors"
                         class="text-center py-8 text-gray-300">
                        <i class="fas fa-user-md text-4xl mb-3"></i>
                        <p class="text-sm text-gray-400">
                            <span x-show="addSearch.length === 0">No approved doctors found in the system.</span>
                            <span x-show="addSearch.length > 0">No doctors match "<span x-text="addSearch"></span>"</span>
                        </p>
                    </div>
                </div>

                <!-- Selected Doctor Preview -->
                <div x-show="selectedAddDoc" class="mt-4 p-4 bg-teal-50 border border-teal-200 rounded-xl">
                    <p class="text-xs font-semibold text-teal-700 mb-2 uppercase tracking-wide">
                        ✓ Selected Doctor
                    </p>
                    <p class="text-sm font-bold text-gray-800"
                       x-text="'Dr. ' + (selectedAddDoc?.name || '')"></p>
                    <p class="text-xs text-gray-500"
                       x-text="selectedAddDoc?.specialization"></p>
                    <p class="text-xs text-teal-600 mt-1 capitalize">
                        Employment: <span x-text="addEmploymentType"></span>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6">
                    <button @click="showAddModal = false"
                            class="flex-1 py-3 border border-gray-200 text-gray-600 text-sm
                                   font-medium rounded-xl hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button @click="addDoctor()"
                            :disabled="!selectedAddDoc || !addEmploymentType || addingDoctor"
                            class="flex-1 py-3 bg-teal-600 text-white text-sm font-semibold
                                   rounded-xl hover:bg-teal-700 transition disabled:opacity-40
                                   disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <i x-show="!addingDoctor" class="fas fa-user-plus"></i>
                        <i x-show="addingDoctor"  class="fas fa-spinner fa-spin"></i>
                        <span x-text="addingDoctor ? 'Adding...' : 'Add Doctor'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════════
         DOCTOR DETAILS MODAL
    ═══════════════════════════════════════════ -->
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
         @click.self="showModal = false"
         style="display:none">

        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            <template x-if="selectedDoc">
                <div>
                    <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-t-2xl p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="font-bold text-lg">Doctor Details</h2>
                            <button @click="showModal = false"
                                    class="w-8 h-8 rounded-full bg-white/20 flex items-center
                                           justify-center hover:bg-white/30 transition">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center
                                        justify-center overflow-hidden flex-shrink-0">
                                <template x-if="selectedDoc.profile_image">
                                    <img :src="'/storage/' + selectedDoc.profile_image"
                                         class="w-full h-full object-cover">
                                </template>
                                <template x-if="!selectedDoc.profile_image">
                                    <i class="fas fa-user-md text-white text-2xl"></i>
                                </template>
                            </div>
                            <div>
                                <p class="text-xl font-bold"
                                   x-text="'Dr. ' + (selectedDoc.name || '')"></p>
                                <p class="text-teal-100 text-sm"
                                   x-text="selectedDoc.specialty || 'General'"></p>
                                <div class="flex items-center gap-1 mt-1">
                                    <i class="fas fa-star text-yellow-300 text-xs"></i>
                                    <span class="text-sm font-semibold"
                                          x-text="Number(selectedDoc.rating || 0).toFixed(1)"></span>
                                    <span class="text-teal-200 text-xs"
                                          x-text="'(' + (selectedDoc.total_ratings || 0) + ' reviews)'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <span class="text-sm font-medium text-gray-700">Affiliation Status</span>
                            <span :class="{
                                'bg-green-100 text-green-700':   selectedDoc.affiliation_status === 'approved',
                                'bg-yellow-100 text-yellow-700': selectedDoc.affiliation_status === 'pending',
                                'bg-red-100 text-red-700':       selectedDoc.affiliation_status === 'rejected'
                            }" class="text-xs px-3 py-1 rounded-full font-semibold capitalize"
                            x-text="selectedDoc.affiliation_status === 'approved' ? '✓ Active' :
                                     selectedDoc.affiliation_status === 'pending'  ? '⏳ Pending' : '✗ Rejected'">
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-teal-50 rounded-xl p-4 text-center">
                                <p class="text-2xl font-bold text-teal-700"
                                   x-text="selectedDoc.experience_years || 0"></p>
                                <p class="text-xs text-teal-500 mt-1">Years Experience</p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-4 text-center">
                                <p class="text-lg font-bold text-blue-700">
                                    Rs.<span x-text="selectedDoc.consultation_fee
                                        ? Number(selectedDoc.consultation_fee).toLocaleString() : '0'"></span>
                                </p>
                                <p class="text-xs text-blue-500 mt-1">Consultation Fee</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2.5 border-b border-gray-100">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <i class="fas fa-briefcase-medical text-teal-400 w-4"></i>
                                    Employment Type
                                </span>
                                <span class="text-sm font-medium text-gray-800 capitalize"
                                      x-text="selectedDoc.employment_type || 'N/A'"></span>
                            </div>
                            <div class="flex items-center justify-between py-2.5 border-b border-gray-100">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <i class="fas fa-circle text-teal-400 w-4"></i>
                                    Availability
                                </span>
                                <span :class="selectedDoc.is_available
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-100 text-gray-600'"
                                    class="text-xs px-2.5 py-1 rounded-full font-medium"
                                    x-text="selectedDoc.is_available ? 'Available' : 'Unavailable'">
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-2.5 border-b border-gray-100">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <i class="fas fa-stethoscope text-teal-400 w-4"></i>
                                    Doctor Account Status
                                </span>
                                <span class="text-sm font-medium text-gray-800 capitalize"
                                      x-text="selectedDoc.doctor_status || 'N/A'"></span>
                            </div>
                            <div x-show="selectedDoc.bio" class="pt-1">
                                <p class="text-sm text-gray-500 mb-2 flex items-center gap-2">
                                    <i class="fas fa-info-circle text-teal-400 w-4"></i> Bio
                                </p>
                                <p class="text-sm text-gray-600 bg-gray-50 rounded-lg p-3 leading-relaxed"
                                   x-text="selectedDoc.bio"></p>
                            </div>
                        </div>

                        <div class="pt-2 space-y-2">
                            <template x-if="selectedDoc.affiliation_status === 'pending'">
                                <div class="flex gap-3">
                                    <button @click="updateStatus(selectedDoc, 'approved')"
                                            class="flex-1 py-3 bg-green-600 text-white text-sm font-semibold
                                                   rounded-xl hover:bg-green-700 transition
                                                   flex items-center justify-center gap-2">
                                        <i class="fas fa-user-check"></i> Approve Doctor
                                    </button>
                                    <button @click="updateStatus(selectedDoc, 'rejected')"
                                            class="flex-1 py-3 bg-red-500 text-white text-sm font-semibold
                                                   rounded-xl hover:bg-red-600 transition
                                                   flex items-center justify-center gap-2">
                                        <i class="fas fa-user-times"></i> Reject
                                    </button>
                                </div>
                            </template>
                            <template x-if="selectedDoc.affiliation_status === 'approved'">
                                <button @click="updateStatus(selectedDoc, 'rejected')"
                                        class="w-full py-3 bg-red-50 text-red-600 text-sm font-semibold
                                               border border-red-200 rounded-xl hover:bg-red-100 transition
                                               flex items-center justify-center gap-2">
                                    <i class="fas fa-user-times"></i> Revoke Affiliation
                                </button>
                            </template>
                            <template x-if="selectedDoc.affiliation_status === 'rejected'">
                                <button @click="updateStatus(selectedDoc, 'approved')"
                                        class="w-full py-3 bg-green-600 text-white text-sm font-semibold
                                               rounded-xl hover:bg-green-700 transition
                                               flex items-center justify-center gap-2">
                                    <i class="fas fa-user-check"></i> Re-approve Affiliation
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>


    <!-- Toast -->
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-500'"
         class="fixed bottom-6 right-6 text-white px-5 py-3 rounded-xl shadow-xl
                flex items-center gap-3 z-50 text-sm font-medium"
         style="display:none">
        <i :class="toast.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'"
           class="fas"></i>
        <span x-text="toast.message"></span>
    </div>

</div>
@endsection

@push('scripts')
<script>
function hospitalDoctors() {
    return {
        doctors:      [],
        summary:      { total: 0, active: 0, pending: 0, avg_rating: '0.0' },
        loading:      true,
        search:       '',
        statusFilter: '',
        pg:           { current_page: 1, last_page: 1, from: 0, to: 0, total: 0 },

        // Details Modal
        showModal:    false,
        selectedDoc:  null,

        // Add Doctor Modal
        showAddModal:      false,
        addSearch:         '',
        allDoctors:        [],   // ← ALL doctors from server (with affiliation info)
        searchResults:     [],   // ← filtered list shown in modal
        searchingDoctors:  false,
        selectedAddDoc:    null,
        addEmploymentType: 'permanent',
        addingDoctor:      false,

        // Toast
        toast: { show: false, type: 'success', message: '' },

        // ─── Init ────────────────────────────────────────
        async init() { await this.load(); },

        // ─── Load Affiliated Doctors (main list) ─────────
        async load(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    search: this.search,
                    status: this.statusFilter,
                    page
                });
                const res  = await fetch(
                    `{{ route('hospital.doctors.data') }}?${params}`,
                    { headers: { 'Accept': 'application/json' } }
                );
                const data = await res.json();
                if (data.success) {
                    this.doctors = data.doctors.data;
                    const m      = data.doctors;
                    this.pg      = {
                        current_page: m.current_page,
                        last_page:    m.last_page,
                        from:         m.from  || 0,
                        to:           m.to    || 0,
                        total:        m.total
                    };
                    this.buildSummary(m.total);
                } else {
                    this.showToast('error', data.message || 'Failed to load doctors.');
                }
            } catch(e) {
                console.error(e);
                this.showToast('error', 'Connection error.');
            }
            this.loading = false;
        },

        // ─── Summary Stats ───────────────────────────────
        buildSummary(total) {
            const all     = this.doctors;
            const ratings = all.filter(d => Number(d.rating) > 0).map(d => Number(d.rating));
            this.summary  = {
                total,
                active:     all.filter(d => d.affiliation_status === 'approved').length,
                pending:    all.filter(d => d.affiliation_status === 'pending').length,
                avg_rating: ratings.length
                    ? (ratings.reduce((a, b) => a + b, 0) / ratings.length).toFixed(1)
                    : '0.0'
            };
        },

        // ─── Open Details Modal ──────────────────────────
        openDetails(doc) {
            this.selectedDoc = { ...doc };
            this.showModal   = true;
        },

        // ─── Open Add Modal — load ALL doctors immediately ─
        async openAddModal() {
            this.addSearch         = '';
            this.allDoctors        = [];
            this.searchResults     = [];
            this.selectedAddDoc    = null;
            this.addEmploymentType = 'permanent';
            this.addingDoctor      = false;
            this.showAddModal      = true;
            // ✅ Modal open වූ සැනින් සියලු doctors load කරනවා
            await this.searchDoctors();
        },

        // ─── Search / Load Doctors for Modal ─────────────
        // q="" → returns ALL approved doctors (with affiliation_status per this hospital)
        async searchDoctors() {
            this.searchingDoctors = true;
            try {
                const q   = encodeURIComponent(this.addSearch.trim());
                const res = await fetch(
                    `{{ route('hospital.doctors.search') }}?q=${q}`,
                    { headers: { 'Accept': 'application/json' } }
                );
                const data = await res.json();
                if (data.success) {
                    this.searchResults = data.doctors;
                    this.allDoctors    = data.doctors;
                } else {
                    this.showToast('error', data.message || 'Failed to load doctors.');
                }
            } catch(e) {
                console.error(e);
                this.showToast('error', 'Connection error.');
            }
            this.searchingDoctors = false;
        },

        // ─── Select Doctor (only non-affiliated) ─────────
        selectDoctor(doc) {
            if (doc.already_affiliated) return;
            this.selectedAddDoc = this.selectedAddDoc?.id === doc.id ? null : doc;
        },

        // ─── Add Doctor to Hospital ──────────────────────
        async addDoctor() {
            if (!this.selectedAddDoc || !this.addEmploymentType) return;
            this.addingDoctor = true;
            try {
                const res  = await fetch('{{ route("hospital.doctors.add") }}', {
                    method:  'POST',
                    headers: {
                        'X-CSRF-TOKEN':  '{{ csrf_token() }}',
                        'Accept':        'application/json',
                        'Content-Type':  'application/json',
                    },
                    body: JSON.stringify({
                        doctor_id:       this.selectedAddDoc.id,
                        employment_type: this.addEmploymentType,
                    }),
                });
                const data = await res.json();
                if (data.success) {
                    this.showAddModal = false;
                    this.showToast('success', data.message || 'Doctor added successfully!');
                    await this.load();
                } else {
                    this.showToast('error', data.message || 'Failed to add doctor.');
                }
            } catch(e) {
                console.error(e);
                this.showToast('error', 'Connection error.');
            }
            this.addingDoctor = false;
        },

        // ─── Approve / Reject Affiliation ────────────────
        async updateStatus(doc, newStatus) {
            const label = newStatus === 'approved' ? 'approve' : 'reject';
            if (!confirm(`Are you sure you want to ${label} Dr. ${doc.name}?`)) return;
            try {
                const res  = await fetch(
                    `{{ url('hospital/doctors') }}/${doc.id}/status`,
                    {
                        method:  'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept':       'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ status: newStatus }),
                    }
                );
                const data = await res.json();
                if (data.success) {
                    doc.affiliation_status = newStatus;
                    if (this.selectedDoc && this.selectedDoc.id === doc.id) {
                        this.selectedDoc.affiliation_status = newStatus;
                    }
                    this.buildSummary(this.pg.total);
                    this.showToast('success',
                        newStatus === 'approved'
                            ? `Dr. ${doc.name} approved!`
                            : `Dr. ${doc.name} affiliation rejected.`
                    );
                } else {
                    this.showToast('error', data.message || 'Update failed.');
                }
            } catch(e) {
                this.showToast('error', 'Connection error.');
            }
        },

        // ─── Pagination ──────────────────────────────────
        changePage(p) {
            if (p < 1 || p > this.pg.last_page) return;
            this.load(p);
        },

        // ─── Toast ───────────────────────────────────────
        showToast(type, message) {
            this.toast = { show: true, type, message };
            setTimeout(() => this.toast.show = false, 3500);
        }
    }
}
</script>
@endpush
