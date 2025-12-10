<?php

namespace App\Filament\Admin\Pages;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\StudyProgram;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class CpmkCplMatrix extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static string $view = 'filament.admin.pages.cpmk-cpl-matrix';

    protected static ?string $navigationGroup = 'Academic Management';

    protected static ?string $title = 'Matriks CPMK terhadap CPL';

    protected static ?int $navigationSort = 3;

    public ?int $faculty_id = null;
    public ?int $study_program_id = null;
    public ?int $course_id = null;

    public Collection $cpls;
    public Collection $cpmks;
    public array $matrix = [];
    public bool $showMatrix = false;

    public function mount(): void
    {
        $this->cpls = collect();
        $this->cpmks = collect();
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('faculty_id')
                    ->label('Fakultas')
                    ->options(Faculty::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function () {
                        $this->study_program_id = null;
                        $this->course_id = null;
                        $this->showMatrix = false;
                    }),

                Select::make('study_program_id')
                    ->label('Program Studi')
                    ->options(function () {
                        if ($this->faculty_id) {
                            return StudyProgram::where('faculty_id', $this->faculty_id)
                                ->pluck('name', 'id');
                        }
                        return [];
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function () {
                        $this->course_id = null;
                        $this->showMatrix = false;
                    })
                    ->disabled(fn () => !$this->faculty_id),

                Select::make('course_id')
                    ->label('Mata Kuliah')
                    ->options(function () {
                        if ($this->study_program_id) {
                            return Course::whereHas('curriculum', function ($query) {
                                $query->where('study_program_id', $this->study_program_id);
                            })
                                ->orderBy('code')
                                ->get()
                                ->mapWithKeys(fn ($course) => [$course->id => "{$course->code} - {$course->name}"])
                                ->toArray();
                        }
                        return [];
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function () {
                        $this->showMatrix = false;
                    })
                    ->disabled(fn () => !$this->study_program_id),
            ])
            ->columns(3);
    }

    public function loadMatrix(): void
    {
        if (!$this->course_id) {
            return;
        }

        // Get CPL dari program studi
        $this->cpls = \App\Models\ProgramLearningOutcome::where('study_program_id', $this->study_program_id)
            ->orderBy('code')
            ->get();

        // Get CPMK dari mata kuliah dengan relasi ke CPL
        $this->cpmks = \App\Models\CourseLearningOutcome::where('course_id', $this->course_id)
            ->with('programLearningOutcomes')
            ->orderBy('code')
            ->get();

        // Build matrix
        $this->matrix = [];
        foreach ($this->cpmks as $cpmk) {
            $row = [
                'cpmk' => $cpmk,
                'contributions' => []
            ];

            foreach ($this->cpls as $cpl) {
                // Check if CPMK contributes to this CPL
                $contributes = $cpmk->programLearningOutcomes->contains('id', $cpl->id);
                $row['contributions'][$cpl->id] = $contributes ? 1 : 0;
            }

            $this->matrix[] = $row;
        }

        $this->showMatrix = true;
    }

    public function getTitle(): string
    {
        return 'Matriks Kontribusi CPMK terhadap CPL';
    }

    public function getHeading(): string
    {
        return 'Kontribusi CPMK terhadap CPL';
    }

    public static function getNavigationLabel(): string
    {
        return 'Matriks CPMK → CPL';
    }
}
