
@extends('laboratory.layouts.app')
@section('title', 'Chat — '.$partner->name)
@section('page-title', 'Messages')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col" style="height:calc(100vh - 160px)">

    {{-- Header --}}
    <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-100 bg-white">
        <a href="{{ route('laboratory.chat.index') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="w-9 h-9 bg-teal-100 rounded-full flex items-center justify-center font-bold text-teal-700">
            {{ strtoupper(substr($partner->name ?? 'U', 0, 1)) }}
        </div>
        <div>
            <p class="font-bold text-gray-900 text-sm">{{ $partner->name }}</p>
            <p class="text-xs text-gray-400 capitalize">{{ $partner->user_type }}</p>
        </div>
    </div>

    {{-- Messages --}}
    <div id="msgContainer" class="flex-1 overflow-y-auto p-5 space-y-3 bg-gray-50">
        @foreach($messages as $msg)
        <div class="flex {{ $msg->sender_id == $myId ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-xs lg:max-w-sm">
                @if($msg->message)
                <div class="px-4 py-2.5 rounded-2xl text-sm shadow-sm
                    {{ $msg->sender_id == $myId
                        ? 'bg-teal-600 text-white rounded-br-sm'
                        : 'bg-white text-gray-800 rounded-bl-sm border border-gray-100' }}">
                    {{ $msg->message }}
                </div>
                @endif
                @if($msg->attachment_path)
                <a href="{{ asset('storage/'.$msg->attachment_path) }}" target="_blank"
                   class="mt-1 flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs text-teal-600 hover:bg-teal-50">
                    <i class="fas fa-paperclip"></i> Attachment
                </a>
                @endif
                <p class="text-xs text-gray-400 mt-1 {{ $msg->sender_id == $myId ? 'text-right' : '' }}">
                    {{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}
                    @if($msg->sender_id == $myId)
                    <i class="fas fa-check{{ $msg->is_read ? '-double text-teal-500' : ' text-gray-300' }} ml-1 text-xs"></i>
                    @endif
                </p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Input --}}
    <div class="px-5 py-3 border-t border-gray-100 bg-white">
        <form id="chatForm" class="flex items-end gap-3">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $partner->id }}">

            <label for="attachInput" class="cursor-pointer text-gray-400 hover:text-teal-600 transition p-2">
                <i class="fas fa-paperclip text-lg"></i>
            </label>
            <input type="file" id="attachInput" name="attachment" class="hidden" onchange="handleAttach(this)">

            <div class="flex-1 relative">
                <textarea name="message" id="msgInput" rows="1"
                    placeholder="Type a message..."
                    class="w-full border border-gray-200 rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none max-h-32 overflow-auto"></textarea>
            </div>
            <button type="submit"
                class="bg-teal-600 text-white p-2.5 rounded-xl hover:bg-teal-700 transition flex-shrink-0">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
        <div id="attachPreview" class="text-xs text-teal-600 mt-1 hidden">
            <i class="fas fa-paperclip mr-1"></i><span></span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-scroll
const container = document.getElementById('msgContainer');
container.scrollTop = container.scrollHeight;

// Handle file attach preview
function handleAttach(input) {
    const prev = document.getElementById('attachPreview');
    if(input.files[0]){
        prev.classList.remove('hidden');
        prev.querySelector('span').textContent = input.files[0].name;
    }
}

// Send message via AJAX
document.getElementById('chatForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const data = new FormData(form);
    const msg = data.get('message');
    if(!msg && !data.get('attachment').size) return;

    try {
        await fetch('{{ route("laboratory.chat.send") }}', {
            method:'POST',
            body: data,
            headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}
        });
        if(msg) {
            container.insertAdjacentHTML('beforeend', `
                <div class="flex justify-end">
                    <div class="max-w-xs">
                        <div class="px-4 py-2.5 rounded-2xl text-sm bg-teal-600 text-white rounded-br-sm shadow-sm">${msg}</div>
                        <p class="text-xs text-gray-400 mt-1 text-right">Just now</p>
                    </div>
                </div>`);
        }
        form.reset();
        document.getElementById('attachPreview').classList.add('hidden');
        container.scrollTop = container.scrollHeight;
    } catch(err){ console.error(err); }
});

// Enter to send
document.getElementById('msgInput').addEventListener('keydown', function(e){
    if(e.key==='Enter' && !e.shiftKey){
        e.preventDefault();
        document.getElementById('chatForm').dispatchEvent(new Event('submit'));
    }
});
</script>
@endpush
