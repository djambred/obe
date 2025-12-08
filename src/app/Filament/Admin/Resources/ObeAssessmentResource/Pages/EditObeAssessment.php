<?php

namespace App\Filament\Admin\Resources\ObeAssessmentResource\Pages;

use App\Filament\Admin\Resources\ObeAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditObeAssessment extends EditRecord
{
    protected static string $resource = ObeAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
