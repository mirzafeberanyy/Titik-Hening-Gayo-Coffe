<div class="min-h-screen bg-bg-dark pt-32 pb-20">
    
    <div class="fixed top-28 left-4 z-40 hidden xl:block">
        <a href="{{ route('stories') }}" class="flex items-center justify-center w-12 h-12 rounded-full bg-white/5 hover:bg-cream text-white hover:text-bg-dark transition border border-white/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10 animate-fade-in">
            <div class="flex items-center justify-center gap-3 text-sm text-cream/80 mb-6 uppercase tracking-widest font-bold">
                <span>{{ $article->published_at->format('d F Y') }}</span>
                <span class="w-1 h-1 rounded-full bg-white/30"></span>
                <span>Oleh {{ $article->author->name ?? 'Tim Redaksi' }}</span>
            </div>
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-serif font-bold text-white leading-tight mb-8">
                {{ $article->title }}
            </h1>
        </div>

        <div class="aspect-video w-full rounded-3xl overflow-hidden shadow-2xl border border-white/10 mb-12">
            @if($article->thumbnail)
                <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-full h-full object-cover">
            @endif
        </div>

        <div class="prose prose-lg prose-invert max-w-none text-white/80 leading-loose mb-16 selection:bg-cream selection:text-bg-dark">
            {!! $article->content !!}
        </div>

        @if($article->images->count() > 0)
            <div class="border-t border-white/10 pt-12">
                <h3 class="text-2xl font-serif font-bold text-cream mb-8 text-center">Galeri Visual</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($article->images as $img)
                        <div class="group relative rounded-2xl overflow-hidden border border-white/5 aspect-w-4 aspect-h-3">
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                            @if($img->caption)
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 translate-y-full group-hover:translate-y-0 transition duration-300">
                                    <p class="text-sm text-white text-center">{{ $img->caption }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-16 pt-8 border-t border-white/10 text-center">
            <a href="{{ route('stories') }}" class="inline-flex items-center gap-2 text-cream font-bold hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Cerita
            </a>
        </div>

    </div>
</div>