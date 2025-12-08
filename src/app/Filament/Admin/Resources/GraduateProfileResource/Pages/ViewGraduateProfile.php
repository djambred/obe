<?php

namespace App\Filament\Admin\Resources\GraduateProfileResource\Pages;

use App\Filament\Admin\Resources\GraduateProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGraduateProfile extends ViewRecord
{
    protected static string $resource = GraduateProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
