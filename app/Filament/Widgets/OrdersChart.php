<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penghasilan (30 Hari Terakhir)';
    
    // Urutan kedua (di bawah kartu statistik)
    protected static ?int $sort = 2;

    // Lebar penuh agar grafik terlihat jelas
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Ambil data order per hari
        $data = Trend::model(Order::class)
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#D4A574', // Warna Emas Kopi
                    'borderColor' => '#6B4423',     // Warna Cokelat
                    'fill' => true,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Grafik Garis
    }
}