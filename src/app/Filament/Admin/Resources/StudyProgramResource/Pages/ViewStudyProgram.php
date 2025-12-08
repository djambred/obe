<?php

namespace App\Filament\Admin\Resources\StudyProgramResource\Pages;

use App\Filament\Admin\Resources\StudyProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudyProgram extends ViewRecord
{
    protected static string $resource = StudyProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
