<div class="min-h-screen bg-bg-dark pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <div class="aspect-square rounded-3xl overflow-hidden border border-white/10 bg-white/5">
                @if($product->main_image)
                    <img src="{{ asset('storage/' . $product->main_image) }}" class="w-full h-full object-cover">
                @else
                    <div class="flex items-center justify-center h-full text-6xl">☕</div>
                @endif
            </div>

            <div class="text-white">
                <h1 class="text-4xl font-serif font-bold mb-4">{{ $product->name }}</h1>

                <div class="flex items-center gap-2 mb-6">
                    <div class="flex text-yellow-400">
                        @for($i=1; $i<=5; $i++)
                            <span>{{ $i <= $product->rating ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                    <span class="text-white/50 text-sm">({{ $product->review_count }} Ulasan)</span>
                </div>

                <div class="mb-8">
                        <div class="flex items-end gap-4 mb-2">
                            <div class="text-3xl font-bold text-cream">
                                Rp {{ number_format($price, 0, ',', '.') }}
                            </div>
                            
                            @if($stock > 0)
                                <div class="flex items-center gap-2 text-sm {{ $stock <= 5 ? 'text-red-400 animate-pulse' : 'text-green-400' }} border border-white/10 px-3 py-1 rounded-full bg-white/5">
                                    <span class="w-2 h-2 rounded-full {{ $stock <= 5 ? 'bg-red-500' : 'bg-green-500' }}"></span>
                                    <span>Stok: <strong>{{ $stock }}</strong> pack</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-sm text-red-500 border border-red-500/30 px-3 py-1 rounded-full bg-red-500/10">
                                    <span class="font-bold">Stok Habis</span>
                                </div>
                            @endif
                        </div>
                    </div>

                <div class="prose prose-invert mb-8 text-white/70">
                    {!! $product->description !!}
                </div>

                @if($product->variants->count() > 0)
                    <div class="mb-8">
                        <label class="block text-sm font-bold mb-3">Pilih Varian</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->variants as $variant)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="selectedVariantId" value="{{ $variant->id }}" class="peer sr-only">
                                    <div class="px-4 py-2 rounded-lg border border-white/20 bg-white/5 peer-checked:bg-cream peer-checked:text-bg-dark peer-checked:border-cream transition hover:border-white/50">
                                        {{ $variant->name }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (session()->has('error'))
                        <div class="mb-4 p-3 bg-red-500/20 border border-red-500/50 text-red-200 text-sm rounded-lg flex items-center gap-2 animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex gap-4">
                        <button wire:click="addToCart" ...>

                <div class="flex gap-4 mt-8">
                        <button 
                            wire:click="addToCart" 
                            @if($stock == 0) disabled @endif
                            class="flex-1 font-bold py-4 rounded-xl transition shadow-lg flex items-center justify-center gap-2
                            {{ $stock > 0 
                                ? 'bg-gradient-to-r from-cream to-coffee-light text-bg-dark hover:scale-[1.02]' 
                                : 'bg-gray-600 text-gray-400 cursor-not-allowed opacity-50' 
                            }}"
                        >
                            @if($stock > 0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Tambah ke Keranjang
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Stok Habis
                            @endif
                        </button>
                    </div>
            </div>
        </div>

        <div class="border-t border-white/10 pt-12">
            <h3 class="text-2xl font-serif font-bold text-white mb-8">Ulasan Pelanggan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($product->reviews->where('is_visible', true) as $review)
                    <div class="bg-bg-secondary p-6 rounded-xl border border-white/5">
                        <div class="flex justify-between mb-2">
                            <span class="font-bold text-white">{{ $review->user->name }}</span>
                            <span class="text-yellow-400 text-sm">{{ str_repeat('★', $review->rating) }}</span>
                        </div>
                        <p class="text-white/60 text-sm">"{{ $review->comment }}"</p>
                    </div>
                @empty
                    <p class="text-white/30">Belum ada ulasan untuk produk ini.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>