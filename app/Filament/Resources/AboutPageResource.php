<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutPageResource\Pages;
use App\Filament\Resources\AboutPageResource\RelationManagers;
use App\Models\AboutPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class AboutPageResource extends Resource
{
    protected static ?string $model = AboutPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    // Opsional: Ganti label di sidebar jadi "Profil Perusahaan" atau "About Us"
    protected static ?string $navigationLabel = 'About Us'; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Header Halaman')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Halaman')
                            ->required(),
                        Forms\Components\TextInput::make('subtitle')
                            ->label('Slogan / Subjudul'),
                        Forms\Components\FileUpload::make('main_image')
                            ->label('Foto Utama (Landscape)')
                            ->image()
                            ->directory('about-images')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Konten Cerita')
                    ->schema([
                        Forms\Components\RichEditor::make('story')
                            ->label('Cerita Sejarah / Filosofi')
                            ->columnSpanFull()
                            ->required(),
                    ]),

                Forms\Components\Section::make('Visi & Misi')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('vision')
                            ->label('Visi Kami')
                            ->rows(4),
                        Forms\Components\Textarea::make('mission')
                            ->label('Misi Kami')
                            ->rows(4),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_image'),
                Tables\Columns\TextColumn::make('title')->label('Judul'),
                Tables\Columns\TextColumn::make('updated_at')->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAboutPages::route('/'),
            'create' => Pages\CreateAboutPage::route('/create'),
            'edit' => Pages\EditAboutPage::route('/{record}/edit'),
        ];
    }
}