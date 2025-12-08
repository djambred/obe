<?php

namespace App\Filament\Admin\Resources\CurriculumResource\Pages;

use App\Filament\Admin\Resources\CurriculumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCurriculums extends ListRecords
{
    protected static string $resource = CurriculumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
