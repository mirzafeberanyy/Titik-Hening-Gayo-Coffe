<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Omset dari pesanan yang sudah lunas (Paid)
        $totalOmset = Order::where('payment_status', 'paid')->sum('total_price');

        // Jumlah pesanan baru yang belum diproses
        $orderPending = Order::where('order_status', 'new')->count();

        // Jumlah total pelanggan terdaftar
        $totalPelanggan = User::where('role', 'customer')->count();

        return [
            Stat::make('Total Omset', 'Rp ' . number_format($totalOmset, 0, ',', '.'))
                ->description('Total pendapatan lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            
            Stat::make('Pesanan Baru', $orderPending . ' Order')
                ->description('Perlu segera diproses')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color($orderPending > 0 ? 'warning' : 'gray'),

            Stat::make('Pelanggan', $totalPelanggan . ' User')
                ->description('Total pembeli terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}