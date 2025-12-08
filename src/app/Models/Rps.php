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
        'course_id',
        'lecturer_id',
        'curriculum_id',
        'academic_year',
        'semester',
        'version',
        'class_code',
        'student_quota',
        'course_description',
        'clo_list',
        'plo_mapped',
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
