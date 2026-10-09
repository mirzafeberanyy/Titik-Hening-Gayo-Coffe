<div class="min-h-screen bg-bg-dark flex items-center justify-center py-20 px-4">
    <div class="max-w-3xl w-full bg-bg-secondary rounded-3xl border border-white/5 shadow-2xl overflow-hidden relative">
        
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-cream to-coffee"></div>

        <div class="p-10 md:p-16 text-center">
            <div class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-green-500/20 animate-bounce-slow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4">Pesanan Berhasil Dibuat!</h1>
            <p class="text-white/60 text-lg mb-8">Langkah terakhir, silakan selesaikan pembayaran Anda.</p>

            <div class="bg-bg-dark rounded-2xl p-8 border border-white/5 mb-8 text-left max-w-xl mx-auto">
                <div class="flex justify-between items-center mb-4 border-b border-white/10 pb-4">
                    <span class="text-white/50">Kode Pesanan</span>
                    <span class="text-cream font-bold text-xl tracking-wider">{{ $order->code }}</span>
                </div>
                <div class="flex justify-between items-center mb-6">
                    <span class="text-white/50">Total Pembayaran</span>
                    <span class="text-white font-bold text-2xl">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                
                <div class="text-xs text-center text-white/20 font-mono mt-2">
                    Token: {{ substr($order->snap_token, 0, 10) }}...
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" class="px-8 py-4 rounded-xl border border-white/10 text-white hover:bg-white/5 transition font-bold">
                    Kembali ke Home
                </a>
                
                @if($order->payment_method == 'midtrans')
                    <button id="pay-button" class="px-8 py-4 rounded-xl bg-gradient-to-r from-cream to-coffee-light text-bg-dark font-bold shadow-lg hover:shadow-cream/20 hover:scale-[1.02] transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Bayar Sekarang
                    </button>
                @else
                    <div class="bg-orange-500/10 text-orange-400 px-6 py-3 rounded-xl border border-orange-500/20 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <div class="text-left">
                            <p class="font-bold text-sm leading-tight">Siapkan Uang Tunai</p>
                            <p class="text-[10px] opacity-80">Bayar Rp {{ number_format($order->total_price, 0, ',', '.') }} ke kurir.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // Menggunakan Snap Token yang dikirim dari Controller
            window.snap.pay('{{ $order->snap_token }}', {
                onSuccess: function(result){
                    alert("Pembayaran Berhasil!");
                    window.location.href = "{{ route('home') }}";
                },
                onPending: function(result){
                    alert("Menunggu pembayaran...");
                    window.location.reload();
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                    window.location.reload();
                },
                onClose: function(){
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        };

        @if($order->payment_method == 'midtrans')
            @endif
    </script>
</div>