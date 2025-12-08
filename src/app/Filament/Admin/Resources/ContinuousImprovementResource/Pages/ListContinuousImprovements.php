<?php

namespace App\Filament\Admin\Resources\ContinuousImprovementResource\Pages;

use App\Filament\Admin\Resources\ContinuousImprovementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContinuousImprovements extends ListRecords
{
    protected static string $resource = ContinuousImprovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
