<?php

namespace App\Filament\Admin\Resources\StudyProgramResource\Pages;

use App\Filament\Admin\Resources\StudyProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudyProgram extends EditRecord
{
    protected static string $resource = StudyProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
