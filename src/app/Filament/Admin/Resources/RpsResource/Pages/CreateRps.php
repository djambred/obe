<?php

namespace App\Filament\Admin\Resources\RpsResource\Pages;

use App\Filament\Admin\Resources\RpsResource;
use App\Models\Rps;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRps extends CreateRecord
{
    protected static string $resource = RpsResource::class;

    protected function beforeCreate(): void
    {
        // Validate unique combination before creating
        $data = $this->data;

        $exists = Rps::where('course_id', $data['course_id'])
            ->where('academic_year', $data['academic_year'])
            ->where('semester', $data['semester'])
            ->where('class_code', $data['class_code'] ?? null)
            ->exists();

        if ($exists) {
            Notification::make()
                ->danger()
                ->title('RPS Sudah Ada!')
                ->body('RPS untuk mata kuliah ini dengan tahun akademik, semester, dan kelas yang sama sudah ada dalam database. Silakan gunakan kelas yang berbeda atau edit RPS yang sudah ada.')
                ->persistent()
                ->send();

            $this->halt();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'RPS berhasil dibuat';
    }
}
