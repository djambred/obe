<?php

namespace App\Filament\Admin\Resources\RpsResource\Pages;

use App\Filament\Admin\Resources\RpsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRps extends ListRecords
{
    protected static string $resource = RpsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
