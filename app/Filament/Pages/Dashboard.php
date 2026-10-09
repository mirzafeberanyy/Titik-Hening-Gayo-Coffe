<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * Batasi akses halaman
     */
    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && $user->isAdmin();
    }

    /**
     * Supaya menu sidebar juga ikut hilang
     */
    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }
}
