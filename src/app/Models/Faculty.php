<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'university_id',
        'code',
        'name',
        'vision',
        'mission',
        'objectives',
        'description',
        'logo',
        'dean_name',
        'phone',
        'email',
        'accreditation',
        'accreditation_date',
        'is_active',
    ];

    protected $casts = [
        'mission' => 'array',
        'objectives' => 'array',
        'accreditation_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function studyPrograms(): HasMany
    {
        return $this->hasMany(StudyProgram::class);
    }

    public function lecturers(): HasMany
    {
        return $this->hasMany(Lecturer::class);
    }
}
