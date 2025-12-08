<?php

namespace App\Filament\Admin\Resources\CourseLearningOutcomeResource\Pages;

use App\Filament\Admin\Resources\CourseLearningOutcomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourseLearningOutcomes extends ListRecords
{
    protected static string $resource = CourseLearningOutcomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
