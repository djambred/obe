<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyField extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'study_program_id',
        'code',
        'name',
        'description',
        'category',
        'sub_fields',
        'order',
        'is_active',
    ];

    protected $casts = [
        'sub_fields' => 'array',
        'is_active' => 'boolean',
    ];

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_study_field')
            ->withPivot(['coverage_level', 'topics_covered', 'notes'])
            ->withTimestamps();
    }
}
