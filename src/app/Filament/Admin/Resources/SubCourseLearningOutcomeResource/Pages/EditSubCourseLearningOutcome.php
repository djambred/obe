<?php

namespace App\Filament\Admin\Resources\SubCourseLearningOutcomeResource\Pages;

use App\Filament\Admin\Resources\SubCourseLearningOutcomeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubCourseLearningOutcome extends EditRecord
{
    protected static string $resource = SubCourseLearningOutcomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
