<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'curriculum_id',
        'study_program_id',
        'code',
        'name',
        'english_name',
        'type',
        'concentration',
        'credits',
        'theory_credits',
        'practice_credits',
        'field_credits',
        'semester',
        'description',
        'prerequisites',
        'corequisites',
        'learning_media',
        'learning_methods',
        'assessment_methods',
        'references',
        'is_active',
    ];

    protected $casts = [
        'prerequisites' => 'array',
        'corequisites' => 'array',
        'assessment_methods' => 'array',
        'references' => 'array',
        'is_active' => 'boolean',
    ];

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function studyFields(): BelongsToMany
    {
        return $this->belongsToMany(StudyField::class, 'course_study_field')
            ->withPivot(['coverage_level', 'topics_covered', 'notes'])
            ->withTimestamps();
    }

    public function lecturers(): BelongsToMany
    {
        return $this->belongsToMany(Lecturer::class, 'course_lecturer')
            ->withPivot([
                'academic_year',
                'semester',
                'class_code',
                'role',
                'expertise_match_score',
                'publication_match_score',
                'overall_match_score',
                'match_reasons',
                'student_count',
                'workload_sks',
                'is_active',
                'notes'
            ])
            ->withTimestamps();
    }

    public function courseLearningOutcomes(): HasMany
    {
        return $this->hasMany(CourseLearningOutcome::class);
    }

    public function rps(): HasMany
    {
        return $this->hasMany(Rps::class);
    }

    public function obeAssessments(): HasMany
    {
        return $this->hasMany(ObeAssessment::class);
    }

    public function continuousImprovements(): HasMany
    {
        return $this->hasMany(ContinuousImprovement::class);
    }

    public function prerequisiteCourses()
    {
        if (empty($this->prerequisites)) {
            return collect();
        }
        return Course::whereIn('id', $this->prerequisites)->get();
    }

    public function corequisiteCourses()
    {
        if (empty($this->corequisites)) {
            return collect();
        }
        return Course::whereIn('id', $this->corequisites)->get();
    }
}
