<div class="min-h-screen bg-bg-dark">
    
    @if($about)
        <div class="relative h-[60vh] w-full overflow-hidden flex items-center justify-center">
            <div class="absolute inset-0 z-0">
                @if($about->main_image)
                    <img src="{{ asset('storage/' . $about->main_image) }}" class="w-full h-full object-cover opacity-60" alt="About Hero">
                @else
                    <div class="w-full h-full bg-coffee-dark opacity-50"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-b from-bg-dark/30 via-bg-dark/60 to-bg-dark"></div>
            </div>

            <div class="relative z-10 text-center px-4 animate-fade-in-up">
                <span class="text-cream font-bold tracking-[0.3em] uppercase text-sm mb-4 block">Est. 2024 • Gayo Highlands</span>
                <h1 class="text-5xl md:text-7xl font-serif font-bold text-white mb-6">{{ $about->title }}</h1>
                <p class="text-xl text-white/80 font-light italic max-w-2xl mx-auto">"{{ $about->subtitle }}"</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                <div class="prose prose-lg prose-invert text-white/80 text-justify leading-relaxed">
                    <h2 class="text-3xl font-serif font-bold text-cream mb-6">Filosofi Kami</h2>
                    {!! $about->story !!}
                </div>

                <div class="relative">
                    <div class="relative z-10 rounded-3xl overflow-hidden border border-white/10 shadow-2xl rotate-3 hover:rotate-0 transition duration-700">
                        @if($about->main_image)
                            <img src="{{ asset('storage/' . $about->main_image) }}" class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-700">
                        @else
                            <div class="h-96 w-full bg-white/5 flex items-center justify-center text-6xl">☕</div>
                        @endif
                    </div>
                    
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-cream/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-coffee/30 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>

        <div class="bg-bg-secondary py-24 relative overflow-hidden">
            <div class="absolute inset-0 opacity-5" style="background-image: url('https://www.transparenttextures.com/patterns/wood-pattern.png');"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    
                    <div class="p-10 rounded-3xl bg-white/5 border border-white/5 backdrop-blur-sm hover:border-cream/30 transition group">
                        <div class="w-16 h-16 bg-bg-dark rounded-full flex items-center justify-center mb-6 border border-white/10 group-hover:border-cream/50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-cream" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-serif font-bold text-white mb-4">Visi Kami</h3>
                        <p class="text-white/60 leading-relaxed">{{ $about->vision }}</p>
                    </div>

                    <div class="p-10 rounded-3xl bg-white/5 border border-white/5 backdrop-blur-sm hover:border-cream/30 transition group">
                        <div class="w-16 h-16 bg-bg-dark rounded-full flex items-center justify-center mb-6 border border-white/10 group-hover:border-cream/50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-cream" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-serif font-bold text-white mb-4">Misi Kami</h3>
                        <p class="text-white/60 leading-relaxed">{{ $about->mission }}</p>
                    </div>

                </div>
            </div>
        </div>

    @else
        <div class="h-screen flex flex-col items-center justify-center text-center px-4">
            <h1 class="text-4xl font-serif font-bold text-white mb-4">Tentang Kami</h1>
            <p class="text-white/50 mb-8">Halaman ini sedang dalam proses penyeduhan...</p>
            <a href="{{ route('home') }}" class="px-6 py-2 border border-cream text-cream rounded-full hover:bg-cream hover:text-bg-dark transition">Kembali ke Beranda</a>
        </div>
    @endif
</div>