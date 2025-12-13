<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

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
        'academic_positions',
        'administrative_positions',
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
        'academic_positions' => 'array',
        'administrative_positions' => 'array',
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
        if (empty($this->sinta_id)) {
            return false;
        }

        try {
            $client = new \GuzzleHttp\Client();
            // SINTA domain moved to kemdiktisaintek.go.id with profile path
            $sintaUrl = "https://sinta.kemdiktisaintek.go.id/authors/profile/{$this->sinta_id}";

            $response = $client->get($sintaUrl, [
                'timeout' => 10,
                'headers' => [
                    // SINTA blocks some default clients; spoofing browser UA helps
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129 Safari/537.36'
                ],
                'http_errors' => false,
            ]);

            if ($response->getStatusCode() !== 200) {
                Log::warning("SINTA sync non-200 for lecturer {$this->id}: " . $response->getStatusCode());
                return false;
            }

            $html = $response->getBody()->getContents();

            // New layout: pr-num blocks contain metrics
            // Order: SINTA Score Overall, SINTA Score 3Yr, Affil Score, Affil Score 3Yr
            $data = [];
            if (preg_match_all('/<div class="pr-num">\s*([\d.,]+)/i', $html, $matches) && !empty($matches[1])) {
                $numbers = array_values(array_map(function ($value) {
                    return (float) str_replace([','], '', $value);
                }, $matches[1]));

                $data['sinta_score'] = $numbers[0] ?? null;              // SINTA Score Overall
                // $numbers[1] = SINTA Score 3Yr (optional, not stored)
                $data['sinta_rank_national'] = $numbers[2] ?? null;      // Affil Score
                $data['sinta_rank_affiliation'] = $numbers[3] ?? null;   // Affil Score 3Yr
            }

            // Extract h-index, i10-index, citations, dan article counts dari stat table
            // Table format: <tr> <td>Citation</td> <td class="text-warning">0</td> <td class="text-success">45</td> </tr>
            if (preg_match('/<tr[^>]*>.*?<td[^>]*>Citation<\/td>.*?<td[^>]*class="text-success"[^>]*>([\d.,]+)<\/td>/is', $html, $matches)) {
                $data['total_citations'] = (int) str_replace([','], '', $matches[1]);
            }
            if (preg_match('/<tr[^>]*>.*?<td[^>]*>H-Index<\/td>.*?<td[^>]*class="text-success"[^>]*>([\d.,]+)<\/td>/is', $html, $matches)) {
                $data['h_index'] = (int) str_replace([','], '', $matches[1]);
            }
            if (preg_match('/<tr[^>]*>.*?<td[^>]*>i10-Index<\/td>.*?<td[^>]*class="text-success"[^>]*>([\d.,]+)<\/td>/is', $html, $matches)) {
                $data['i10_index'] = (int) str_replace([','], '', $matches[1]);
            }

            if (!empty(array_filter($data, fn ($v) => $v !== null))) {
                $data['last_profile_sync'] = now();
                $data['sinta_data'] = json_encode([
                    'url' => $sintaUrl,
                    'synced_at' => now()->toIso8601String(),
                    'source' => 'SINTA'
                ]);

                $this->update(array_filter($data, fn ($v) => $v !== null));
                return true;
            }

            Log::warning("SINTA sync parse failed for lecturer {$this->id}");
            return false;
        } catch (\Exception $e) {
            Log::error("SINTA sync error for lecturer {$this->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sync Google Scholar profile data
     */
    public function syncGoogleScholarProfile(): bool
    {
        if (empty($this->google_scholar_id)) {
            return false;
        }

        try {
            $client = new \GuzzleHttp\Client();

            // Google Scholar URL format
            $url = "https://scholar.google.com/citations?hl=en&user={$this->google_scholar_id}";

            $response = $client->get($url, [
                'timeout' => 10,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ]
            ]);

            $html = $response->getBody()->getContents();

            // Parse HTML untuk extract metrics
            // Google Scholar structure biasanya:
            // h-index: Citations / h-index
            // i10-index: i10-index:
            // citations: Citations

            $data = [];

            // Extract h-index
            if (preg_match('/h-index:\s*(\d+)/i', $html, $matches)) {
                $data['h_index'] = (int) $matches[1];
            }

            // Extract i10-index
            if (preg_match('/i10-index:\s*(\d+)/i', $html, $matches)) {
                $data['i10_index'] = (int) $matches[1];
            }

            // Extract citations
            if (preg_match('/Citations:\s*(\d+)/i', $html, $matches)) {
                $data['total_citations'] = (int) $matches[1];
            }

            // Count publications - biasanya ada table dengan publikasi
            if (preg_match_all('/<tr.*?<\/tr>/is', $html, $tables)) {
                // Hitung rows (roughly approximate publications)
                $data['total_publications'] = count($tables[0]) - 1; // minus header
            }

            if (!empty($data)) {
                $data['last_profile_sync'] = now();
                $data['google_scholar_data'] = json_encode([
                    'url' => $url,
                    'synced_at' => now()->toIso8601String(),
                    'source' => 'Google Scholar'
                ]);

                $this->update($data);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Google Scholar sync error for lecturer {$this->id}: " . $e->getMessage());
            return false;
        }
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

    /**
     * Mutator for expertise_areas: convert comma-separated string to array
     */
    public function setExpertiseAreasAttribute($value): void
    {
        if (is_string($value)) {
            $this->attributes['expertise_areas'] = json_encode(
                array_map('trim', explode(',', $value))
            );
        } else {
            $this->attributes['expertise_areas'] = json_encode($value ?? []);
        }
    }

    /**
     * Mutator for research_interests: convert comma-separated string to array
     */
    public function setResearchInterestsAttribute($value): void
    {
        if (is_string($value)) {
            $this->attributes['research_interests'] = json_encode(
                array_map('trim', explode(',', $value))
            );
        } else {
            $this->attributes['research_interests'] = json_encode($value ?? []);
        }
    }

    /**
     * Accessor for expertise_areas: convert array to comma-separated string for display
     */
    public function getExpertiseAreasAttribute($value): string|array
    {
        if (!$value) {
            return [];
        }
        $decoded = json_decode($value, true);
        if (is_array($decoded) && count($decoded) > 0) {
            return implode(', ', $decoded);
        }
        return $decoded ?? [];
    }

    /**
     * Accessor for research_interests: convert array to comma-separated string for display
     */
    public function getResearchInterestsAttribute($value): string|array
    {
        if (!$value) {
            return [];
        }
        $decoded = json_decode($value, true);
        if (is_array($decoded) && count($decoded) > 0) {
            return implode(', ', $decoded);
        }
        return $decoded ?? [];
    }
}
