<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContinuousImprovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'study_program_id',
        'course_id',
        'academic_year',
        'semester',
        'improvement_area',
        'issue_identified',
        'root_cause',
        'improvement_plan',
        'action_items',
        'planned_start_date',
        'planned_end_date',
        'actual_start_date',
        'actual_end_date',
        'pic_user_id',
        'stakeholders',
        'status',
        'progress_percentage',
        'implementation_notes',
        'results',
        'is_effective',
        'effectiveness_evidence',
        'evidence_files',
    ];

    protected $casts = [
        'action_items' => 'array',
        'planned_start_date' => 'date',
        'planned_end_date' => 'date',
        'actual_start_date' => 'date',
        'actual_end_date' => 'date',
        'stakeholders' => 'array',
        'is_effective' => 'boolean',
        'evidence_files' => 'array',
    ];

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    /**
     * Start implementation
     */
    public function startImplementation(): bool
    {
        $this->status = 'In Progress';
        $this->actual_start_date = now();
        return $this->save();
    }

    /**
     * Complete implementation
     */
    public function complete(string $results, bool $isEffective, string $evidence = null): bool
    {
        $this->status = 'Completed';
        $this->actual_end_date = now();
        $this->progress_percentage = 100;
        $this->results = $results;
        $this->is_effective = $isEffective;
        $this->effectiveness_evidence = $evidence;
        return $this->save();
    }

    /**
     * Update progress
     */
    public function updateProgress(int $percentage, string $notes = null): bool
    {
        $this->progress_percentage = min(100, max(0, $percentage));

        if ($notes) {
            $this->implementation_notes = $notes;
        }

        if ($this->progress_percentage === 100) {
            $this->status = 'Completed';
            $this->actual_end_date = now();
        }

        return $this->save();
    }
}
