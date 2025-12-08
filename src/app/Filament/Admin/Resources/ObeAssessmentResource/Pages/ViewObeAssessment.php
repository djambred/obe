<?php

namespace App\Filament\Admin\Resources\ObeAssessmentResource\Pages;

use App\Filament\Admin\Resources\ObeAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewObeAssessment extends ViewRecord
{
    protected static string $resource = ObeAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
