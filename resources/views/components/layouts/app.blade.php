<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Titik Hening' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-bg-dark text-text-light font-sans antialiased selection:bg-cream selection:text-bg-dark"
      x-data="{ 
          mobileMenuOpen: false, 
          scrolled: false,
          cartOpen: false 
      }"
      @scroll.window="scrolled = (window.pageYOffset > 50)">

    <nav :class="{ 'bg-bg-dark/95 backdrop-blur-md shadow-lg': scrolled, 'bg-transparent': !scrolled }" 
         class="fixed top-0 w-full z-50 transition-all duration-300 border-b border-white/5 bg-bg-dark/90">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cream to-coffee flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform">
                        <span class="text-xl"></span>
                    </div>
                    <span class="text-2xl font-serif font-bold text-cream tracking-wide">Titik Hening</span>
                </a>

                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}#hero" class="text-sm uppercase tracking-widest hover:text-cream transition-colors">Beranda</a>
                    <a href="{{ route('home') }}#products" class="text-sm uppercase tracking-widest hover:text-cream transition-colors">Produk</a>
                    <a href="{{ route('home') }}#brand" class="text-sm uppercase tracking-widest hover:text-cream transition-colors">Tentang</a>
                    <a href="{{ route('home') }}#blog" class="text-sm uppercase tracking-widest hover:text-cream transition-colors">
                        Artikel
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    <livewire:navbar-cart />

                    @auth
                        @if(Auth::user()->role === 'customer')
                            
                            <div class="relative hidden md:block" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 text-sm font-medium text-cream hover:text-white transition focus:outline-none">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 rounded bg-red-500/20 text-red-400 text-xs font-bold uppercase border border-red-500/30">
                                    {{ str_replace('_', ' ', Auth::user()->role) }}
                                </span>
                                <a href="/admin" class="text-sm font-bold text-cream hover:text-white underline decoration-cream/30 underline-offset-4">
                                    Dashboard
                                </a>
                            </div>
                        @endif

                    @else
                        <div class="hidden md:flex items-center gap-3">
                            <a href="{{ route('login') }}" class="text-sm font-bold text-cream hover:text-white transition flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-5 py-2 rounded-full bg-white/10 border border-white/5 text-sm font-bold text-white hover:bg-white/20 transition">Daftar</a>
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
            <a @click="mobileMenuOpen = false" href="{{ route('home') }}" class="text-xl font-serif text-cream hover:text-white">Beranda</a>
            <a @click="mobileMenuOpen = false" href="{{ route('home') }}#products" class="text-xl font-serif text-cream hover:text-white">Produk</a>
            
            <hr class="border-white/10 w-1/2 mx-auto">

            @auth
                <a href="{{ route('my-profile') }}" class="flex items-center justify-center gap-2 text-xl font-bold text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil 
                </a>
                <a href="{{ route('my-orders') }}" class="flex items-center justify-center gap-2 text-xl font-bold text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Pesanan Saya
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full text-xl font-bold text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
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

    <main>
        {{ $slot }}
    </main>

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
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors flex items-center gap-2">Beranda</a></li>
                        <li><a href="{{ route('home') }}#products" class="hover:text-white transition-colors flex items-center gap-2">Koleksi Kopi</a></li>
                        <li><a href="{{ route('home') }}#brand" class="hover:text-white transition-colors flex items-center gap-2">Tentang Kami</a></li>
                        <li><a href="{{ route('home') }}#blog" class="hover:text-white transition-colors flex items-center gap-2">Artikel & Cerita</a></li>
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

    <script>
        document.addEventListener('livewire:init', () => {
        Livewire.on('swal', (event) => {
            const data = event[0];
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                timer: data.timer || 3000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timerProgressBar: true,
                background: '#1a1a1a', // Warna gelap biar senada sama Titik Hening
                color: '#fff'
            });
        });
        });
    </script>

</body>
</html>