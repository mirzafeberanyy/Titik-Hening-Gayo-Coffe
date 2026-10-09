<div x-data="{ show: @entangle('isOpen') }"
     x-show="show"
     @keydown.escape.window="show = false"
     class="relative z-[70]" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     style="display: none;">

    <div x-show="show"
         x-transition.opacity.duration.300ms
         class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity"
         @click="show = false"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-bg-secondary border border-white/10 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">

                <button @click="show = false" class="absolute top-4 right-4 text-white/50 hover:text-white z-20">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                @if($product)
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="bg-coffee-dark/20 relative h-64 md:h-auto flex items-center justify-center">
                        @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-6xl"></span>
                        @endif
                    </div>

                    <div class="p-8 md:p-10 flex flex-col justify-center">
                        <h2 class="text-3xl font-serif font-bold text-white mb-2">{{ $product->name }}</h2>

                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-yellow-400 text-sm flex gap-1">
                                ★ {{ $product->rating }}
                            </span>
                            <span class="text-white/40 text-xs">({{ $product->review_count }} Ulasan)</span>
                        </div>

                        <p class="text-2xl font-bold text-cream mb-2">
                                Rp {{ number_format($price, 0, ',', '.') }}
                            </p>
                            
                            <div class="mb-6">
                                @if($stock > 0)
                                    <span class="text-xs {{ $stock <= 5 ? 'text-red-400' : 'text-green-400' }}">
                                        Tersedia: {{ $stock }} pcs
                                    </span>
                                @else
                                    <span class="text-xs text-red-500 font-bold">Stok Habis</span>
                                @endif
                            </div>       

                        <div class="prose prose-sm prose-invert text-white/70 mb-6 line-clamp-4">
                            {!! $product->description !!}
                        </div>

                        @if($product->variants->count() > 0)
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-white/60 mb-2 uppercase tracking-wider">Pilih Varian</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($product->variants as $variant)
                                        <button 
                                            wire:click="$set('selectedVariantId', {{ $variant->id }})"
                                            class="px-4 py-2 rounded-lg text-sm border transition {{ $selectedVariantId == $variant->id ? 'bg-cream text-bg-dark border-cream font-bold' : 'bg-transparent text-white border-white/20 hover:border-white' }}"
                                        >
                                            {{ $variant->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <button 
                                wire:click="addToCart" 
                                @if($stock == 0) disabled @endif
                                class="w-full font-bold py-3 rounded-xl transition shadow-lg
                                {{ $stock > 0 
                                    ? 'bg-gradient-to-r from-cream to-coffee-light text-bg-dark hover:scale-[1.02]' 
                                    : 'bg-gray-600 text-gray-400 cursor-not-allowed' 
                                }}"
                            >
                                {{ $stock > 0 ? 'Masukkan Keranjang' : 'Stok Habis' }}
                            </button>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>