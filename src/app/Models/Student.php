<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nim', 'name', 'email', 'phone',
        'faculty_id', 'study_program_id',
        'enrollment_year', 'class_group', 'status',
        'gender', 'birth_date', 'birth_place', 'address', 'city', 'province',
        'parent_name', 'parent_phone', 'extras',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'enrollment_year' => 'integer',
        'extras' => 'array',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }
}
