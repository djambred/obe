<?php

namespace App\Filament\Admin\Resources\ContinuousImprovementResource\Pages;

use App\Filament\Admin\Resources\ContinuousImprovementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContinuousImprovement extends EditRecord
{
    protected static string $resource = ContinuousImprovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
