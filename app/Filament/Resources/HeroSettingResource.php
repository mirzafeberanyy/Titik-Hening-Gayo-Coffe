<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSettingResource\Pages;
use App\Filament\Resources\HeroSettingResource\RelationManagers;
use App\Models\HeroSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;


class HeroSettingResource extends Resource
{
    protected static ?string $model = HeroSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Hero Section Homepage')
                    ->description('Atur tampilan banner utama website.')
                    ->schema([

                        TextInput::make('title')
                            ->label('Judul Besar')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('subtitle')
                            ->label('Sub-Judul (Deskripsi)')
                            ->required()
                            ->rows(3),

                        FileUpload::make('bg_image')
                            ->label('Gambar Background')
                            ->image() // Harus file gambar
                            ->directory('hero-images') // Disimpan di folder storage/app/public/hero-images
                            ->columnSpanFull(), // Lebar penuh
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Saat Ini'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])

            ->bulkActions([]) 
            ->headerActions([]); 
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
            'index' => Pages\ListHeroSettings::route('/'),
            'create' => Pages\CreateHeroSetting::route('/create'),
            'edit' => Pages\EditHeroSetting::route('/{record}/edit'),
        ];
    }
}
