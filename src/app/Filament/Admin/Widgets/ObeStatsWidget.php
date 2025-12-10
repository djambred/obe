<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Course;
use App\Models\CourseLearningOutcome;
use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\ProgramLearningOutcome;
use App\Models\Rps;
use App\Models\StudyProgram;
use App\Models\University;
use Filament\Widgets\Widget;

class ObeStatsWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.obe-stats-widget';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function getStats(): array
    {
        return [
            [
                'label' => 'Universitas',
                'value' => University::count(),
                'url' => \App\Filament\Admin\Resources\UniversityResource::getUrl(),
                'icon' => 'heroicon-o-building-library',
                'color' => 'primary',
            ],
            [
                'label' => 'Fakultas',
                'value' => Faculty::count(),
                'url' => \App\Filament\Admin\Resources\FacultyResource::getUrl(),
                'icon' => 'heroicon-o-building-office-2',
                'color' => 'primary',
            ],
            [
                'label' => 'Program Studi',
                'value' => StudyProgram::count(),
                'url' => \App\Filament\Admin\Resources\StudyProgramResource::getUrl(),
                'icon' => 'heroicon-o-academic-cap',
                'color' => 'primary',
            ],
            [
                'label' => 'Mata Kuliah',
                'value' => Course::count(),
                'url' => \App\Filament\Admin\Resources\CourseResource::getUrl(),
                'icon' => 'heroicon-o-book-open',
                'color' => 'success',
            ],
            [
                'label' => 'Dosen',
                'value' => Lecturer::count(),
                'url' => \App\Filament\Admin\Resources\LecturerResource::getUrl(),
                'icon' => 'heroicon-o-user-group',
                'color' => 'success',
            ],
            [
                'label' => 'RPS',
                'value' => Rps::count(),
                'url' => \App\Filament\Admin\Resources\RpsResource::getUrl(),
                'icon' => 'heroicon-o-document-text',
                'color' => 'warning',
            ],
            [
                'label' => 'CPL',
                'value' => ProgramLearningOutcome::count(),
                'url' => \App\Filament\Admin\Resources\ProgramLearningOutcomeResource::getUrl(),
                'icon' => 'heroicon-o-light-bulb',
                'color' => 'info',
            ],
            [
                'label' => 'CPMK',
                'value' => CourseLearningOutcome::count(),
                'url' => \App\Filament\Admin\Resources\CourseLearningOutcomeResource::getUrl(),
                'icon' => 'heroicon-o-sparkles',
                'color' => 'info',
            ],
        ];
    }
}
