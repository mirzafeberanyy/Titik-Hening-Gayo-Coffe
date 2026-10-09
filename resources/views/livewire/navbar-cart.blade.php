<button @click="$dispatch('open-cart')" class="relative p-2 hover:bg-white/5 rounded-full transition-colors group">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cream group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
    </svg>
    
    @if($count > 0)
        <span class="absolute top-0 right-0 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 ring-2 ring-bg-dark text-[10px] font-bold text-white animate-bounce-slow">
            {{ $count }}
        </span>
    @endif
</button>