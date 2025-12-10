<?php

namespace App\Filament\Admin\Pages;

use App\Models\Course;
use App\Models\CourseLearningOutcome;
use App\Models\Curriculum;
use App\Models\ProgramLearningOutcome;
use App\Models\StudyField;
use App\Models\StudyProgram;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class CurriculumMapping extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Curriculum Mapping';

    protected static ?string $title = 'Peta Kurikulum & Mapping Pembelajaran';

    protected static ?string $navigationGroup = 'Academic Management';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.admin.pages.curriculum-mapping';

    public $mappingData = [];

    // Form data property
    public ?array $data = [];

    // Additional properties for state management
    public $studyProgramId = null;
    public $curriculumId = null;
    public $concentrationFilter = 'all';

    // Prevent scroll to top on Livewire updates
    protected $listeners = ['mappingDataUpdated' => '$refresh'];

    public function mount(): void
    {
        $this->form->fill([
            'study_program_id' => null,
            'curriculum_id' => null,
            'concentration' => 'all',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('study_program_id')
                    ->label('Program Studi')
                    ->options(StudyProgram::pluck('name', 'id'))
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        $this->studyProgramId = $state;
                        $this->curriculumId = null;
                        $this->mappingData = [];
                        $set('curriculum_id', null);
                        $set('concentration', 'all');
                    })
                    ->required(),

                Select::make('curriculum_id')
                    ->label('Kurikulum')
                    ->options(function (callable $get) {
                        $studyProgramId = $get('study_program_id');
                        if (!$studyProgramId) {
                            return [];
                        }
                        return Curriculum::where('study_program_id', $studyProgramId)
                            ->pluck('name', 'id');
                    })
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->curriculumId = $state;
                        if ($state) {
                            $this->loadMappingData();
                        } else {
                            $this->mappingData = [];
                        }
                    })
                    ->required()
                    ->disabled(fn (callable $get) => !$get('study_program_id')),

                Select::make('concentration')
                    ->label('Konsentrasi/Peminatan')
                    ->options(function (callable $get) {
                        $curriculumId = $get('curriculum_id');
                        if (!$curriculumId) {
                            return ['all' => 'Semua Konsentrasi'];
                        }

                        $concentrations = Course::where('curriculum_id', $curriculumId)
                            ->whereNotNull('concentration')
                            ->distinct()
                            ->pluck('concentration', 'concentration')
                            ->toArray();

                        return array_merge(['all' => 'Semua Konsentrasi'], $concentrations);
                    })
                    ->default('all')
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->concentrationFilter = $state;
                        if ($this->curriculumId) {
                            $this->loadMappingData();
                        }
                    })
                    ->disabled(fn (callable $get) => !$get('curriculum_id')),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function loadMappingData(): void
    {
        // Get curriculum_id from form data
        $formData = $this->form->getState();
        $curriculumId = $formData['curriculum_id'] ?? $this->curriculumId;
        $concentrationFilter = $formData['concentration'] ?? $this->concentrationFilter ?? 'all';

        if (!$curriculumId) {
            $this->mappingData = [];
            return;
        }

        // Get courses grouped by semester
        $coursesQuery = Course::where('curriculum_id', $curriculumId)
            ->with(['courseLearningOutcomes', 'studyFields', 'rps'])
            ->orderBy('semester')
            ->orderBy('name');

        if ($concentrationFilter && $concentrationFilter !== 'all') {
            $coursesQuery->where(function ($query) use ($concentrationFilter) {
                $query->where('concentration', $concentrationFilter)
                    ->orWhereNull('concentration');
            });
        }

        $courses = $coursesQuery->get();

        // Group by semester
        $this->mappingData = $courses->groupBy('semester')->map(function ($semesterCourses, $semester) {
            return [
                'semester' => $semester,
                'courses' => $semesterCourses->map(function ($course) {
                    // Get PLO from RPS if exists
                    $cpls = [];
                    if ($course->rps->isNotEmpty()) {
                        $rps = $course->rps->first();
                        $ploMapped = $rps->plo_mapped ?? [];
                        if (!empty($ploMapped)) {
                            $cpls = \App\Models\ProgramLearningOutcome::whereIn('code', $ploMapped)
                                ->get()
                                ->map(fn($plo) => [
                                    'code' => $plo->code,
                                    'description' => $plo->description,
                                ])
                                ->toArray();
                        }
                    }

                    return [
                        'id' => $course->id,
                        'code' => $course->code,
                        'name' => $course->name,
                        'credits' => $course->credits,
                        'concentration' => $course->concentration,
                        'type' => $course->type,
                        'cpls' => $cpls,
                        'cpmks' => $course->courseLearningOutcomes->map(fn($clo) => [
                            'code' => $clo->code,
                            'description' => $clo->description,
                        ])->toArray(),
                        'study_fields' => $course->studyFields->map(fn($sf) => [
                            'code' => $sf->code,
                            'name' => $sf->name,
                        ])->toArray(),
                    ];
                })->toArray(),
            ];
        })->values()->toArray();
    }

    public function updated($property): void
    {
        if (in_array($property, ['data.study_program_id', 'data.curriculum_id', 'data.concentration'])) {
            $this->loadMappingData();
        }
    }
}
