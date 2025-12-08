<?php

namespace App\Filament\Admin\Resources\CurriculumResource\Pages;

use App\Filament\Admin\Resources\CurriculumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCurriculum extends EditRecord
{
    protected static string $resource = CurriculumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
