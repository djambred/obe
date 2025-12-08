<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseLearningOutcome extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'code',
        'description',
        'bloom_cognitive_level',
        'bloom_affective_level',
        'bloom_psychomotor_level',
        'weight',
        'order',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function programLearningOutcomes(): BelongsToMany
    {
        return $this->belongsToMany(ProgramLearningOutcome::class, 'clo_plo')
            ->withPivot(['contribution_level', 'weight', 'notes'])
            ->withTimestamps();
    }

    public function subCourseLearningOutcomes(): HasMany
    {
        return $this->hasMany(SubCourseLearningOutcome::class);
    }

    public function performanceIndicators(): HasMany
    {
        return $this->hasMany(PerformanceIndicator::class);
    }
}
