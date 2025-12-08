<?php

namespace App\Filament\Admin\Resources\CourseLearningOutcomeResource\Pages;

use App\Filament\Admin\Resources\CourseLearningOutcomeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourseLearningOutcome extends EditRecord
{
    protected static string $resource = CourseLearningOutcomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
