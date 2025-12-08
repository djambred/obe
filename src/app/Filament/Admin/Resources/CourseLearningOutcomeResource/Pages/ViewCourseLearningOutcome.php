<?php

namespace App\Filament\Admin\Resources\CourseLearningOutcomeResource\Pages;

use App\Filament\Admin\Resources\CourseLearningOutcomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCourseLearningOutcome extends ViewRecord
{
    protected static string $resource = CourseLearningOutcomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
