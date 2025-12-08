<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecturer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'faculty_id',
        'study_program_id',
        'nidn',
        'nip',
        'name',
        'email',
        'phone',
        'employment_status',
        'academic_rank',
        'functional_position',
        'highest_education',
        'education_field',
        'education_background',
        'expertise_areas',
        'research_interests',
        'certifications',
        'sinta_id',
        'sinta_score',
        'sinta_rank_national',
        'sinta_rank_affiliation',
        'sinta_publications',
        'sinta_data',
        'google_scholar_id',
        'h_index',
        'i10_index',
        'total_citations',
        'total_publications',
        'google_scholar_data',
        'photo',
        'biography',
        'achievements',
        'is_active',
        'last_profile_sync',
    ];

    protected $casts = [
        'education_background' => 'array',
        'expertise_areas' => 'array',
        'research_interests' => 'array',
        'certifications' => 'array',
        'sinta_data' => 'array',
        'google_scholar_data' => 'array',
        'achievements' => 'array',
        'is_active' => 'boolean',
        'last_profile_sync' => 'datetime',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_lecturer')
            ->withPivot([
                'academic_year',
                'semester',
                'class_code',
                'role',
                'expertise_match_score',
                'publication_match_score',
                'overall_match_score',
                'match_reasons',
                'student_count',
                'workload_sks',
                'is_active',
                'notes'
            ])
            ->withTimestamps();
    }

    public function rps(): HasMany
    {
        return $this->hasMany(Rps::class);
    }

    /**
     * Calculate match score between lecturer and course
     */
    public function calculateMatchScore(Course $course): array
    {
        $expertiseScore = $this->calculateExpertiseMatch($course);
        $publicationScore = $this->calculatePublicationMatch($course);

        $overallScore = ($expertiseScore * 0.6) + ($publicationScore * 0.4);

        return [
            'expertise_match_score' => round($expertiseScore, 2),
            'publication_match_score' => round($publicationScore, 2),
            'overall_match_score' => round($overallScore, 2),
            'match_reasons' => $this->generateMatchReasons($course, $expertiseScore, $publicationScore),
        ];
    }

    protected function calculateExpertiseMatch(Course $course): float
    {
        // TODO: Implement sophisticated matching algorithm
        // Compare expertise_areas with course topics, study fields, etc.
        return 0.0;
    }

    protected function calculatePublicationMatch(Course $course): float
    {
        // TODO: Implement publication relevance scoring
        // Analyze SINTA/GS publications related to course topics
        return 0.0;
    }

    protected function generateMatchReasons(Course $course, float $expertiseScore, float $publicationScore): array
    {
        // TODO: Generate human-readable match reasons
        return [];
    }

    /**
     * Sync SINTA profile data
     */
    public function syncSintaProfile(): bool
    {
        // TODO: Implement SINTA API/scraping integration
        return false;
    }

    /**
     * Sync Google Scholar profile data
     */
    public function syncGoogleScholarProfile(): bool
    {
        // TODO: Implement Google Scholar scraping
        return false;
    }

    /**
     * Get total workload for a given period
     */
    public function getTotalWorkload(string $academicYear, string $semester): float
    {
        return $this->courses()
            ->wherePivot('academic_year', $academicYear)
            ->wherePivot('semester', $semester)
            ->wherePivot('is_active', true)
            ->sum('course_lecturer.workload_sks');
    }
}
