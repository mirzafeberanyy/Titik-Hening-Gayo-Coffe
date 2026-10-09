<div class="min-h-screen bg-bg-dark pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-cream mb-4">Katalog Lengkap</h1>
            <p class="text-white/60 text-lg max-w-2xl mx-auto">
                Temukan biji kopi pilihan terbaik dari dataran tinggi Gayo.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($products as $product)
                <div x-data="{ showVariant: false }" @mouseleave="showVariant = false" class="group relative bg-bg-secondary rounded-3xl overflow-hidden card-shadow hover-lift border border-white/5">
                    
                    <div class="h-72 bg-coffee-dark/20 relative overflow-hidden flex items-center justify-center">
                        
                        <a href="{{ route('product.detail', $product->slug) }}" class="absolute inset-0 z-0">
                            @if($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-6xl opacity-50"></div>
                            @endif
                        </a>
                        
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4 z-10" x-show="!showVariant">
                            
                            <button @click.prevent="showVariant = true" class="p-3 bg-cream text-bg-dark rounded-full hover:scale-110 transition-transform shadow-lg cursor-pointer" title="Beli Cepat">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </button>
                            
                            <button @click.prevent="$dispatch('open-product-modal', { slug: '{{ $product->slug }}' })" class="p-3 bg-white text-bg-dark rounded-full hover:scale-110 transition-transform shadow-lg cursor-pointer" title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>

                        <div 
                            x-show="showVariant" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-10"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-10"
                            class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-bg-dark/95 p-4 text-center"
                            style="display: none;"
                        >
                            <p class="mb-3 text-sm font-bold uppercase tracking-widest text-cream flex-shrink-0">Pilih Varian</p>
                            
                            <div class="w-full max-h-[180px] overflow-y-auto px-2 custom-scrollbar">
                                <div class="flex flex-wrap justify-center gap-2 pb-2">
                                    @foreach($product->variants as $variant)
                                        @php 
                                            $isHabis = $variant->stock <= 0; 
                                        @endphp

                                        <button 
                                            type="button" 
                                            @click="$dispatch('add-to-cart', { productId: {{ $product->id }}, variantId: {{ $variant->id }} }); showVariant = false;"
                                            {{ $isHabis ? 'disabled' : '' }}
                                            class="relative rounded-lg border px-3 py-2 text-xs transition-all w-full sm:w-auto flex-shrink-0
                                            {{ $isHabis 
                                                ? 'border-gray-700 bg-gray-800 text-gray-500 cursor-not-allowed opacity-80' /* Style HABIS: Solid Abu Gelap */
                                                : 'border-white/20 bg-transparent text-white hover:bg-cream hover:text-bg-dark hover:border-cream cursor-pointer' /* Style ADA */
                                            }}"
                                        >
                                            <span class="{{ $isHabis ? 'line-through decoration-gray-500' : '' }}">
                                                {{ $variant->name }}
                                            </span>
                                            
                                            <div class="font-bold mt-0.5 {{ $isHabis ? 'opacity-50' : '' }}">
                                                Rp {{ number_format($variant->price/1000, 0) }}k
                                            </div>

                                            @if($isHabis)
                                                <div class="absolute inset-0 flex items-center justify-center overflow-hidden rounded-lg z-10">
                                                    <span class="bg-red-900/90 text-red-200 text-[10px] font-bold px-3 py-0.5 border border-red-500/30 transform -rotate-6 shadow-sm backdrop-blur-sm">
                                                        HABIS
                                                    </span>
                                                </div>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <button @click="showVariant = false" class="mt-4 text-xs text-white/50 hover:text-white underline decoration-white/30 underline-offset-4 transition flex-shrink-0">
                                Batal
                            </button>
                        </div>

                    </div>

                    <div class="p-8">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="text-xs text-cream font-bold uppercase tracking-wider mb-1">
                                    {{ $product->category->name ?? 'Origin' }}
                                </p>
                                <a href="{{ route('product.detail', $product->slug) }}">
                                    <h3 class="text-xl font-serif font-bold text-white group-hover:text-cream transition-colors line-clamp-1">
                                        {{ $product->name }}
                                    </h3>
                                </a>
                            </div>
                            <div class="flex items-center gap-1 text-yellow-500 text-sm bg-white/5 px-2 py-1 rounded-lg">
                                <span>★</span>
                                <span class="font-bold text-cream">{{ $product->rating > 0 ? $product->rating : '-' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center mt-6 pt-6 border-t border-white/10">
                            <div class="flex flex-col">
                                <span class="text-xs text-white/40 mb-1">Mulai dari</span>
                                <span class="text-2xl font-bold text-white">
                                    Rp {{ number_format($product->variants->min('price') ?? $product->base_price, 0, ',', '.') }}
                                </span>
                            </div>
                            <span class="text-xs bg-white/10 px-2 py-1 rounded text-white/60">
                                {{ $product->variants_count ?? 0 }} Varian
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-20 border border-dashed border-white/10 rounded-3xl">
                    <p class="text-white/50 text-xl">Produk tidak ditemukan.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $products->links() }}
        </div>
    </div>

    <livewire:product-detail-modal />
    <livewire:cart-sidebar />
</div>