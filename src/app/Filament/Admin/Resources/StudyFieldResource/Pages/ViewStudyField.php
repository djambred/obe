<?php

namespace App\Filament\Admin\Resources\StudyFieldResource\Pages;

use App\Filament\Admin\Resources\StudyFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudyField extends ViewRecord
{
    protected static string $resource = StudyFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
