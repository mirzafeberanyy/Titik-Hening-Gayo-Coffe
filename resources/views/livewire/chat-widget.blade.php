<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
    
    <div x-data="{ open: @entangle('isOpen') }" x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90 translate-y-10"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         class="mb-4 w-80 md:w-96 h-[500px] bg-white dark:bg-gray-900 shadow-2xl rounded-2xl border border-gray-200 dark:border-white/10 flex flex-col overflow-hidden" 
         style="display: none;">
        
        <div class="p-4 bg-gray-900 dark:bg-black text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="font-bold text-sm text-cream">Customer Service</span>
            </div>
            <button wire:click="toggleChat" class="hover:text-cream transition">&times;</button>
        </div>

        <div wire:poll.3s class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-950" id="user-chat-box">
            @forelse($chats as $chat)
                <div class="flex {{ $chat->is_admin_reply ? 'justify-start' : 'justify-end' }}">
                    <div class="max-w-[85%] px-3 py-2 rounded-xl text-sm shadow-sm
                        {{ $chat->is_admin_reply 
                            ? 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-tl-none border border-gray-200 dark:border-gray-700' 
                            : 'bg-gray-900 text-white rounded-tr-none' }}">
                        {{ $chat->message }}
                        <div class="text-[9px] mt-1 opacity-50 text-right">
                            {{ $chat->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-center p-6">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <p class="text-xs text-gray-500">Ada yang bisa kami bantu? Silakan kirim pesan di bawah ini.</p>
                </div>
            @endforelse
        </div>

        <form wire:submit.prevent="sendMessage" class="p-3 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-white/10 flex gap-2">
            <input wire:model="message" type="text" placeholder="Tulis pesan..." 
                   class="flex-1 text-sm bg-gray-100 dark:bg-gray-800 border-none rounded-lg focus:ring-1 focus:ring-gray-400 dark:text-white">
            <button type="submit" class="p-2 bg-gray-900 dark:bg-gray-700 text-white rounded-lg hover:bg-black transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>
    </div>

    <button wire:click="toggleChat" 
            class="group relative flex items-center justify-center w-14 h-14 bg-gray-900 dark:bg-black text-white rounded-full shadow-2xl hover:scale-110 transition-all duration-300 border-2 border-white/10">
        
        @php
            $unread = auth()->check() ? \App\Models\Chat::where('user_id', auth()->id())->where('is_admin_reply', true)->where('is_read', false)->count() : 0;
        @endphp
        
        @if($unread > 0)
            <span class="absolute -top-1 -right-1 flex h-5 w-5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-5 w-5 bg-red-500 text-[10px] items-center justify-center font-bold">{{ $unread }}</span>
            </span>
        @endif

        <svg x-show="!$wire.isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
        
        <svg x-show="$wire.isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
</div>

<script>
    // Auto scroll ke bawah tiap ada pesan baru
    window.addEventListener('scroll-bottom', () => {
        const box = document.getElementById('user-chat-box');
        setTimeout(() => { box.scrollTop = box.scrollHeight; }, 100);
    });
</script>