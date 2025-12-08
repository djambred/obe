<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCourseLearningOutcome extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_learning_outcome_id',
        'code',
        'description',
        'bloom_cognitive_level',
        'bloom_affective_level',
        'bloom_psychomotor_level',
        'week_number',
        'learning_materials',
        'learning_methods',
        'duration_minutes',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function courseLearningOutcome(): BelongsTo
    {
        return $this->belongsTo(CourseLearningOutcome::class);
    }

    public function performanceIndicators(): HasMany
    {
        return $this->hasMany(PerformanceIndicator::class);
    }
}
