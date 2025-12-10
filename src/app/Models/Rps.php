<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rps extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rps';

    protected $fillable = [
        'faculty_id',
        'study_program_id',
        'course_id',
        'lecturer_id',
        'coordinator_id',
        'head_of_program_id',
        'curriculum_id',
        'academic_year',
        'semester',
        'version',
        'class_code',
        'student_quota',
        'course_description',
        'learning_materials',
        'prerequisites',
        'clo_list',
        'plo_mapped',
        'performance_indicators',
        'study_field_mapped',
        'weekly_plan',
        'assessment_plan',
        'assessment_rubric',
        'grading_system',
        'main_references',
        'supporting_references',
        'learning_media',
        'learning_software',
        'document_file',
        'syllabus_file',
        'contract_file',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'approved_by',
        'approved_at',
        'approval_notes',
        'is_active',
    ];

    protected $casts = [
        'clo_list' => 'array',
        'plo_mapped' => 'array',
        'study_field_mapped' => 'array',
        'weekly_plan' => 'array',
        'assessment_plan' => 'array',
        'assessment_rubric' => 'array',
        'main_references' => 'array',
        'supporting_references' => 'array',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Accessor untuk mengkonversi array repeater menjadi format yang benar
    public function setCloListAttribute($value)
    {
        if (is_array($value)) {
            // Jika dari repeater simple, extract value saja
            $this->attributes['clo_list'] = json_encode(array_values(array_filter($value)));
        } else {
            $this->attributes['clo_list'] = $value;
        }
    }

    public function setPloMappedAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['plo_mapped'] = json_encode(array_values(array_filter($value)));
        } else {
            $this->attributes['plo_mapped'] = $value;
        }
    }

    public function setMainReferencesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['main_references'] = json_encode(array_values(array_filter($value)));
        } else {
            $this->attributes['main_references'] = $value;
        }
    }

    public function setSupportingReferencesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['supporting_references'] = json_encode(array_values(array_filter($value)));
        } else {
            $this->attributes['supporting_references'] = $value;
        }
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'coordinator_id');
    }

    public function headOfProgram(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'head_of_program_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function obeAssessments(): HasMany
    {
        return $this->hasMany(ObeAssessment::class);
    }

    /**
     * Submit RPS for review
     */
    public function submit(): bool
    {
        $this->status = 'Submitted';
        return $this->save();
    }

    /**
     * Review RPS
     */
    public function review(User $reviewer, string $notes, bool $approve = true): bool
    {
        $this->reviewed_by = $reviewer->id;
        $this->reviewed_at = now();
        $this->review_notes = $notes;

        if ($approve) {
            $this->status = 'Reviewed by Coordinator';
        } else {
            $this->status = 'Revision Required';
        }

        return $this->save();
    }

    /**
     * Approve RPS
     */
    public function approve(User $approver, string $notes = null): bool
    {
        $this->status = 'Approved';
        $this->approved_by = $approver->id;
        $this->approved_at = now();
        $this->approval_notes = $notes;
        return $this->save();
    }

    /**
     * Reject RPS
     */
    public function reject(User $reviewer, string $notes): bool
    {
        $this->status = 'Rejected';
        $this->reviewed_by = $reviewer->id;
        $this->reviewed_at = now();
        $this->review_notes = $notes;
        return $this->save();
    }
}
