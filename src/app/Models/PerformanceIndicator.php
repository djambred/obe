<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerformanceIndicator extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sub_course_learning_outcome_id',
        'course_learning_outcome_id',
        'code',
        'description',
        'criteria',
        'rubric',
        'weight',
        'assessment_type',
        'passing_grade',
        'grading_scale',
        'grading_scale_level',
        'faculty_id',
        'study_program_id',
        'order',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'passing_grade' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function subCourseLearningOutcome(): BelongsTo
    {
        return $this->belongsTo(SubCourseLearningOutcome::class);
    }

    public function courseLearningOutcome(): BelongsTo
    {
        return $this->belongsTo(CourseLearningOutcome::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }
}
