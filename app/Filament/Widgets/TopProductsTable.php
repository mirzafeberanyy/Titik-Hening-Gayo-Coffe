<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TopProductsTable extends BaseWidget
{
    protected static ?string $heading = 'Produk Terlaris';
    
    protected int | string | array $columnSpan = 'full';

    public function getTableRecordKey(Model $record): string
    {
        return (string) $record->product_id;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderItem::query()
                    ->with('product') 
                    // Kita ambil product_id dan unit_price (harga saat beli)
                    ->select('product_id', 'unit_price', DB::raw('SUM(quantity) as total_sold'))
                    ->groupBy('product_id', 'unit_price') // unit_price harus masuk groupBy juga
                    ->orderBy('total_sold', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->default('Produk Tidak Ditemukan'),
                    
                Tables\Columns\TextColumn::make('total_sold')
                    ->label('Quantity Terjual')
                    ->badge()
                    ->color('primary'),
                    
                // KITA AMBIL LANGSUNG DARI unit_price DI TABEL order_items
                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Harga Satuan')
                    ->money('IDR'),
            ]);
    }
}