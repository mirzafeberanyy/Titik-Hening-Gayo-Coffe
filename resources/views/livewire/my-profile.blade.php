<div class="min-h-screen bg-bg-dark pt-32 pb-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-serif font-bold text-cream mb-8">Akun Saya</h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            <div class="md:col-span-1">
                <div class="bg-bg-secondary rounded-2xl border border-white/5 p-4 sticky top-32">
                    <div class="flex items-center gap-3 mb-6 p-2">
                        <div class="w-10 h-10 rounded-full bg-cream text-bg-dark flex items-center justify-center font-bold text-lg">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-white font-bold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-white/50 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <nav class="space-y-1">
                        <a href="{{ route('my-profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 text-cream font-medium border border-white/5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil & Alamat
                        </a>
                        
                        <a href="{{ route('my-orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/60 hover:text-white hover:bg-white/5 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Riwayat Pesanan
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-white/10">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition text-left">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <div class="md:col-span-3 space-y-6">
                
                <div class="bg-bg-secondary rounded-2xl border border-white/5 p-6 md:p-8">
                    <h2 class="text-xl font-bold text-white mb-6">Edit Profil</h2>
                    
                    @if (session()->has('success'))
                        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 text-green-400 rounded-xl flex items-center gap-2">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="updateProfile">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm text-white/60 mb-2">Nama Lengkap</label>
                                <input wire:model="name" type="text" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                                @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm text-white/60 mb-2">Email</label>
                                <input wire:model="email" type="email" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                                @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm text-white/60 mb-2">No. WhatsApp</label>
                                <input wire:model="phone" type="number" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                                @error('phone') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <hr class="border-white/10 my-6">
                        <h3 class="text-lg font-bold text-cream mb-4">Alamat Pengiriman Default</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm text-white/60 mb-2">Provinsi</label>
                                <select wire:model.live="selectedProvince" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                                    <option value="">-- Pilih --</option>
                                    @foreach($this->provinces as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-white/60 mb-2">Kota/Kab</label>
                                <select wire:model.live="selectedCity" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                                    <option value="">-- Pilih --</option>
                                    @foreach($this->cities as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-white/60 mb-2">Kecamatan</label>
                                <select wire:model.live="selectedDistrict" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                                    <option value="">-- Pilih --</option>
                                    @foreach($this->districts as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm text-white/60 mb-2">Alamat Lengkap</label>
                            <textarea wire:model="address" rows="3" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none" placeholder="Jl. Mawar No. 123..."></textarea>
                            @error('address') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-cream text-bg-dark font-bold rounded-xl hover:bg-white transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-bg-secondary rounded-2xl border border-white/5 p-6 md:p-8">
                    <h2 class="text-xl font-bold text-white mb-6">Ganti Password</h2>

                    @if (session()->has('success_password'))
                        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 text-green-400 rounded-xl flex items-center gap-2">
                            {{ session('success_password') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="updatePassword">
                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="block text-sm text-white/60 mb-2">Password Saat Ini</label>
                                <input wire:model="current_password" type="password" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                                @error('current_password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm text-white/60 mb-2">Password Baru</label>
                                <input wire:model="new_password" type="password" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                                @error('new_password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm text-white/60 mb-2">Konfirmasi Password Baru</label>
                                <input wire:model="new_password_confirmation" type="password" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 border border-white/10 text-white font-bold rounded-xl hover:bg-white/5 transition">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>