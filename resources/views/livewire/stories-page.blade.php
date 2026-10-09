<div class="min-h-screen bg-bg-dark pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-cream mb-6">Artikel Gayo</h1>
            <p class="text-white/60 text-lg max-w-2xl mx-auto leading-relaxed">
                Menelusuri jejak budaya, tradisi, dan kearifan lokal dari Dataran Tinggi Gayo yang tersimpan dalam setiap biji kopi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($articles as $article)
                <a href="{{ route('stories.show', $article->slug) }}" class="group block bg-bg-secondary rounded-2xl overflow-hidden border border-white/5 hover:border-cream/30 transition-all duration-500 hover:-translate-y-2 shadow-lg">
                    
                    <div class="aspect-w-16 aspect-h-10 h-56 overflow-hidden relative">
                        @if($article->thumbnail)
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        @else
                            <div class="w-full h-full bg-coffee-dark flex items-center justify-center">
                                <span class="text-4xl">📰</span>
                            </div>
                        @endif
                        
                        <div class="absolute top-4 left-4 bg-bg-dark/80 backdrop-blur px-3 py-1 rounded-lg text-xs text-cream font-bold uppercase tracking-wider border border-white/10">
                            {{ $article->published_at->format('d M Y') }}
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-serif font-bold text-white mb-3 group-hover:text-cream transition leading-tight">
                            {{ $article->title }}
                        </h3>
                        <p class="text-white/50 text-sm line-clamp-3 leading-relaxed mb-4">
                            {{ Str::limit(strip_tags($article->content), 120) }}
                        </p>
                        
                        <div class="text-cream text-sm font-bold flex items-center gap-2 group/link">
                            Baca Selengkapnya 
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover/link:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-20 border border-dashed border-white/10 rounded-3xl">
                    <p class="text-white/30 text-lg">Belum ada cerita yang ditulis.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $articles->links() }}
        </div>
    </div>
</div>