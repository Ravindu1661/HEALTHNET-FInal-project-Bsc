@extends('laboratory.layouts.app')
@section('title','Messages')
@section('page-title','Messages')
@section('page-subtitle','Chat with patients and doctors')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" style="height:calc(100vh - 160px)">
    <div class="flex h-full">

        {{-- Conversations List --}}
        <div class="w-80 border-r border-gray-100 flex flex-col">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 mb-3">Conversations</h3>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" placeholder="Search..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
            <div class="flex-1 overflow-y-auto divide-y divide-gray-50">
                @forelse($conversations as $conv)
                <a href="{{ route('laboratory.chat.conversation', $conv['partner_id']) }}"
                   class="flex items-center gap-3 px-4 py-3 hover:bg-teal-50 transition">
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center font-bold text-teal-700">
                            {{ strtoupper(substr($conv['user']->name ?? 'U', 0, 1)) }}
                        </div>
                        @if($conv['unread'] > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">{{ $conv['unread'] }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-baseline justify-between">
                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $conv['user']->name ?? 'User' }}</p>
                            <span class="text-xs text-gray-400 flex-shrink-0">{{ \Carbon\Carbon::parse($conv['last_at'])->diffForHumans(null, true) }}</span>
                        </div>
                        <p class="text-xs text-gray-400 truncate">{{ $conv['last_message'] }}</p>
                    </div>
                </a>
                @empty
                <div class="p-8 text-center">
                    <i class="fas fa-comments text-gray-200 text-4xl mb-2 block"></i>
                    <p class="text-gray-400 text-sm">No conversations yet</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Empty State --}}
        <div class="flex-1 flex items-center justify-center bg-gray-50">
            <div class="text-center">
                <i class="fas fa-comment-dots text-gray-200 text-6xl mb-4 block"></i>
                <p class="text-gray-400 font-medium">Select a conversation</p>
                <p class="text-gray-300 text-sm mt-1">Choose from the list to start chatting</p>
            </div>
        </div>
    </div>
</div>
@endsection
