<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curriculum extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'curriculums';

    protected $fillable = [
        'study_program_id',
        'code',
        'name',
        'academic_year_start',
        'academic_year_end',
        'total_credits',
        'mandatory_university_credits',
        'mandatory_faculty_credits',
        'mandatory_program_credits',
        'elective_credits',
        'concentration_credits',
        'description',
        'structure',
        'concentration_list',
        'is_active',
        'effective_date',
        'document_file',
    ];

    protected $casts = [
        'academic_year_start' => 'integer',
        'academic_year_end' => 'integer',
        'structure' => 'array',
        'concentration_list' => 'array',
        'is_active' => 'boolean',
        'effective_date' => 'date',
    ];

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function rps(): HasMany
    {
        return $this->hasMany(Rps::class);
    }
}
