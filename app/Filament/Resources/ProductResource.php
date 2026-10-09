<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    
    protected static ?string $navigationGroup = 'Shop Management';

    protected static ?int $navigationSort = 2; // Urutan menu (Category no 1)

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- GROUP 1: INFORMASI UTAMA ---
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Product Identity')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->readOnly(), // Biar tidak diubah manual sembarangan

                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([ // Fitur Quick Create Category langsung dari sini
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                        Forms\Components\TextInput::make('slug')->required(),
                                    ]),

                                Forms\Components\RichEditor::make('description')
                                    ->columnSpanFull(),
                            ]),

                        // --- GROUP 3: VARIAN PRODUK (REPEATER) ---
                        Forms\Components\Section::make('Product Variants')
                            ->description('Manage sizes, roast levels, and prices here.')
                            ->schema([
                                Forms\Components\Repeater::make('variants')
                                    ->relationship() // Relasi ke tabel product_variants
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Variant Name')
                                            ->placeholder('Ex: 250g - Medium Roast')
                                            ->required(),
                                        
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('weight_grams')
                                                    ->options([
                                                        100 => '100g',
                                                        200 => '200g',
                                                        250 => '250g',
                                                        500 => '500g',
                                                        1000 => '1kg',
                                                    ])
                                                    ->required(),

                                                Forms\Components\Select::make('grind_type')
                                                    ->options([
                                                        'biji' => 'Biji Utuh (Beans)',
                                                        'kasar' => 'Giling Kasar (Coarse)',
                                                        'sedang' => 'Giling Sedang (Medium)',
                                                        'halus' => 'Giling Halus (Fine)',
                                                    ])
                                                    ->required(),
                                                
                                                Forms\Components\Select::make('roast_level')
                                                    ->options([
                                                        'light' => 'Light Roast',
                                                        'medium' => 'Medium Roast',
                                                        'dark' => 'Dark Roast',
                                                    ]),

                                                Forms\Components\TextInput::make('stock')
                                                    ->numeric()
                                                    ->default(10)
                                                    ->required(),
                                            ]),

                                        Forms\Components\TextInput::make('price')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->required(),

                                        Forms\Components\TextInput::make('sku')
                                            ->label('SKU Code')
                                            ->default(fn () => 'GAYO-' . strtoupper(Str::random(6))) // Otomatis saat Add New
                                            ->required()
                                            // Fitur Baru: Tombol Generate Manual
                                            ->suffixAction(
                                                Forms\Components\Actions\Action::make('regenerate')
                                                    ->icon('heroicon-m-arrow-path') // Ikon Refresh
                                                    ->action(function (Set $set) {
                                                        $set('sku', 'GAYO-' . strtoupper(Str::random(6)));
                                                    })
                                            )
                                            ->unique(ignoreRecord: true),
                                    ])
                                    ->columns(1)
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->collapsible(),
                            ]),
                    ])->columnSpan(2),

                // --- GROUP 2: SIDEBAR KANAN (Status & Gambar) ---
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true),
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Product')
                                    ->helperText('Show on Homepage carousel?'),
                            ]),

                        Forms\Components\Section::make('Images')
                            ->schema([
                                Forms\Components\FileUpload::make('main_image')
                                    ->image()
                                    ->directory('products') // Folder penyimpanan
                                    ->required(),
                                
                                Forms\Components\TextInput::make('base_price')
                                    ->label('Display Price (Start From)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required() // <--- INI YANG KURANG TADI
                                    ->default(0) // Mengisi nilai awal 0
                                    ->helperText('Harga tampilan awal sebelum pilih varian'),
                            ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_image')
                    ->circular(),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('base_price')
                    ->money('IDR')
                    ->label('Starts From'),

                Tables\Columns\TextColumn::make('variants_count')
                    ->counts('variants')
                    ->label('Variants'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}