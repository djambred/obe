<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GraduateProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'study_program_id',
        'code',
        'name',
        'description',
        'career_prospects',
        'work_areas',
        'order',
        'is_active',
    ];

    protected $casts = [
        'work_areas' => 'array',
        'is_active' => 'boolean',
    ];

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function programLearningOutcomes(): BelongsToMany
    {
        return $this->belongsToMany(ProgramLearningOutcome::class, 'graduate_profile_plo')
            ->withPivot(['contribution_level', 'notes'])
            ->withTimestamps();
    }
}
