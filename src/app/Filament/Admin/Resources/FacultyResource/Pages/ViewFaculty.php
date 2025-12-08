<?php

namespace App\Filament\Admin\Resources\FacultyResource\Pages;

use App\Filament\Admin\Resources\FacultyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFaculty extends ViewRecord
{
    protected static string $resource = FacultyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
