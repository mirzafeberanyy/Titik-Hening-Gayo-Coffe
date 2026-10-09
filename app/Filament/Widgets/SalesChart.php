<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan (30 Hari Terakhir)';
    protected static string $color = 'info';
    
    // PERBAIKAN DI SINI: Tambahkan tipe data eksplisit
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Trend::model(Order::class)
            ->between(start: now()->subDays(30), end: now())
            ->perDay()
            ->sum('total_price');

        return [
            'datasets' => [
                [
                    'label' => 'Omset (Rp)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'fill' => 'start',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}