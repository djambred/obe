<?php

namespace App\Filament\Admin\Resources\RpsResource\Pages;

use App\Filament\Admin\Resources\RpsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRps extends EditRecord
{
    protected static string $resource = RpsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
