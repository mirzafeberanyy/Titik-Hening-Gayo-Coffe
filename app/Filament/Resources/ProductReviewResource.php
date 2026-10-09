<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductReviewResource\Pages;
use App\Models\ProductReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductReviewResource extends Resource
{
    protected static ?string $model = ProductReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    
    protected static ?string $navigationGroup = 'Content Management';
    
    protected static ?string $navigationLabel = 'Reviews';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form ini jarang dipakai admin (karena user yg input), 
                // tapi kita sediakan untuk edit moderasi
                Forms\Components\Toggle::make('is_visible')
                    ->label('Tampilkan di Website?'),
                
                Forms\Components\Textarea::make('comment')
                    ->readOnly(), // Admin cuma bisa baca, jangan edit kata-kata user
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Stars')
                    ->color('warning') // Warna Emas
                    ->formatStateUsing(fn (string $state): string => str_repeat('★', $state)), // Ubah angka jadi simbol bintang

                Tables\Columns\TextColumn::make('comment')
                    ->limit(50),

                Tables\Columns\ToggleColumn::make('is_visible')
                    ->label('Publish'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\DeleteAction::make(), // Admin bisa hapus review jelek
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductReviews::route('/'),
            // Kita matikan create & edit karena review murni dari user
            // 'create' => Pages\CreateProductReview::route('/create'),
            // 'edit' => Pages\EditProductReview::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
       return false;
    }
}