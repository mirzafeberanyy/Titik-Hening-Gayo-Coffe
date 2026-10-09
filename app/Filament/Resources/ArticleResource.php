<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- KOLOM KIRI: KONTEN & GALERI ---
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Content')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->readOnly()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\RichEditor::make('content')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),

                        // FITUR KHUSUS: GALERI BUDAYA
                        Forms\Components\Section::make('Cultural Gallery')
                            ->description('Upload multiple photos for this article (ex: Ritual Gayo processes).')
                            ->schema([
                                Forms\Components\Repeater::make('images')
                                    ->relationship('images') // Relasi ke tabel article_images
                                    ->schema([
                                        Forms\Components\FileUpload::make('image_path')
                                            ->label('Photo')
                                            ->image()
                                            ->directory('article-gallery')
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('caption')
                                            ->placeholder('Photo Description...')
                                            ->required(),
                                    ])
                                    ->grid(2) // Tampilan grid 2 kolom biar rapi
                                    ->defaultItems(0)
                                    ->orderColumn('sort_order'), // Fitur drag-and-drop urutan foto
                            ]),
                    ])->columnSpan(2),

                // --- KOLOM KANAN: PUBLIKASI ---
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Publication')
                            ->schema([
                                Forms\Components\FileUpload::make('thumbnail')
                                    ->label('Main Cover Image')
                                    ->image()
                                    ->directory('articles')
                                    ->required(),

                                Forms\Components\DatePicker::make('published_at')
                                    ->label('Publish Date')
                                    ->native(false) // Pakai widget kalender JS yang bagus
                                    ->default(now()),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Headline?')
                                    ->helperText('Show at the top of blog page.'),

                                // Input otomatis User ID (Penulis)
                                Forms\Components\Hidden::make('user_id')
                                    ->default(auth()->id())
                                    ->required(),
                            ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50)
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->icon('heroicon-m-user')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('published_at')
                    ->date()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state <= now() ? 'success' : 'warning')
                    ->label('Date'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}