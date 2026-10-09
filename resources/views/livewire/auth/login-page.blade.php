<div class="min-h-screen bg-bg-dark flex items-center justify-center py-20 px-4">
    <div class="max-w-md w-full bg-bg-secondary rounded-3xl border border-white/5 shadow-2xl p-8 md:p-10">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-serif font-bold text-cream mb-2">Selamat Datang</h2>
            <p class="text-white/50">Masuk untuk menikmati layanan penuh.</p>
        </div>

        <form wire:submit.prevent="login" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-cream mb-2">Email</label>
                <input wire:model="email" type="email" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-cream mb-2">Password</label>
                <input wire:model="password" type="password" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                @error('password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-cream to-coffee-light text-bg-dark font-bold py-4 rounded-xl hover:scale-[1.02] transition shadow-lg">
                Masuk Sekarang
            </button>
        </form>

        <div class="mt-8 text-center text-white/50 text-sm">
            Belum punya akun? <a href="{{ route('register') }}" class="text-cream hover:underline">Daftar disini</a>
        </div>
    </div>
</div>