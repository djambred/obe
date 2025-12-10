<?php

namespace App\Filament\Admin\Pages;

use App\Models\Course;
use App\Models\CourseLearningOutcome;
use App\Models\Curriculum;
use App\Models\Faculty;
use App\Models\PerformanceIndicator;
use App\Models\StudyProgram;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class AssessmentMatrix extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationLabel = 'Matriks Bobot Penilaian';

    protected static ?string $title = 'Matriks Bobot Penilaian OBE';

    protected static ?string $navigationGroup = 'Academic Management';

    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.admin.pages.assessment-matrix';

    public $matrixData = [];
    public ?array $data = [];

    public $facultyId = null;
    public $studyProgramId = null;
    public $courseId = null;

    public function mount(): void
    {
        $this->form->fill([
            'faculty_id' => null,
            'study_program_id' => null,
            'course_id' => null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('faculty_id')
                    ->label('Fakultas')
                    ->options(Faculty::pluck('name', 'id'))
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        $this->facultyId = $state;
                        $this->studyProgramId = null;
                        $this->courseId = null;
                        $this->matrixData = [];
                        $set('study_program_id', null);
                        $set('course_id', null);
                    })
                    ->required(),

                Select::make('study_program_id')
                    ->label('Program Studi')
                    ->options(function (callable $get) {
                        $facultyId = $get('faculty_id');
                        if (!$facultyId) {
                            return [];
                        }
                        return StudyProgram::where('faculty_id', $facultyId)
                            ->pluck('name', 'id');
                    })
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        $this->studyProgramId = $state;
                        $this->courseId = null;
                        $this->matrixData = [];
                        $set('course_id', null);
                    })
                    ->required()
                    ->disabled(fn (callable $get) => !$get('faculty_id')),

                Select::make('course_id')
                    ->label('Mata Kuliah')
                    ->options(function (callable $get) {
                        $studyProgramId = $get('study_program_id');
                        if (!$studyProgramId) {
                            return [];
                        }
                        return Course::where('study_program_id', $studyProgramId)
                            ->get()
                            ->mapWithKeys(fn($course) => [$course->id => "{$course->code} - {$course->name}"])
                            ->toArray();
                    })
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->courseId = $state;
                        if ($state) {
                            $this->loadMatrixData();
                        } else {
                            $this->matrixData = [];
                        }
                    })
                    ->searchable()
                    ->required()
                    ->disabled(fn (callable $get) => !$get('study_program_id')),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function loadMatrixData(): void
    {
        if (!$this->courseId) {
            $this->matrixData = [];
            return;
        }

        $course = Course::find($this->courseId);

        // Get all CPMK for this course
        $cpmks = CourseLearningOutcome::where('course_id', $this->courseId)
            ->orderBy('code')
            ->get();

        // Get all Performance Indicators grouped by assessment type
        $indicators = PerformanceIndicator::whereIn('course_learning_outcome_id', $cpmks->pluck('id'))
            ->with('courseLearningOutcome')
            ->get()
            ->groupBy('assessment_type');

        // Build matrix structure
        $matrix = [];

        foreach ($indicators as $assessmentType => $typeIndicators) {
            $assessmentGroup = [
                'type' => $assessmentType,
                'items' => []
            ];

            // Group by specific assessment item (e.g., T1, T2, Q1, Q2)
            $itemCounter = 1;
            foreach ($typeIndicators as $indicator) {
                $itemCode = $this->getAssessmentItemCode($assessmentType, $itemCounter);

                // Calculate weights for each CPMK
                $cpmkWeights = [];
                foreach ($cpmks as $cpmk) {
                    if ($indicator->course_learning_outcome_id == $cpmk->id) {
                        $cpmkWeights[$cpmk->code] = $indicator->weight / 100;
                    } else {
                        $cpmkWeights[$cpmk->code] = 0;
                    }
                }

                $assessmentGroup['items'][] = [
                    'code' => $itemCode,
                    'indicator' => $indicator,
                    'cpmk_weights' => $cpmkWeights,
                    'total_weight' => $indicator->weight / 100,
                ];

                $itemCounter++;
            }

            $matrix[] = $assessmentGroup;
        }

        // Calculate CPMK totals
        $cpmkTotals = [];
        foreach ($cpmks as $cpmk) {
            $total = 0;
            foreach ($matrix as $assessmentGroup) {
                foreach ($assessmentGroup['items'] as $item) {
                    $total += $item['cpmk_weights'][$cpmk->code] ?? 0;
                }
            }
            $cpmkTotals[$cpmk->code] = $total;
        }

        $this->matrixData = [
            'course' => $course,
            'cpmks' => $cpmks,
            'matrix' => $matrix,
            'cpmk_totals' => $cpmkTotals,
        ];
    }

    private function getAssessmentItemCode(string $type, int $counter): string
    {
        $prefix = match($type) {
            'Tugas' => 'T',
            'Quiz' => 'Q',
            'UTS' => 'UTS',
            'UAS' => 'UAS',
            'Praktikum' => 'P',
            'Proyek' => 'PRO',
            'Presentasi' => 'PRE',
            'Portfolio' => 'POR',
            default => 'A',
        };

        return in_array($type, ['UTS', 'UAS']) ? $prefix . $counter : $prefix . $counter;
    }
}
