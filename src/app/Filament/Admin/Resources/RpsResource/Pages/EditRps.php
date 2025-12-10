<?php

namespace App\Filament\Admin\Resources\RpsResource\Pages;

use App\Filament\Admin\Resources\RpsResource;
use App\Models\Rps;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRps extends EditRecord
{
    protected static string $resource = RpsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        // Validate unique combination before saving, excluding current record
        $data = $this->data;

        $exists = Rps::where('course_id', $data['course_id'])
            ->where('academic_year', $data['academic_year'])
            ->where('semester', $data['semester'])
            ->where('class_code', $data['class_code'] ?? null)
            ->where('id', '!=', $this->record->id)
            ->exists();

        if ($exists) {
            Notification::make()
                ->danger()
                ->title('RPS Sudah Ada!')
                ->body('RPS untuk mata kuliah ini dengan tahun akademik, semester, dan kelas yang sama sudah ada dalam database. Silakan gunakan kelas yang berbeda.')
                ->persistent()
                ->send();

            $this->halt();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'RPS berhasil diperbarui';
    }
}
