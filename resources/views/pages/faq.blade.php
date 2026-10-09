<x-layouts.app title="FAQ | Titik Hening">
    <div class="pt-32 pb-20 bg-bg-dark min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-cream mb-8 text-center">
                Pertanyaan Umum
            </h1>
            
            <div class="space-y-4" x-data="{ active: null }">
                
                <div class="border border-white/10 rounded-xl bg-white/5 overflow-hidden">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full flex justify-between items-center p-5 text-left text-white font-bold hover:bg-white/5 transition">
                        <span>Apakah kopi di Titik Hening Halal?</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-300" :class="active === 1 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 1" x-collapse class="p-5 pt-0 text-white/70 border-t border-white/10 text-sm leading-relaxed">
                        Ya, 100% Halal. Produk kami murni biji kopi Arabica Gayo tanpa campuran bahan kimia, alkohol, atau bahan non-halal lainnya. Proses roasting dilakukan secara higienis.
                    </div>
                </div>

                <div class="border border-white/10 rounded-xl bg-white/5 overflow-hidden">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full flex justify-between items-center p-5 text-left text-white font-bold hover:bg-white/5 transition">
                        <span>Kapan pesanan saya dikirim?</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-300" :class="active === 2 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 2" x-collapse class="p-5 pt-0 text-white/70 border-t border-white/10 text-sm leading-relaxed">
                        Pesanan yang masuk sebelum jam 14.00 WIB akan diproses dan diserahkan ke kurir pada hari yang sama. Pesanan di atas jam tersebut atau di hari Minggu/Libur Nasional akan dikirim pada hari kerja berikutnya.
                    </div>
                </div>

                <div class="border border-white/10 rounded-xl bg-white/5 overflow-hidden">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full flex justify-between items-center p-5 text-left text-white font-bold hover:bg-white/5 transition">
                        <span>Apakah bisa request gilingan kopi?</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-300" :class="active === 3 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 3" x-collapse class="p-5 pt-0 text-white/70 border-t border-white/10 text-sm leading-relaxed">
                        Tentu! Saat memilih produk, Anda bisa memilih varian: Biji Utuh (Beans), Giling Kasar (Cold Brew/French Press), Giling Medium (V60/Manual Brew), atau Giling Halus (Espresso/Tubruk).
                    </div>
                </div>

                <div class="border border-white/10 rounded-xl bg-white/5 overflow-hidden">
                    <button @click="active = (active === 4 ? null : 4)" class="w-full flex justify-between items-center p-5 text-left text-white font-bold hover:bg-white/5 transition">
                        <span>Bagaimana cara tracking resi?</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-300" :class="active === 4 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 4" x-collapse class="p-5 pt-0 text-white/70 border-t border-white/10 text-sm leading-relaxed">
                        Setelah pesanan dikirim, kami akan mengirimkan email berisi Nomor Resi. Anda juga bisa mengecek status resi langsung di menu "Pesanan Saya" di website ini.
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>