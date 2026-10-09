<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get; 
use Filament\Forms\Set; 
use Illuminate\Support\Str; 
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'Shop Management';
    
    protected static ?int $navigationSort = 1; // Paling atas di grup Shop
    
    // Tampilkan jumlah order status 'new' di sidebar (Badge Merah)
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('order_status', 'new')->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // KOLOM KIRI: Status & Item
                Forms\Components\Group::make()
                    ->schema([
                        Section::make('Order Status')
                            ->schema([
                                Select::make('order_status')
                                    ->options([
                                        'new' => 'New Order',
                                        'processing' => 'Processing',
                                        'shipping' => 'Shipping',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        // LOGIC OTOMATIS:
                                        // Jika status berubah jadi 'shipping' DAN resi masih kosong
                                        if ($state === 'shipping') {
                                            // Generate Resi Palsu tapi Keren
                                            // Format: RESI-[TAHUN][BULAN]-[HARI]-[ACAK]
                                            $autoResi = 'JP-' . date('Ymd') . '-' . strtoupper(Str::random(4));
                                            
                                            $set('tracking_number', $autoResi);
                                        }

                                        if ($state === 'completed') {
                                            $set('payment_status', 'paid');
                                        }
                                    }), 

                                // Kolom Resi (Muncul hanya jika Shipping)
                                TextInput::make('tracking_number')
                                    ->label('Nomor Resi')
                                    ->visible(fn (Get $get) => $get('order_status') === 'shipping') // Logic Visible
                                    ->required(fn (Get $get) => $get('order_status') === 'shipping'), // Logic Wajib Isi
                                
                                Select::make('payment_status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'paid' => 'Paid',
                                        'failed' => 'Failed',
                                    ])
                                    ->required()
                                    ->native(false),
                            ])->columns(2),

                        Section::make('Order Items')
                            ->schema([
                                Repeater::make('items')
                                    ->relationship()
                                    ->schema([
                                        Select::make('product_id')
                                            ->label('Product')
                                            ->options(Product::query()->pluck('name', 'id'))
                                            ->disabled(), // Admin tidak boleh ubah item sembarangan
                                        
                                        TextInput::make('quantity')
                                            ->numeric()
                                            ->disabled(),
                                            
                                        TextInput::make('unit_price')
                                            ->label('Price (at buy)')
                                            ->prefix('Rp')
                                            ->disabled(),
                                            
                                        TextInput::make('total_price')
                                            ->label('Subtotal')
                                            ->prefix('Rp')
                                            ->disabled(),
                                    ])
                                    ->columns(4)
                                    ->deletable(false) // Jangan hapus item histori
                                    ->addable(false)   // Jangan tambah item manual
                            ])
                    ])->columnSpan(2),

                // KOLOM KANAN: Data Customer
                Forms\Components\Group::make()
                    ->schema([
                        Section::make('Customer Details')
                            ->schema([
                                TextInput::make('code')
                                    ->label('Order ID')
                                    ->readOnly(),
                                
                                TextInput::make('customer_name')
                                    ->label('Name')
                                    ->readOnly(),
                                    
                                TextInput::make('customer_email')
                                    ->label('Email')
                                    ->email()
                                    ->readOnly(),
                                    
                                TextInput::make('customer_phone')
                                    ->label('Phone')
                                    ->readOnly(),
                                    
                                Forms\Components\Textarea::make('shipping_address')
                                    ->readOnly(),
                                    
                                TextInput::make('total_price')
                                    ->label('Grand Total')
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->extraInputAttributes(['class' => 'text-xl font-bold text-primary-600']),
                            ])
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            // OPTIMASI: Load relasi user dan items di awal agar ringan
            ->modifyQueryUsing(fn ($query) => $query->with(['user', 'items'])) 
            
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')    
                    ->copyable(), 
                
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable()
                    ->label('Customer'),

                Tables\Columns\TextColumn::make('total_price')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipping' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
                
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            
            // --- BAGIAN INI YANG DIUBAH (ACTIONS) ---
            ->actions([
                Tables\Actions\ActionGroup::make([
                    
                    // 1. Edit Status
                    Tables\Actions\EditAction::make(),

                    // TOMBOL 1: DOWNLOAD INVOICE (Untuk Arsip/Email)
                    Tables\Actions\Action::make('pdf') 
                        ->label('Download Invoice')
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(fn (Order $record) => route('orders.print', $record)) // Pastikan Anda punya route ini atau sesuaikan
                        ->openUrlInNewTab(),

                    // TOMBOL 2: CETAK RESI (YANG BARU)
                    Tables\Actions\Action::make('print_label')
                        ->label('Cetak Label Resi')
                        ->icon('heroicon-o-truck')
                        ->color('warning') // Warna Kuning/Orange biar beda
                        ->url(fn (Order $record) => route('orders.print_label', $record))
                        ->openUrlInNewTab(),
                    
                    // 3. Hapus Order
                    Tables\Actions\DeleteAction::make(),
                    
                ]),
            ])
            // ----------------------------------------

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            // OPTIMASI: Auto refresh setiap 10 detik agar status berubah realtime
            ->poll('10s'); 
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    
    // Matikan fitur "Create Order" manual karena order harus dari Frontend
    public static function canCreate(): bool
    {
        return false;
    }
}