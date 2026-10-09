<div class="min-h-screen bg-bg-dark pt-32 pb-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-serif font-bold text-cream mb-8">Pesanan Saya</h1>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 text-green-400 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @forelse($orders as $order)
            <div class="bg-bg-secondary rounded-2xl border border-white/5 p-6 mb-6 shadow-lg">
                
                <div class="flex flex-col md:flex-row justify-between md:items-center border-b border-white/10 pb-4 mb-4 gap-4">
                    <div>
                        <div class="text-xs text-white/50 uppercase tracking-wider mb-1">No. Pesanan</div>
                        <div class="text-lg font-bold text-white">#{{ $order->code }}</div>
                        <div class="text-xs text-white/40 mt-1">{{ $order->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $statusColor = match($order->order_status) {
                                'new' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                'processing' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                'shipping' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                'completed' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                default => 'bg-gray-500/10 text-gray-400',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusColor }}">
                            {{ strtoupper($order->order_status) }}
                        </span>

                        @if($order->order_status == 'shipping' && $order->tracking_number)
                            <div class="mt-2 md:mt-0 md:ml-4 bg-gray-700 px-3 py-1 rounded border border-gray-600 flex items-center gap-2">
                                <span class="text-xs text-gray-400 uppercase">Resi:</span>
                                <span class="text-sm font-mono font-bold text-green-400 tracking-wider select-all">
                                    {{ $order->tracking_number }}
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-3 mb-6">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center text-sm border-b border-white/5 last:border-0 py-3">
                            <div class="flex items-center gap-3">
                                <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl border border-white/10 bg-white/5 flex items-center justify-center group hover:bg-white/10 transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-cream/70 group-hover:text-cream group-hover:scale-110 transition-transform duration-300">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" class="hidden"/> <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" class="hidden"/>
                                        
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9v6.5a3.5 3.5 0 003.5 3.5h5a3.5 3.5 0 003.5-3.5V9M6 9h12" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 11.5h1.5a2.5 2.5 0 002.5-2.5v0a2.5 2.5 0 00-2.5-2.5H18" />
                                    </svg>
                                </div>
                                
                                <div>
                                    <p class="text-white font-medium">{{ $item->product->name }}</p>                               
                                    <p class="text-white/40">x{{ $item->quantity }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <div class="text-white">Rp {{ number_format($item->total_price, 0, ',', '.') }}</div>
                                
                                @if($order->order_status == 'completed')
                                    <button wire:click="openReviewModal({{ $order->id }}, {{ $item->product_id }})" class="text-xs bg-white/10 hover:bg-cream hover:text-bg-dark text-cream px-3 py-1 rounded transition">
                                        ★ Nilai
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-col md:flex-row justify-between items-center pt-4 border-t border-white/10 gap-4">
                    <div class="text-white">
                        Total: <span class="font-bold text-xl text-cream ml-2">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex gap-3">
                        @if($order->payment_status == 'pending' && $order->order_status != 'cancelled' && $order->payment_method == 'midtrans')
                            <a href="{{ route('checkout.success', $order->code) }}" class="...">
                                Bayar Sekarang
                            </a>
                        @endif

                        @if($order->order_status == 'new')
                            <button wire:click="cancelOrder({{ $order->id }})" wire:confirm="Yakin ingin membatalkan pesanan ini?" class="px-6 py-2 border border-red-500/30 text-red-400 font-bold rounded-lg hover:bg-red-500/10 transition">
                                Batalkan Pesanan
                            </button>
                        @endif

                        @if($order->order_status == 'shipping')
                            <button wire:click="completeOrder({{ $order->id }})" wire:confirm="Pastikan barang sudah Anda terima dengan baik?" class="px-6 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-500 transition shadow-lg flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Pesanan Diterima
                            </button>
                        @endif

                        @if($order->order_status == 'completed')
                            <span class="text-green-500 font-bold flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Selesai
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        @empty
            <div class="text-center py-20">
                <p class="text-white/50 mb-4">Belum ada riwayat pesanan.</p>
                <a href="{{ route('home') }}" class="text-cream hover:underline">Mulai Belanja</a>
            </div>
        @endforelse

    </div>

    @if($reviewModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-bg-secondary border border-white/10 rounded-2xl p-6 w-full max-w-md shadow-2xl animate-scale-in">
            <h3 class="text-xl font-serif font-bold text-white mb-4">Beri Ulasan Produk</h3>
            
            <form wire:submit.prevent="submitReview">
                <div class="mb-4">
                    <label class="block text-sm text-white/60 mb-2">Rating</label>
                    <div class="flex gap-2">
                        @for($i=1; $i<=5; $i++)
                            <button type="button" wire:click="$set('rating', {{ $i }})" class="text-2xl transition hover:scale-110 {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-600' }}">
                                ★
                            </button>
                        @endfor
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm text-white/60 mb-2">Komentar Anda</label>
                    <textarea wire:model="comment" rows="3" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none" placeholder="Ceritakan rasa kopinya..."></textarea>
                    @error('comment') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" wire:click="$set('reviewModalOpen', false)" class="px-4 py-2 text-white/60 hover:text-white">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-cream text-bg-dark font-bold rounded-lg hover:bg-white transition">Kirim Ulasan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>