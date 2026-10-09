<div class="min-h-screen bg-bg-dark pt-32 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl md:text-4xl font-serif font-bold text-cream mb-8 text-center">Checkout Pesanan</h1>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <div class="lg:col-span-7">
                <div class="bg-bg-secondary p-8 rounded-3xl border border-white/5 shadow-xl">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span></span> Informasi Pengiriman
                    </h2>

                    <form wire:submit.prevent="submit" class="space-y-6">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-cream mb-2">Nama Lengkap</label>
                                <input wire:model="customer_name" type="text" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream focus:ring-1 focus:ring-cream outline-none transition">
                                @error('customer_name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-cream mb-2">Email</label>
                                    <input wire:model="customer_email" type="email" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                                    @error('customer_email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-cream mb-2">No WhatsApp</label>
                                    <input wire:model="customer_phone" type="number" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                                    @error('customer_phone') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="border-white/10 my-6">

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-cream mb-2">Provinsi</label>
                                    <select wire:model.live="selectedProvince" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($this->provinces as $code => $name)
                                            <option value="{{ $code }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedProvince') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-cream mb-2">Kota / Kabupaten</label>
                                    <select wire:model.live="selectedCity" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none" {{ empty($this->provinces) ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Kota --</option>
                                        @foreach($this->cities as $code => $name)
                                            <option value="{{ $code }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <div wire:loading wire:target="selectedProvince" class="text-xs text-cream mt-1">Memuat kota...</div>
                                    @error('selectedCity') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-cream mb-2">Kecamatan</label>
                                    <select wire:model.live="selectedDistrict" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none" {{ empty($this->cities) ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Kecamatan --</option>
                                        @foreach($this->districts as $code => $name)
                                            <option value="{{ $code }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <div wire:loading wire:target="selectedCity" class="text-xs text-cream mt-1">Memuat kecamatan...</div>
                                    @error('selectedDistrict') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-cream mb-2">Jalan / Detail</label>
                                    <input wire:model="detail_address" type="text" class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cream outline-none" placeholder="Jl. Mawar No. 12, RT 01/02">
                                    @error('detail_address') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="border-white/10 my-6">

                        <div>
                            <label class="block text-sm font-medium text-cream mb-2">Pilih Kurir</label>
                            <div class="grid grid-cols-3 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="shipping_courier" value="jne" class="peer sr-only">
                                    <div class="rounded-xl border border-white/10 bg-bg-dark p-4 text-center hover:bg-white/5 peer-checked:border-cream peer-checked:bg-cream/10 transition">
                                        <span class="font-bold text-white">JNE</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="shipping_courier" value="pos" class="peer sr-only">
                                    <div class="rounded-xl border border-white/10 bg-bg-dark p-4 text-center hover:bg-white/5 peer-checked:border-cream peer-checked:bg-cream/10 transition">
                                        <span class="font-bold text-white">POS</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="shipping_courier" value="tiki" class="peer sr-only">
                                    <div class="rounded-xl border border-white/10 bg-bg-dark p-4 text-center hover:bg-white/5 peer-checked:border-cream peer-checked:bg-cream/10 transition">
                                        <span class="font-bold text-white">TIKI</span>
                                    </div>
                                </label>
                            </div>
                            @error('shipping_courier') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-cream mb-2">Metode Pembayaran</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="payment_method" value="midtrans" class="peer sr-only">
                                    <div class="rounded-xl border border-white/10 bg-bg-dark p-4 text-center hover:bg-white/5 peer-checked:border-cream peer-checked:bg-cream/10 transition flex flex-col items-center justify-center gap-2">
                                        <span class="text-2xl"></span>
                                        <span class="font-bold text-white text-sm">Transfer / QRIS</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="payment_method" value="cod" class="peer sr-only">
                                    <div class="rounded-xl border border-white/10 bg-bg-dark p-4 text-center hover:bg-white/5 peer-checked:border-cream peer-checked:bg-cream/10 transition flex flex-col items-center justify-center gap-2">
                                        <span class="text-2xl"></span>
                                        <span class="font-bold text-white text-sm">COD (Bayar di Tempat)</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        @if(!empty($shipping_options))
                            <div class="bg-white/5 p-4 rounded-xl border border-white/10 animate-fade-in">
                                <label class="block text-sm font-medium text-cream mb-3">Pilih Layanan</label>
                                <div class="space-y-3">
                                    @foreach($shipping_options as $option)
                                        <label class="flex items-center justify-between p-3 rounded-lg border border-white/10 bg-bg-dark cursor-pointer hover:border-cream transition">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" wire:model.live="selected_shipping_service" value="{{ $option['service'] }}|{{ $option['cost'][0]['value'] }}" class="text-cream focus:ring-cream bg-transparent">
                                                <div>
                                                    <p class="font-bold text-white">{{ $option['service'] }}</p>
                                                    <p class="text-xs text-white/50">{{ $option['description'] }} ({{ $option['cost'][0]['etd'] }} hari)</p>
                                                </div>
                                            </div>
                                            <span class="font-bold text-cream">Rp {{ number_format($option['cost'][0]['value'], 0, ',', '.') }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('selected_shipping_service') <span class="text-red-400 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="bg-white/5 p-8 rounded-3xl border border-white/5 sticky top-32">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span></span> Ringkasan Belanja
                    </h2>

                    <div class="flow-root mb-6 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        <ul role="list" class="-my-4 divide-y divide-white/10">
                            @foreach($cartItems as $item)
                                <li class="flex py-4 items-center">
                                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-white/10">
                                        @if($item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-coffee-dark flex items-center justify-center">☕</div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex flex-1 flex-col">
                                        <div class="flex justify-between text-base font-medium text-white">
                                            <h3 class="line-clamp-1">{{ $item['name'] }}</h3>
                                            <p>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                        </div>
                                        <p class="mt-1 text-sm text-white/50">Qty: {{ $item['quantity'] }} • {{ $item['weight'] ?? 250 }}g</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="border-t border-white/10 pt-6 space-y-4">
                        <div class="flex justify-between text-base font-medium text-white">
                            <p>Subtotal Barang</p>
                            <p>Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex justify-between text-base font-medium text-white">
                            <p>Total Berat</p>
                            <p>{{ $total_weight }} gram</p>
                        </div>
                        <div class="flex justify-between text-base font-medium text-white">
                            <p>Ongkos Kirim</p>
                            @if($shipping_cost > 0)
                                <p class="text-cream font-bold">Rp {{ number_format($shipping_cost, 0, ',', '.') }}</p>
                            @else
                                <p class="text-white/50">-</p>
                            @endif
                        </div>
                        
                        <div class="flex justify-between text-2xl font-bold text-cream pt-4 border-t border-white/10">
                            <p>Total Bayar</p>
                            <p>Rp {{ number_format($grand_total, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button wire:click="submit" wire:loading.attr="disabled" class="w-full bg-gradient-to-r from-cream to-coffee-light text-bg-dark font-bold py-4 rounded-xl hover:scale-[1.02] transition shadow-lg hover:shadow-cream/20 disabled:opacity-50 disabled:cursor-not-allowed flex justify-center gap-2 items-center">
                            <span wire:loading.remove>Konfirmasi Pesanan</span>
                            <span wire:loading>
                                <svg class="animate-spin h-5 w-5 text-bg-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                        
                        @if (session()->has('error'))
                            <div class="mt-4 p-3 bg-red-500/20 border border-red-500/50 text-red-200 text-sm rounded-lg text-center">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>