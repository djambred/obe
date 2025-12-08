<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObeAssessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'rps_id',
        'academic_year',
        'semester',
        'class_code',
        'total_students',
        'passed_students',
        'failed_students',
        'clo_achievement',
        'plo_achievement',
        'statistics',
        'average_score',
        'passing_rate',
        'clo_attainment_rate',
        'plo_attainment_rate',
        'strengths',
        'weaknesses',
        'analysis',
        'recommendations',
        'improvement_actions',
        'best_practices',
        'previous_improvements',
        'improvement_effective',
        'metabase_dashboard_url',
    ];

    protected $casts = [
        'clo_achievement' => 'array',
        'plo_achievement' => 'array',
        'statistics' => 'array',
        'improvement_actions' => 'array',
        'best_practices' => 'array',
        'average_score' => 'decimal:2',
        'passing_rate' => 'decimal:2',
        'clo_attainment_rate' => 'decimal:2',
        'plo_attainment_rate' => 'decimal:2',
        'improvement_effective' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function rps(): BelongsTo
    {
        return $this->belongsTo(Rps::class);
    }

    /**
     * Calculate PLO achievement from CLO data
     */
    public function calculatePloAchievement(): array
    {
        // TODO: Implement PLO calculation based on CLO-PLO mapping
        $course = $this->course;
        $cloAchievement = $this->clo_achievement ?? [];
        $ploAchievement = [];

        // Get all CLOs with their PLO mappings
        $clos = $course->courseLearningOutcomes()
            ->with('programLearningOutcomes')
            ->get();

        foreach ($clos as $clo) {
            $cloScore = $cloAchievement[$clo->id]['avg_score'] ?? 0;

            foreach ($clo->programLearningOutcomes as $plo) {
                $weight = $plo->pivot->weight / 100;

                if (!isset($ploAchievement[$plo->id])) {
                    $ploAchievement[$plo->id] = [
                        'code' => $plo->code,
                        'total_weighted_score' => 0,
                        'total_weight' => 0,
                        'contributing_clos' => [],
                    ];
                }

                $ploAchievement[$plo->id]['total_weighted_score'] += $cloScore * $weight;
                $ploAchievement[$plo->id]['total_weight'] += $weight;
                $ploAchievement[$plo->id]['contributing_clos'][] = $clo->code;
            }
        }

        // Calculate final PLO scores
        foreach ($ploAchievement as $ploId => &$data) {
            if ($data['total_weight'] > 0) {
                $data['avg_score'] = $data['total_weighted_score'] / $data['total_weight'];
                $data['attainment_level'] = $this->determineAttainmentLevel($data['avg_score']);
            }
        }

        return $ploAchievement;
    }

    /**
     * Generate statistical analysis
     */
    public function generateStatistics(): array
    {
        // TODO: Implement comprehensive statistical analysis
        return [
            'mean' => $this->average_score,
            'median' => 0,
            'mode' => 0,
            'std_deviation' => 0,
            'min' => 0,
            'max' => 0,
            'quartiles' => [25 => 0, 50 => 0, 75 => 0],
            'distribution' => [],
        ];
    }

    /**
     * Generate improvement recommendations
     */
    public function generateRecommendations(): string
    {
        // TODO: Implement AI/rule-based recommendation engine
        $recommendations = [];

        if ($this->passing_rate < 70) {
            $recommendations[] = "Tingkat kelulusan di bawah standar. Pertimbangkan untuk meninjau metode pembelajaran dan assessment.";
        }

        if ($this->clo_attainment_rate < 75) {
            $recommendations[] = "Pencapaian CPMK perlu ditingkatkan. Review materi pembelajaran dan alokasi waktu.";
        }

        return implode("\n", $recommendations);
    }

    protected function determineAttainmentLevel(float $score): string
    {
        if ($score >= 85) return 'Sangat Tinggi';
        if ($score >= 75) return 'Tinggi';
        if ($score >= 65) return 'Sedang';
        return 'Rendah';
    }
}
