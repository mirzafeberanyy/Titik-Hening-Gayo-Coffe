<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped; // Pastikan Mail Class ini sudah ada

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // --- LOGIC KIRIM EMAIL SETELAH SAVE ---
    protected function afterSave(): void
    {
        // Ambil data order yang baru saja disave
        $order = $this->record;

        // Cek 1: Apakah statusnya sekarang 'shipping'?
        // Cek 2: Apakah statusnya BERUBAH di save kali ini? (Biar ga spam email kalau cuma edit alamat)
        if ($order->order_status === 'shipping' && $order->wasChanged('order_status')) {
            
            // Cek apakah punya email customer
            if ($order->customer_email) {
                try {
                    Mail::to($order->customer_email)->send(new OrderShipped($order));
                    
                    // (Opsional) Beri notifikasi ke Admin bahwa email terkirim
                    \Filament\Notifications\Notification::make()
                        ->title('Email Resi Terkirim')
                        ->success()
                        ->send();

                } catch (\Exception $e) {
                    \Filament\Notifications\Notification::make()
                        ->title('Gagal kirim email')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            }
        }
    }
}