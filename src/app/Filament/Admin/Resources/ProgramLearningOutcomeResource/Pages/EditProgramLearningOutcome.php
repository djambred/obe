<?php

namespace App\Filament\Admin\Resources\ProgramLearningOutcomeResource\Pages;

use App\Filament\Admin\Resources\ProgramLearningOutcomeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProgramLearningOutcome extends EditRecord
{
    protected static string $resource = ProgramLearningOutcomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
