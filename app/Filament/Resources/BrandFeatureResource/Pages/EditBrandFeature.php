<?php

namespace App\Filament\Resources\BrandFeatureResource\Pages;

use App\Filament\Resources\BrandFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBrandFeature extends EditRecord
{
    protected static string $resource = BrandFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
