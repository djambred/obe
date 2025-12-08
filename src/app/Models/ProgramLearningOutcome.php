<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramLearningOutcome extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'study_program_id',
        'code',
        'description',
        'category',
        'bloom_cognitive_level',
        'bloom_affective_level',
        'bloom_psychomotor_level',
        'sndikti_reference',
        'kkni_level',
        'industry_reference',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function graduateProfiles(): BelongsToMany
    {
        return $this->belongsToMany(GraduateProfile::class, 'graduate_profile_plo')
            ->withPivot(['contribution_level', 'notes'])
            ->withTimestamps();
    }

    public function courseLearningOutcomes(): BelongsToMany
    {
        return $this->belongsToMany(CourseLearningOutcome::class, 'clo_plo')
            ->withPivot(['contribution_level', 'weight', 'notes'])
            ->withTimestamps();
    }
}
