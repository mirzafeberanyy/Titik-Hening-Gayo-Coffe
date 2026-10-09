<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 3; // Urutan paling bawah
    
    protected int | string | array $columnSpan = 'full'; // Lebar penuh

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Ambil 5 order terakhir saja
                OrderResource::getEloquentQuery()->latest()->take(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('code')->label('Kode Order')->weight('bold'),
                Tables\Columns\TextColumn::make('customer_name')->label('Pembeli'),
                Tables\Columns\TextColumn::make('total_price')->money('IDR'),
                Tables\Columns\TextColumn::make('order_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipping' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Waktu'),
            ])
            ->actions([
                // Tombol pintas Edit
                Tables\Actions\Action::make('open')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn ($record) => OrderResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}