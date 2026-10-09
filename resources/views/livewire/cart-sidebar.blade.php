<div 
    x-data="{ open: false }"
    x-show="open"
    @open-cart.window="open = true" 
    @open-cart-sidebar.window="open = true"
    @keydown.escape.window="open = false"
    class="relative z-[60]" 
    aria-labelledby="slide-over-title" 
    role="dialog" 
    aria-modal="true"
    style="display: none;"
>
    <div 
        x-show="open"
        x-transition:enter="ease-in-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-bg-dark/80 backdrop-blur-sm transition-opacity cursor-pointer"
        @click="open = false"
    ></div>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                
                <div 
                    x-show="open"
                    x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-md"
                >
                    <div class="flex h-full flex-col overflow-y-scroll bg-bg-secondary border-l border-coffee/20 shadow-2xl">
                        
                        <div class="flex items-start justify-between px-4 py-6 sm:px-6 bg-bg-dark border-b border-white/5">
                            <h2 class="text-lg font-serif font-bold text-cream" id="slide-over-title">Keranjang Belanja</h2>
                            <div class="ml-3 flex h-7 items-center">
                                <button type="button" @click="open = false" class="relative -m-2 p-2 text-white/40 hover:text-white">
                                    <span class="sr-only">Close panel</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 custom-scrollbar">
                            @if(count($cartItems) > 0)
                                <ul role="list" class="-my-6 divide-y divide-white/5">
                                    @foreach($cartItems as $id => $item)
                                        <li class="flex py-6">
                                            <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-white/10">
                                                @if($item['image'])
                                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover object-center">
                                                @else
                                                    <div class="h-full w-full bg-coffee-dark flex items-center justify-center text-2xl">☕</div>
                                                @endif
                                            </div>

                                            <div class="ml-4 flex flex-1 flex-col">
                                                <div>
                                                    <div class="flex justify-between text-base font-medium text-white">
                                                        <h3><a href="#">{{ $item['name'] }}</a></h3>
                                                        <p class="ml-4">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                                    </div>
                                                    
                                                    <div class="flex flex-col mt-1 text-sm text-white/50">
                                                        <span>{{ $item['category'] }}</span>
                                                        @if(isset($item['variant_name']) && $item['variant_name'] !== '-')
                                                            <span class="text-cream text-xs border border-cream/20 rounded px-1 w-fit mt-1">
                                                                {{ $item['variant_name'] }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="flex flex-1 items-end justify-between text-sm mt-2">
                                                    <div class="flex items-center border border-white/10 rounded-lg">
                                                        <button wire:click="decrement('{{ $id }}')" class="px-3 py-1 text-white hover:bg-white/10 transition">-</button>
                                                        <span class="px-2 text-cream font-bold">{{ $item['quantity'] }}</span>
                                                        <button wire:click="increment('{{ $id }}')" class="px-3 py-1 text-white hover:bg-white/10 transition">+</button>
                                                    </div>

                                                    <button wire:click="remove('{{ $id }}')" type="button" class="font-medium text-red-400 hover:text-red-300 transition">Hapus</button>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-center opacity-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-cream" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <p class="text-lg">Keranjang kosong.</p>
                                    <button @click="open = false" class="mt-4 text-cream hover:underline">Mulai Belanja</button>
                                </div>
                            @endif
                        </div>

                        @if(count($cartItems) > 0)
                            <div class="border-t border-white/10 bg-bg-dark px-4 py-6 sm:px-6">
                                <div class="flex justify-between text-base font-medium text-white">
                                    <p>Subtotal</p>
                                    <p>Rp {{ number_format($total, 0, ',', '.') }}</p>
                                </div>
                                <p class="mt-0.5 text-sm text-white/50 mb-6">Ongkir dihitung saat checkout.</p>
                                @auth
                                    <a href="{{ route('checkout') }}" class="flex items-center justify-center rounded-full bg-gradient-to-r from-cream to-coffee-light px-6 py-3 text-base font-bold text-bg-dark shadow-sm hover:scale-[1.02] transition-transform">
                                        Checkout Sekarang
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="flex items-center justify-center rounded-full border border-cream/50 px-6 py-3 text-base font-bold text-cream hover:bg-cream hover:text-bg-dark transition">
                                        Login untuk Checkout
                                    </a>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>