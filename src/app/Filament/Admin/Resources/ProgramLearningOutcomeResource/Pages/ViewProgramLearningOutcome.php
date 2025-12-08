<?php

namespace App\Filament\Admin\Resources\ProgramLearningOutcomeResource\Pages;

use App\Filament\Admin\Resources\ProgramLearningOutcomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProgramLearningOutcome extends ViewRecord
{
    protected static string $resource = ProgramLearningOutcomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
