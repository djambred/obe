<?php

namespace App\Filament\Admin\Resources\StudyFieldResource\Pages;

use App\Filament\Admin\Resources\StudyFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudyField extends EditRecord
{
    protected static string $resource = StudyFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
