<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // Ganti Ikon jadi Orang/Group
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    // Ganti Label di Sidebar jadi "Customers"
    protected static ?string $navigationLabel = 'Customers';
    
    // Masukkan ke Grup User Management (atau Shop Management juga boleh)
    protected static ?string $navigationGroup = 'User Management';

    // Urutan menu
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('role')
                            ->options([
                                'admin' => 'Admin',
                                'customer' => 'Customer',
                            ])
                            ->required()
                            ->default('customer'),

                        // Password hanya diisi jika ingin diganti (Nullable)
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state)) // Hanya simpan jika diisi
                            ->required(fn (string $context): bool => $context === 'create'), // Wajib saat create, sunnah saat edit
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // FILTER PENTING: Sembunyikan Admin dari list ini agar tidak terhapus tidak sengaja
            // Atau biarkan semua terlihat tapi diberi tanda.
            // Disini kita tampilkan semua tapi disortir.
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->icon('heroicon-m-envelope'),

                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',    // Admin warna Merah
                        'staff_gudang' => 'warning', // Kuning/Oranye (Baru)
                        'staff_konten' => 'info',    // Biru (Baru)
                        'customer' => 'success', // Customer warna Hijau
                        default => 'gray',           // Jaga-jaga kalau ada role lain
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Joined'),
            ])
            ->filters([
                // Filter agar admin bisa melihat cuma customer saja
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'customer' => 'Customer',
                    ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}