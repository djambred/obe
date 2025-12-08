<?php

namespace App\Filament\Admin\Resources\SubCourseLearningOutcomeResource\Pages;

use App\Filament\Admin\Resources\SubCourseLearningOutcomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSubCourseLearningOutcome extends ViewRecord
{
    protected static string $resource = SubCourseLearningOutcomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
