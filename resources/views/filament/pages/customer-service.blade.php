<x-filament-panels::page>
    <div class="flex h-[600px] w-full border border-gray-200 dark:border-white/10 rounded-xl overflow-hidden bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
        
        <div class="w-1/3 border-r border-gray-200 dark:border-white/10 flex flex-col bg-gray-50 dark:bg-gray-900">
            
            <div class="p-4 border-b border-gray-200 dark:border-white/10 font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2 bg-white dark:bg-gray-900">
                <x-heroicon-o-users class="w-5 h-5 text-gray-400" />
                <span>Daftar Pelanggan</span>
            </div>
            
            <div class="flex-1 overflow-y-auto" wire:poll.5s>
                @foreach($this->chatUsers as $user)
                    <button 
                        wire:click="selectUser({{ $user->id }})"
                        class="w-full text-left p-4 border-b border-gray-100 dark:border-white/5 hover:bg-gray-100 dark:hover:bg-white/5 transition flex items-center gap-3 group
                        {{ $selectedUserId == $user->id ? 'bg-primary-50 dark:bg-white/5 border-l-4 border-l-primary-600' : 'border-l-4 border-l-transparent' }}"
                    >
                        <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center font-bold text-gray-600 dark:text-gray-300 group-hover:bg-white dark:group-hover:bg-gray-600 transition">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $user->name }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $user->chats->first()?->created_at->format('H:i') }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate group-hover:text-gray-700 dark:group-hover:text-gray-300">
                                {{ Str::limit($user->chats->first()?->message, 30) }}
                            </p>
                        </div>
                    </button>
                @endforeach

                @if($this->chatUsers->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-500 text-sm p-4 text-center">
                        <x-heroicon-o-chat-bubble-oval-left class="w-10 h-10 mb-2 opacity-50" />
                        <p>Belum ada pesan masuk.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="w-2/3 flex flex-col relative bg-white dark:bg-gray-900/50">
            @if($selectedUserId)
                <div class="p-4 border-b border-gray-200 dark:border-white/10 flex items-center justify-between bg-white dark:bg-gray-900 z-10 shadow-sm">
                    <div class="flex items-center gap-3">
                         <div class="w-9 h-9 rounded-full bg-primary-100 dark:bg-primary-500/20 text-primary-600 dark:text-primary-400 flex items-center justify-center font-bold border border-primary-200 dark:border-primary-500/30">
                            {{ substr(\App\Models\User::find($selectedUserId)->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white leading-tight">
                                {{ \App\Models\User::find($selectedUserId)->name }}
                            </h3>
                            <span class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1 font-medium">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                Online
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-950/50" id="chat-box" wire:poll.3s>
                    @foreach($this->selectedChats as $chat)
                        <div class="flex w-full {{ $chat->is_admin_reply ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[70%] rounded-2xl px-4 py-2.5 text-sm shadow-sm
                                {{ $chat->is_admin_reply 
                                    ? 'bg-primary-600 text-white rounded-tr-none' 
                                    : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700 rounded-tl-none' 
                                }}">
                                <p class="leading-relaxed">{{ $chat->message }}</p>
                                <div class="text-[10px] mt-1 text-right {{ $chat->is_admin_reply ? 'text-primary-200' : 'text-gray-400' }}">
                                    {{ $chat->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 border-t border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900">
                    <form wire:submit.prevent="sendMessage" class="flex gap-3">
                        <input 
                            wire:model="message" 
                            type="text" 
                            placeholder="Ketik balasan..." 
                            class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-primary-500 focus:ring-primary-500 placeholder-gray-400 dark:placeholder-gray-500"
                            autocomplete="off"
                        >
                        <button type="submit" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-500 text-white rounded-lg font-bold transition flex items-center gap-2 shadow-lg shadow-primary-500/30">
                            <span>Kirim</span>
                            <x-heroicon-m-paper-airplane class="w-4 h-4" />
                        </button>
                    </form>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <x-heroicon-o-chat-bubble-left-right class="w-10 h-10 opacity-50" />
                    </div>
                    <p class="font-medium">Pilih pelanggan di sidebar kiri</p>
                    <p class="text-sm opacity-60">untuk melihat riwayat percakapan.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const chatBox = document.getElementById('chat-box');
            
            const scrollToBottom = () => {
                if(chatBox) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            };

            scrollToBottom();

            Livewire.hook('morph.updated', () => {
                scrollToBottom();
            });
        });
    </script>
</x-filament-panels::page>