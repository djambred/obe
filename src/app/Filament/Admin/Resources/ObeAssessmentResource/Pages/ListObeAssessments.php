<?php

namespace App\Filament\Admin\Resources\ObeAssessmentResource\Pages;

use App\Filament\Admin\Resources\ObeAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListObeAssessments extends ListRecords
{
    protected static string $resource = ObeAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
