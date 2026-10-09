<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Mail\OrderPlaced;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Midtrans\Config;
use Midtrans\Snap;

// Import Model Wilayah Indonesia
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;

class CheckoutPage extends Component
{
    // --- FORM DATA DIRI ---
    public $customer_name;
    public $customer_email;
    public $customer_phone;
    
    // --- FORM ALAMAT (WILAYAH) ---
    public $selectedProvince = null;
    public $selectedCity = null;
    public $selectedDistrict = null; // Kecamatan
    public $detail_address;          // Jalan/Nomor Rumah
    
    // --- PENGIRIMAN & ONGKIR ---
    public $shipping_courier = null; // jne, pos, tiki
    public $shipping_options = [];   // Opsi layanan (REG, YES)
    public $selected_shipping_service = null; 
    public $shipping_cost = 0;

    // --- DATA KERANJANG ---
    public $cartItems = [];
    public $subtotal = 0;
    public $total_weight = 0;
    public $grand_total = 0;

    public function mount(CartService $cartService)
    {
        $this->cartItems = $cartService->getCart();
        $this->subtotal = $cartService->getTotal();
        $this->total_weight = $cartService->getTotalWeight();
        $this->grand_total = $this->subtotal; 

        if (empty($this->cartItems)) {
            return redirect()->route('home');
        }

        // --- LOGIC SMART CHECKOUT (AUTO-FILL) ---
        if (auth()->check()) {
            $user = auth()->user();
            
            // 1. Isi Data Diri
            $this->customer_name = $user->name;
            $this->customer_email = $user->email;
            $this->customer_phone = $user->phone;
            
            // 2. Isi Alamat (Jika user sudah pernah belanja sebelumnya)
            if ($user->province_id) {
                $this->selectedProvince = $user->province_id;
                $this->selectedCity = $user->city_id;
                $this->selectedDistrict = $user->district_id;
                $this->detail_address = $user->address;
                
                // 3. Pancing Hitung Ongkir (Opsional: Biar user langsung lihat harga)
                // Kita panggil fungsi hitung fake shipping agar variabel internal siap
                $this->calculateFakeShipping();
            }
        }
    }

    // --- LOGIC DROPDOWN WILAYAH (GETTER) ---

    // 1. Ambil Semua Provinsi
    public function getProvincesProperty()
    {
        return Province::pluck('name', 'code');
    }

    // 2. Ambil Kota (Berdasarkan Provinsi Terpilih)
    public function getCitiesProperty()
    {
        if (!$this->selectedProvince) return [];
        return City::where('province_code', $this->selectedProvince)->pluck('name', 'code');
    }

    // 3. Ambil Kecamatan (Berdasarkan Kota Terpilih)
    public function getDistrictsProperty()
    {
        if (!$this->selectedCity) return [];
        return District::where('city_code', $this->selectedCity)->pluck('name', 'code');
    }

    // --- LOGIC RESET & HITUNG ONGKIR ---

    // Jika ganti Provinsi -> Reset Kota & Kecamatan
    public function updatedSelectedProvince()
    {
        $this->selectedCity = null;
        $this->selectedDistrict = null;
        $this->clearShipping();
    }

    // Jika ganti Kota -> Reset Kecamatan & Hitung Ongkir Ulang
    public function updatedSelectedCity() 
    { 
        $this->selectedDistrict = null;
        $this->calculateFakeShipping(); 
    }

    // Jika ganti Kurir -> Hitung Ongkir Ulang
    public function updatedShippingCourier() { $this->calculateFakeShipping(); }

    // --- SIMULASI HITUNG ONGKIR (TANPA API KEY) ---
    public function calculateFakeShipping()
    {
        if ($this->selectedCity && $this->shipping_courier) {
            
            // Ambil 2 digit awal kode provinsi untuk estimasi jarak
            // 11 = Aceh, 12 = Sumut, 3x = Jawa, dll.
            $provCode = substr($this->selectedProvince, 0, 2);
            
            $basePrice = 50000; // Default Jauh (Luar Pulau)

            if ($provCode == '11') { 
                $basePrice = 15000; // Sesama Aceh Murah
            } elseif ($provCode == '12') {
                $basePrice = 25000; // Sumut Agak Murah
            } elseif (in_array($provCode, ['31', '32', '33', '34', '35', '36', '51'])) {
                $basePrice = 45000; // Jawa & Bali
            }

            // Hitung berat (pembulatan ke atas per kg)
            $weightKg = ceil($this->total_weight / 1000);
            $weightKg = $weightKg < 1 ? 1 : $weightKg;

            // Modifikasi harga berdasarkan Kurir
            $courierPrice = $basePrice;
            if ($this->shipping_courier == 'pos') $courierPrice -= 2000; 
            if ($this->shipping_courier == 'tiki') $courierPrice += 5000;

            // Generate Opsi Layanan Palsu tapi Realistis
            $this->shipping_options = [
                [
                    'service' => 'REG',
                    'description' => 'Layanan Reguler',
                    'cost' => [['value' => $courierPrice * $weightKg, 'etd' => '3-5']]
                ],
                [
                    'service' => 'EXPRESS',
                    'description' => 'Yakin Esok Sampai',
                    'cost' => [['value' => ($courierPrice * 1.5) * $weightKg, 'etd' => '1-2']]
                ]
            ];

            $this->selected_shipping_service = null;
            $this->shipping_cost = 0;
            $this->grand_total = $this->subtotal;
        }
    }

    // Saat user memilih opsi ongkir (REG/EXPRESS)
    public function updatedSelectedShippingService($value)
    {
        if ($value) {
            list($service, $cost) = explode('|', $value);
            $this->shipping_cost = (int) $cost;
            $this->grand_total = $this->subtotal + $this->shipping_cost;
        }
    }

    public function clearShipping()
    {
        $this->shipping_options = [];
        $this->shipping_cost = 0;
        $this->grand_total = $this->subtotal;
        $this->selected_shipping_service = null;
    }

    // --- SUBMIT ORDER ---
    public function submit(CartService $cartService)
    {

        // --- PROTEKSI ROLE (TAMBAHKAN INI) ---
        // Cek jika user login tapi BUKAN customer (Admin/Staff)
        if (auth()->check() && !auth()->user()->isCustomer()) {
             $this->dispatch('swal', [
                'title' => 'Akses Ditolak',
                'text' => 'Maaf, akun Admin atau Staff tidak diperbolehkan melakukan transaksi belanja.',
                'icon' => 'error',
            ]);
            return;
        }
        // --------------------------------------
        $this->validate([
            'customer_name' => 'required|min:3',
            'customer_email' => 'required|email',
            'customer_phone' => 'required',
            'selectedProvince' => 'required',
            'selectedCity' => 'required',
            'selectedDistrict' => 'required',
            'detail_address' => 'required|min:5',
            'shipping_courier' => 'required',
            'selected_shipping_service' => 'required',
        ]);

        try {
            // Ambil Nama Wilayah
            $provName = Province::where('code', $this->selectedProvince)->value('name');
            $cityName = City::where('code', $this->selectedCity)->value('name');
            $distName = District::where('code', $this->selectedDistrict)->value('name');
            $fullAddress = "{$this->detail_address}, Kec. {$distName}, {$cityName}, {$provName}";

            // Update Data User
            if (auth()->check()) {
                auth()->user()->update([
                    'phone' => $this->customer_phone,
                    'province_id' => $this->selectedProvince,
                    'city_id' => $this->selectedCity,
                    'district_id' => $this->selectedDistrict,
                    'address' => $this->detail_address,
                ]);
            }

            list($serviceName, $serviceCost) = explode('|', $this->selected_shipping_service);

            // --- MULAI TRANSAKSI DATABASE ---
            $order = DB::transaction(function () use ($cartService, $fullAddress, $serviceName, $serviceCost) {
                
                // 1. CEK STOK & KURANGI STOK DULU (Sebelum buat Order)
                // Ini untuk mencegah overselling
                foreach ($this->cartItems as $item) {
                    
                    if ($item['variant_id']) {
                        // Jika produk bervarian
                        $variant = ProductVariant::lockForUpdate()->find($item['variant_id']); // lockForUpdate mencegah race condition
                        
                        if (!$variant || $variant->stock < $item['quantity']) {
                            throw new \Exception("Stok untuk " . $item['name'] . " (" . $item['variant_name'] . ") habis atau kurang!");
                        }
                        
                        // Kurangi Stok
                        $variant->decrement('stock', $item['quantity']);
                        
                    } else {
                        // Jika produk simple (tanpa varian) - Optional kalau ada
                        $product = Product::lockForUpdate()->find($item['id']);
                        
                        if (!$product || $product->variants->sum('stock') < $item['quantity']) { 
                             // Logic: Kalau produk simple biasanya kita cek stok varian default atau stok global
                             // Karena sistem kita full varian, biasanya item['variant_id'] selalu ada.
                             // Tapi sebagai pengaman, kita throw error saja kalau varian tidak terdeteksi
                             throw new \Exception("Varian produk tidak valid.");
                        }
                    }
                }

                // 2. Buat Order (Sama seperti sebelumnya)
                $newOrder = Order::create([
                    'code' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                    'user_id' => auth()->id(),
                    'customer_name' => $this->customer_name,
                    'customer_email' => $this->customer_email,
                    'customer_phone' => $this->customer_phone,
                    'shipping_address' => $fullAddress,
                    'total_weight' => $this->total_weight,
                    'shipping_cost' => $serviceCost,
                    'shipping_courier' => strtoupper($this->shipping_courier),
                    'shipping_service' => $serviceName,
                    'total_price' => $this->grand_total,
                    'order_status' => 'new',
                    'payment_status' => 'pending',
                    'payment_method' => $this->payment_method,
                ]);

                // 3. Buat Order Items
                foreach ($this->cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $newOrder->id,
                        'product_id' => $item['id'],
                        'product_variant_id' => $item['variant_id'], // Simpan ID Varian juga biar akurat
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                    ]);
                }

                // 4. Proses Payment (Midtrans)
                if ($this->payment_method == 'midtrans') {
                    Config::$serverKey = config('services.midtrans.server_key');
                    Config::$isProduction = config('services.midtrans.is_production');
                    Config::$isSanitized = true;
                    Config::$is3ds = true;

                    $params = [
                        'transaction_details' => [
                            'order_id' => $newOrder->code,
                            'gross_amount' => (int) $newOrder->total_price,
                        ],
                        'customer_details' => [
                            'first_name' => $this->customer_name,
                            'email' => $this->customer_email,
                            'phone' => $this->customer_phone,
                        ],
                    ];

                    $snapToken = Snap::getSnapToken($params);
                    $newOrder->update(['snap_token' => $snapToken]);
                }

                if ($this->payment_method == 'cod') {

                    try {
                        // Kirim ke email pelanggan
                        Mail::to($this->customer_email)->send(new OrderPlaced($newOrder));
                    } catch (\Exception $e) {
                        // Jika email gagal, jangan batalkan order, catat saja log-nya
                        \Illuminate\Support\Facades\Log::error('Gagal kirim email: ' . $e->getMessage());
                    }
                }
                
                $cartService->clear();
                return $newOrder;
            });

            return redirect()->route('checkout.success', ['code' => $order->code]);

        } catch (\Exception $e) {
            // Jika stok habis, akan masuk sini
            session()->flash('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout-page');
    }

    public $payment_method = 'midtrans'; // Default pilih transfer
}