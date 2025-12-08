<?php

namespace App\Filament\Admin\Resources\ProgramLearningOutcomeResource\Pages;

use App\Filament\Admin\Resources\ProgramLearningOutcomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProgramLearningOutcomes extends ListRecords
{
    protected static string $resource = ProgramLearningOutcomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
