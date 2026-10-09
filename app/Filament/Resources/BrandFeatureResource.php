<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandFeatureResource\Pages;
use App\Filament\Resources\BrandFeatureResource\RelationManagers;
use App\Models\BrandFeature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandFeatureResource extends Resource
{
    protected static ?string $model = BrandFeature::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\Textarea::make('description')->required(),
            Forms\Components\FileUpload::make('icon')
                ->image()
                ->directory('brand-icons') // Folder simpan
                ->required(),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('icon'),
            Tables\Columns\TextColumn::make('title')->searchable(),
            Tables\Columns\ToggleColumn::make('is_active'),
        ])->defaultSort('sort_order', 'asc');
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
            'index' => Pages\ListBrandFeatures::route('/'),
            'create' => Pages\CreateBrandFeature::route('/create'),
            'edit' => Pages\EditBrandFeature::route('/{record}/edit'),
        ];
    }
}
