<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\InvoiceController;
use App\Models\Order;
use App\Livewire\CheckoutPage;
use App\Livewire\OrderSuccess;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\StoriesPage;
use App\Livewire\StoryDetail;
use App\Livewire\ProductDetail;
use App\Livewire\ShopPage;
use App\Livewire\AboutPage;
use App\Mail\OrderPlaced;
use App\Livewire\MyProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/checkout', CheckoutPage::class)
    ->middleware('auth') // SATPAM
    ->name('checkout');
Route::get('/checkout/success/{code}', OrderSuccess::class)->name('checkout.success');
Route::post('payment-notification', [PaymentCallbackController::class, 'receive']);
Route::get('/invoice/{orderId}', [InvoiceController::class, 'download'])->name('invoice.download');
Route::get('/login', LoginPage::class)->name('login');
Route::get('/register', RegisterPage::class)->name('register');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Route yang butuh login
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', MyOrdersPage::class)->name('my-orders');
    Route::get('/my-profile', MyProfile::class)->name('my-profile');
});

Route::get('/stories', StoriesPage::class)->name('stories');
Route::get('/stories/{slug}', StoryDetail::class)->name('stories.show');

Route::get('/product/{slug}', ProductDetail::class)->name('product.detail');
Route::get('/shop', ShopPage::class)->name('shop');

Route::get('/about', AboutPage::class)->name('about');

// Route untuk testing email dihapus demi keamanan

// 1. Route untuk Download INVOICE (Keuangan/Harga)
Route::get('/admin/orders/{record}/print', function($record) {
    if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'staff'])) abort(403);

    $order = Order::with(['items.product', 'items.product.variants'])->find($record);
    
    if(!$order) return abort(404);

    $pdf = Pdf::loadView('pdf.invoice', compact('order'));
    
    // Gunakan stream() agar tampil di browser dulu
    return $pdf->stream('Invoice-' . $order->code . '.pdf');
})->name('orders.print'); // <--- Nama ini yang dicari oleh Filament


// 2. Route untuk Cetak LABEL RESI (Pengiriman/Packing)
Route::get('/admin/orders/{record}/print-label', function($record) {
    if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'staff'])) abort(403);

    $order = \App\Models\Order::with(['items.product', 'items.product.variants'])->find($record);
    
    if(!$order) return abort(404);

    // --- GENERATE BARCODE ---
    $generator = new BarcodeGeneratorPNG();
    // Ubah kode order jadi gambar base64
    $barcode = base64_encode($generator->getBarcode($order->code, $generator::TYPE_CODE_128));

    // Kirim variable $barcode ke view
    $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.shipping_label', compact('order', 'barcode'));
    
    // Set ukuran kertas A6 (standar resi termal: 105mm x 148mm)
    $pdf->setPaper('a6', 'portrait'); 
    
    return $pdf->stream('Label-' . $order->code . '.pdf');
})->name('orders.print_label');

// HALAMAN LEGAL & TRUST (SYARAT MIDTRANS)
Route::view('/privacy-policy', 'pages.privacy')->name('privacy');
Route::view('/terms-conditions', 'pages.terms')->name('terms');
Route::view('/refund-policy', 'pages.refund')->name('refund');
Route::view('/faq', 'pages.faq')->name('faq');