<?php

namespace App\Filament\Admin\Resources\StudyFieldResource\Pages;

use App\Filament\Admin\Resources\StudyFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudyFields extends ListRecords
{
    protected static string $resource = StudyFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
