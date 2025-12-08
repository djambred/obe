<?php

namespace App\Filament\Admin\Resources\PerformanceIndicatorResource\Pages;

use App\Filament\Admin\Resources\PerformanceIndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerformanceIndicator extends EditRecord
{
    protected static string $resource = PerformanceIndicatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
