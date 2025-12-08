<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'faculty_id',
        'code',
        'name',
        'level',
        'vision',
        'mission',
        'objectives',
        'description',
        'head_of_program',
        'secretary',
        'accreditation',
        'accreditation_date',
        'accreditation_number',
        'standard_study_period',
        'degree_awarded',
        'is_active',
    ];

    protected $casts = [
        'mission' => 'array',
        'objectives' => 'array',
        'accreditation_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function studyFields(): HasMany
    {
        return $this->hasMany(StudyField::class);
    }

    public function curriculums(): HasMany
    {
        return $this->hasMany(Curriculum::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function graduateProfiles(): HasMany
    {
        return $this->hasMany(GraduateProfile::class);
    }

    public function programLearningOutcomes(): HasMany
    {
        return $this->hasMany(ProgramLearningOutcome::class);
    }

    public function lecturers(): HasMany
    {
        return $this->hasMany(Lecturer::class);
    }

    public function continuousImprovements(): HasMany
    {
        return $this->hasMany(ContinuousImprovement::class);
    }
}
