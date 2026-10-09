<?php

namespace App\Filament\Resources\BrandFeatureResource\Pages;

use App\Filament\Resources\BrandFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBrandFeatures extends ListRecords
{
    protected static string $resource = BrandFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
