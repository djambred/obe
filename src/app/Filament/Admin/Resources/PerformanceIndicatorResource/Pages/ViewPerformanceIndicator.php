<?php

namespace App\Filament\Admin\Resources\PerformanceIndicatorResource\Pages;

use App\Filament\Admin\Resources\PerformanceIndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPerformanceIndicator extends ViewRecord
{
    protected static string $resource = PerformanceIndicatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
