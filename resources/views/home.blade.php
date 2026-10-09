<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titik Hening </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
</head>
<body class="bg-bg-dark text-text-light font-sans antialiased selection:bg-cream selection:text-bg-dark"
      x-data="{ 
          mobileMenuOpen: false, 
          scrolled: false,
          cartOpen: false 
      }"
      @scroll.window="scrolled = (window.pageYOffset > 50)">

    <nav :class="{ 'bg-bg-dark/95 backdrop-blur-md shadow-lg': scrolled, 'bg-transparent': !scrolled }" 
         class="fixed top-0 w-full z-50 transition-all duration-300 border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <a href="#" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cream to-coffee flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform">
                        <span class="text-xl"></span>
                    </div>
                    <span class="text-2xl font-serif font-bold text-cream tracking-wide">Titik Hening</span>
                </a>

                <div class="hidden md:flex space-x-8">
                    <a href="#" class="text-sm uppercase tracking-widest hover:text-cream transition-colors">Beranda</a>
                    <a href="#products" class="text-sm uppercase tracking-widest hover:text-cream transition-colors">Produk</a>
                    <a href="#brand" class="text-sm uppercase tracking-widest hover:text-cream transition-colors">Tentang</a>
                    <a href="#blog" class="text-sm uppercase tracking-widest hover:text-cream transition-colors">
                        Artikel
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    <livewire:navbar-cart />

                    @auth
                        
                        @if(Auth::user()->role === 'customer')
                            <div class="relative hidden md:block" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-cream hover:text-white transition focus:outline-none">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-56 bg-bg-secondary border border-white/10 rounded-xl shadow-xl py-2 z-50 origin-top-right" 
                                     style="display: none;">
                                    
                                    <a href="{{ route('my-profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-white hover:bg-white/5 hover:text-cream transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profil 
                                    </a>

                                    <a href="{{ route('my-orders') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-white hover:bg-white/5 hover:text-cream transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        Pesanan Saya
                                    </a>

                                    <div class="border-t border-white/10 my-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 transition text-left">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>

                        @else
                            <div class="hidden md:flex items-center gap-4">
                                <span class="px-3 py-1 rounded bg-red-500/20 text-red-400 text-xs font-bold uppercase border border-red-500/30">
                                    {{ str_replace('_', ' ', Auth::user()->role) }}
                                </span>
                                <a href="/admin" class="text-sm font-bold text-cream hover:text-white underline">
                                    Dashboard
                                </a>
                            </div>
                        @endif

                    @else
                        <div class="hidden md:flex items-center gap-3">
                            <a href="{{ route('login') }}" class="text-sm font-bold text-cream hover:text-white transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-5 py-2 rounded-full bg-white/10 border border-white/5 text-sm font-bold text-white hover:bg-white/20 transition">
                                Daftar
                            </a>
                        </div>
                    @endauth

                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-cream">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div x-show="mobileMenuOpen" 
         x-transition
         class="fixed inset-0 z-40 bg-bg-dark/98 backdrop-blur-xl pt-24 px-8 md:hidden"
         style="display: none;">
        
        <div class="flex flex-col gap-6 text-center">
            <a @click="mobileMenuOpen = false" href="#hero" class="text-xl font-serif text-cream hover:text-white">Beranda</a>
            <a @click="mobileMenuOpen = false" href="#products" class="text-xl font-serif text-cream hover:text-white">Produk</a>
            
            <hr class="border-white/10 w-1/2 mx-auto">

            @auth
                @if(Auth::user()->role === 'customer')
                    <a href="{{ route('my-orders') }}" class="text-xl font-bold text-white">Pesanan Saya</a>
                @else
                    <span class="text-sm text-red-400 uppercase font-bold">{{ Auth::user()->role }}</span>
                    <a href="/admin" class="text-xl font-bold text-cream">Ke Dashboard Admin</a>
                @endif
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xl font-bold text-red-400 mt-4">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-xl font-bold text-white">Masuk</a>
                <a href="{{ route('register') }}" class="text-xl font-bold text-cream border border-cream/30 py-2 rounded-xl">Daftar Sekarang</a>
            @endauth

            <button @click="mobileMenuOpen = false" class="absolute top-6 right-6 p-2 text-white/50 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-5"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-5"
         class="fixed inset-0 z-40 bg-bg-dark/98 backdrop-blur-xl pt-32 px-8 md:hidden">
        <div class="flex flex-col gap-8 text-center">
            <a @click="mobileMenuOpen = false" href="#hero" class="text-2xl font-serif text-cream hover:text-white">Beranda</a>
            <a @click="mobileMenuOpen = false" href="#products" class="text-2xl font-serif text-cream hover:text-white">Koleksi</a>
            <a @click="mobileMenuOpen = false" href="#brand" class="text-2xl font-serif text-cream hover:text-white">Tentang Kami</a>
            <button @click="mobileMenuOpen = false" class="absolute top-8 right-8 p-2 text-white/50 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div id="hero" class="relative h-screen w-full overflow-hidden">

        <div class="absolute inset-0 w-full h-full z-0">
            @if($hero && $hero->bg_image)
                <img src="{{ asset('storage/' . $hero->bg_image) }}" class="w-full h-full object-cover" alt="Hero Background">
            @else
                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085" class="w-full h-full object-cover" alt="Default Hero">
            @endif
            <div class="absolute inset-0 bg-black/50"></div>
        </div>

        <div class="fixed top-0 left-0 w-full h-screen flex items-center z-0 pointer-events-none">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pointer-events-auto"> <div class="max-w-3xl text-left animate-fade-in">
                    <div class="inline-block mb-6 animate-bounce-slow">
                        <span class="px-4 py-1.5 rounded-full border border-cream/30 bg-cream/10 text-cream text-xs font-bold tracking-widest uppercase backdrop-blur-sm">
                            Est. 2024 • Gayo Highlands
                        </span>
                    </div>

                    <h1 class="text-5xl md:text-7xl lg:text-8xl font-serif font-bold text-white leading-tight mb-6 drop-shadow-2xl">
                        {{ $hero->title ?? 'Judul Default' }}
                    </h1>
                    
                    <p class="text-xl text-white/90 mb-10 leading-relaxed max-w-2xl drop-shadow-lg">
                        {{ $hero->subtitle ?? 'Deskripsi default website Anda.' }}
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-start">
                        <a href="#products" class="px-8 py-4 bg-gradient-to-r from-cream to-coffee-light text-bg-dark font-bold rounded-full hover:scale-105 transition shadow-lg text-center">
                            Jelajahi Kopi
                        </a>
                        <a href="#brand" class="px-8 py-4 border-2 border-white/30 text-white font-bold rounded-full hover:bg-white hover:text-bg-dark transition text-center backdrop-blur-sm">
                            Tentang Kami
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <section class="relative z-10 bg-bg-secondary border-t border-white/5 shadow-[0_-50px_100px_rgba(0,0,0,0.9)] overflow-hidden">
        
        <div class="bg-cream py-2 w-full overflow-hidden border-b-4 border-bg-dark mb-16">
            <div class="animate-marquee whitespace-nowrap flex gap-12 text-bg-dark font-black tracking-[0.2em] uppercase text-sm md:text-base">
                <span>Authentic Gayo Coffee</span> • <span>Single Origin</span> • <span>100% Arabica</span> • <span>Direct Trade</span> • 
                <span>Fresh Roasted</span> • <span>Diracik untuk Tenang</span> • <span>Fresh Roasted</span> • <span>Direct Trade</span> •
                <span>Pengiriman Aman</span> • <span>Small Batch Roastery</span> • <span>Pengiriman Aman</span> • <span>Direct Trade</span> •
                <span>Asli Dari Petani</span> • <span>Proses Transparan</span> • <span>Diracik untuk Tenang</span> • <span>Direct Trade</span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">Brand Feature</h2>
                <p class="text-cream text-lg max-w-2xl mx-auto">Kopi Gayo ciri khas kopi dengan rasa otentik khas pegunungan Aceh</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                
                @forelse($features as $feature)
                    <div class="group relative p-8 rounded-3xl bg-white/5 border border-white/5 hover:border-cream/30 transition-all duration-500 hover:-translate-y-2 hover:bg-white/10">
                        
                        <div class="relative w-28 h-28 mx-auto mb-8 flex items-center justify-center bg-bg-dark rounded-full border-2 border-white/10 group-hover:border-cream transition-colors shadow-xl overflow-hidden">
                            @if($feature->icon)
                                <img src="{{ asset('storage/' . $feature->icon) }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90 group-hover:opacity-100" 
                                     alt="{{ $feature->title }}">
                            @else
                                <span class="text-4xl"></span>
                            @endif
                        </div>

                        <h3 class="text-xl font-serif font-bold text-white mb-3 group-hover:text-cream transition-colors">
                            {{ $feature->title }}
                        </h3>
                        <p class="text-white/60 leading-relaxed text-sm group-hover:text-white/80 transition-colors px-4">
                            {{ $feature->description }}
                        </p>
                    </div>
                @empty
                    <div class="col-span-3 text-white/30 text-center py-10">
                        Belum ada fitur yang ditambahkan.
                    </div>
                @endforelse

            </div>
        </div>
    </section>

    <section id="products" class="relative py-32 bg-bg-dark coffee-bean-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">Produk Unggulan</h2>
                <p class="text-cream text-lg max-w-2xl mx-auto">Pilihan biji kopi terbaik yang siap menemani momen hening Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($products as $product)
                    <div x-data="{ showVariant: false }" @mouseleave="showVariant = false" class="group relative bg-bg-secondary rounded-3xl overflow-hidden card-shadow hover-lift border border-white/5">
                        
                        <div class="h-72 bg-coffee-dark/20 relative overflow-hidden flex items-center justify-center">
                            
                            <a href="{{ route('product.detail', $product->slug) }}" class="absolute inset-0 z-0">
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-6xl opacity-50">☕</div>
                                @endif
                            </a>
                            
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4 z-10" x-show="!showVariant">
                                
                                <button @click.prevent="showVariant = true" class="p-3 bg-cream text-bg-dark rounded-full hover:scale-110 transition-transform shadow-lg" title="Beli Cepat">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </button>
                                
                                <button 
                                    @click.prevent="$dispatch('open-product-modal', { slug: '{{ $product->slug }}' })"
                                    class="p-3 bg-white text-bg-dark rounded-full hover:scale-110 transition-transform cursor-pointer shadow-lg" 
                                    title="Lihat Detail (Quick View)"
                                >
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
                    <div class="col-span-3 text-center py-20">
                        <p class="text-white/50 text-xl">Belum ada produk.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-16 text-center">
                <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-8 py-3 border border-cream text-cream font-bold rounded-full hover:bg-cream hover:text-bg-dark transition">
                    Lihat Semua Produk
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <section id="brand" class="py-32 bg-bg-secondary relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                
                <div class="relative animate-fade-in-left">
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-cream/10 rounded-full blur-3xl"></div>

                    
                    <h2 class="text-4xl md:text-6xl font-serif font-bold text-white mb-8 leading-tight">
                        @if($about)
                            {!! str_replace(' ', ' <span class="text-gradient">', Str::before($about->title, ' ')) !!} 
                            <span class="text-gradient">{{ Str::after($about->title, ' ') }}</span>
                        @else
                            Warisan <span class="text-gradient">Dataran Tinggi</span> Aceh
                        @endif
                    </h2>

                    <div class="space-y-6 text-white/70 text-lg font-light leading-relaxed">
                        @if($about)
                            <p class="italic text-cream/80">"{{ $about->subtitle }}"</p>
                            
                            <p>
                                {{ Str::limit(strip_tags($about->story), 250) }}
                            </p>
                            
                            <a href="{{ route('about') }}" class="inline-block mt-4 text-cream font-bold hover:underline">
                                Baca Cerita Lengkap &rarr;
                            </a>
                        @else
                            <p>Titik Hening lahir dari kecintaan mendalam terhadap kopi Gayo. Kami percaya bahwa kopi bukan sekadar minuman, melainkan ritual ketenangan.</p>
                        @endif
                    </div>

                    <div class="mt-10">
                        @if($about && $about->main_image)
                            <img src="{{ asset('storage/' . $about->main_image) }}" class="rounded-2xl opacity-90 hover:opacity-100 transition-opacity border border-white/10 card-shadow object-cover h-64 w-full" alt="About Image">
                        @else
                            <img src="https://images.unsplash.com/photo-1667663669486-9849a27f3988?q=80&w=800&auto=format&fit=crop" class="rounded-2xl opacity-80 hover:opacity-100 transition-opacity border border-white/10 card-shadow" alt="Default Image">
                        @endif
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-8 animate-fade-in-right">
                    
                    <div class="bg-bg-dark p-8 rounded-3xl border border-white/5 card-shadow hover:border-cream/30 transition group relative overflow-hidden">
                        
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-cream/10 rounded-full blur-3xl group-hover:bg-cream/20 transition duration-700"></div>

                        <div class="w-16 h-16 mb-6 rounded-2xl bg-gradient-to-br from-white/10 to-white/5 border border-white/10 flex items-center justify-center shadow-[0_0_15px_rgba(212,165,116,0.1)] group-hover:shadow-[0_0_25px_rgba(212,165,116,0.3)] transition duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-cream group-hover:scale-110 transition duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>

                        <h3 class="text-xl font-serif font-bold text-white mb-2 relative z-10 group-hover:text-cream transition-colors">Visi Kami</h3>
                        <p class="text-white/50 leading-relaxed relative z-10 text-sm">
                            {{ $about->vision ?? 'Menjadi representasi kopi Gayo terbaik yang dikenal dunia karena kualitas dan keasliannya.' }}
                        </p>
                    </div>

                    <div class="bg-bg-dark p-8 rounded-3xl border border-white/5 card-shadow translate-x-4 md:translate-x-8 hover:border-cream/30 transition group relative overflow-hidden">
                        
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-coffee/20 rounded-full blur-3xl group-hover:bg-coffee/40 transition duration-700"></div>

                        <div class="w-16 h-16 mb-6 rounded-2xl bg-gradient-to-br from-white/10 to-white/5 border border-white/10 flex items-center justify-center shadow-[0_0_15px_rgba(212,165,116,0.1)] group-hover:shadow-[0_0_25px_rgba(212,165,116,0.3)] transition duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-cream group-hover:scale-110 transition duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <h3 class="text-xl font-serif font-bold text-white mb-2 relative z-10 group-hover:text-cream transition-colors">Misi Kami</h3>
                        <p class="text-white/50 leading-relaxed relative z-10 text-sm">
                            {{ $about->mission ?? 'Memberdayakan petani lokal Takengon dan menyajikan ketenangan dalam setiap cangkir.' }}
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="blog" class="py-24 bg-bg-dark border-t border-white/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-cream/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div>
                    <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">Artikel Gayo</h2>
                    <p class="text-white/60 text-lg max-w-lg">
                        Telusuri cerita, tradisi, dan inspirasi di balik setiap biji kopi yang kami sajikan.
                    </p>
                </div>
                
                <a href="{{ route('stories') }}" class="group flex items-center gap-2 text-cream font-bold hover:text-white transition border-b border-cream/30 hover:border-white pb-1">
                    Lihat Semua Artikel
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($articles as $article)
                    <a href="{{ route('stories.show', $article->slug) }}" class="group block bg-bg-secondary rounded-2xl overflow-hidden border border-white/5 hover:border-cream/30 transition-all duration-500 hover:-translate-y-2">
                        
                        <div class="aspect-w-16 aspect-h-10 h-56 overflow-hidden relative">
                            @if($article->thumbnail)
                                <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @else
                                <div class="w-full h-full bg-coffee-dark flex items-center justify-center text-4xl">📰</div>
                            @endif
                            
                            <div class="absolute bottom-0 left-0 bg-bg-dark/90 px-4 py-2 text-xs text-cream font-bold uppercase tracking-widest border-tr-xl">
                                {{ $article->published_at->format('d M Y') }}
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-xl font-serif font-bold text-white mb-3 group-hover:text-cream transition leading-tight">
                                {{ $article->title }}
                            </h3>
                            <p class="text-white/50 text-sm line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($article->content), 100) }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 text-center py-12 border border-dashed border-white/10 rounded-xl">
                        <p class="text-white/30">Belum ada cerita terbaru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <footer class="bg-bg-dark border-t border-white/5 pt-20 pb-10 mt-auto relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                
                <div class="space-y-6">
                    <a href="#" class="text-3xl font-serif font-bold text-white block items-center gap-2">
                        Titik Hening.
                    </a>
                    <p class="text-white/50 leading-relaxed text-sm">
                        Menemani momen hening Anda dengan secangkir kopi Gayo terbaik. Asli, Premium, dan Penuh Cerita.
                    </p>
                    
                    
                </div>
            
                <div class="lg:pl-12"> 
                    <h4 class="text-cream font-bold uppercase tracking-widest mb-6 text-sm">Jelajahi</h4>
                    <ul class="space-y-3 text-white/60 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2">Beranda</a></li>
                        <li><a href="#products" class="hover:text-white transition-colors flex items-center gap-2">Koleksi Kopi</a></li>
                        <li><a href="#brand" class="hover:text-white transition-colors flex items-center gap-2">Tentang Kami</a></li>
                        <li><a href="#blog" class="hover:text-white transition-colors flex items-center gap-2">Artikel & Cerita</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-cream font-bold uppercase tracking-widest mb-6 text-sm">Bantuan</h4>
                    <ul class="space-y-3 text-white/60 text-sm">
                        <li><a href="{{ route('faq') }}" class="hover:text-white transition-colors">Pertanyaan Umum (FAQ)</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('refund') }}" class="hover:text-white transition-colors">Kebijakan Refund</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-cream font-bold uppercase tracking-widest mb-6 text-sm">Hubungi Kami</h4>
                    <ul class="space-y-4 text-white/60 text-sm">
                        <li class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cream mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Takengon, Aceh Tengah<br>Indonesia, 24511</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cream flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>thstore@titikhening.com</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cream flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>+62 812 3456 7890</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-center items-center gap-4 text-sm text-white/30">
                <div>
                    &copy; {{ date('Y') }} Titik Hening. All rights reserved.
                </div>
            </div>
        </div>
    </footer>
    
    <livewire:cart-sidebar />
    <livewire:product-detail-modal />
    <livewire:chat-widget />

</body>
</html>