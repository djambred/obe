<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseLearningOutcome;
use App\Models\PerformanceIndicator;
use Illuminate\Database\Seeder;

class PerformanceIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeder ini membuat indikator kinerja (Performance Indicator) untuk mata kuliah
     * yang sudah memiliki CPMK. Setiap mata kuliah akan memiliki berbagai jenis penilaian:
     * - Tugas Besar (TB): 1 tugas dengan bobot 20%
     * - UTS: 1 ujian dengan bobot 30%
     * - UAS: 1 ujian dengan bobot 30%
     * - Quiz (Q1-Q2): 2 kuis dengan bobot masing-masing 5% = 10% total
     * - Tugas Individu (T1-T2): 2 tugas dengan bobot masing-masing 5% = 10% total
     *
     * Total bobot = 100% dan didistribusikan ke CPMK yang berbeda
     * untuk memastikan setiap CPMK diukur secara komprehensif dan seimbang.
     *
     * Skala nilai menggunakan standar Universitas (A, B, C, D, E).
     */
    public function run(): void
    {
        // Hapus data lama
        PerformanceIndicator::truncate();

        // Ambil mata kuliah yang memiliki CPMK
        $courses = Course::with('courseLearningOutcomes')
            ->whereHas('courseLearningOutcomes')
            ->get();

        foreach ($courses as $course) {
            $cpmks = $course->courseLearningOutcomes;

            if ($cpmks->count() < 3) {
                // Skip jika CPMK kurang dari 3 (tidak cukup untuk distribusi)
                continue;
            }

            $this->createPerformanceIndicators($course, $cpmks);
        }

        $this->command->info('Performance Indicators created successfully!');
        $this->command->info('Total indicators: ' . PerformanceIndicator::count());
    }

    /**
     * Membuat indikator kinerja untuk satu mata kuliah
     */
    private function createPerformanceIndicators($course, $cpmks)
    {
        $cpmkIds = $cpmks->pluck('id')->toArray();
        $cpmkCount = count($cpmkIds);

        // Template rubrik OBE standar
        $standardRubric = "- Sangat Baik (86-100): Menunjukkan pemahaman yang sangat mendalam dan mampu mengaplikasikan konsep dengan sangat baik
- Baik (71-85): Menunjukkan pemahaman yang baik dan mampu mengaplikasikan konsep dengan baik
- Cukup (56-70): Menunjukkan pemahaman yang cukup dan mampu mengaplikasikan konsep dasar
- Kurang (0-55): Belum menunjukkan pemahaman yang memadai";

        // Skala nilai standar Universitas
        $universityGradingScale = "A: 86-100\nB: 71-85\nC: 56-70\nD: 41-55\nE: 0-40";

        $order = 1;

        // 1. TUGAS BESAR (1 item, bobot 20%)
        // Didistribusikan ke beberapa CPMK
        PerformanceIndicator::create([
            'course_learning_outcome_id' => $cpmkIds[0],
            'code' => 'TB',
            'description' => 'Tugas Besar: Analisis, perancangan, implementasi, dan dokumentasi proyek komprehensif',
            'criteria' => 'Tugas besar mencakup seluruh aspek pembelajaran dalam satu proyek terintegrasi, dikerjakan secara berkelompok atau individu sesuai instruksi, diserahkan tepat waktu dengan dokumentasi lengkap',
            'rubric' => $standardRubric,
            'weight' => 20.00,
            'assessment_type' => 'Proyek',
            'passing_grade' => 56.00,
            'grading_scale' => $universityGradingScale,
            'grading_scale_level' => 'Universitas',
            'order' => $order++,
            'is_active' => true,
        ]);

        // 2. UTS (1 item, bobot 30%)
        // Fokus pada materi pertengahan semester
        PerformanceIndicator::create([
            'course_learning_outcome_id' => $cpmkIds[$cpmkCount > 2 ? 1 : 0],
            'code' => 'UTS',
            'description' => 'Ujian Tengah Semester: Penguasaan materi minggu 1-7',
            'criteria' => 'Ujian Tengah Semester mencakup seluruh materi yang telah dibahas hingga minggu ke-7, menguji pemahaman konseptual, kemampuan aplikasi, dan pemecahan masalah',
            'rubric' => $standardRubric,
            'weight' => 30.00,
            'assessment_type' => 'Ujian Tulis',
            'passing_grade' => 56.00,
            'grading_scale' => $universityGradingScale,
            'grading_scale_level' => 'Universitas',
            'order' => $order++,
            'is_active' => true,
        ]);

        // 3. UAS (1 item, bobot 30%)
        // Fokus pada materi akhir semester dan integrasi
        PerformanceIndicator::create([
            'course_learning_outcome_id' => $cpmkIds[$cpmkCount > 2 ? 2 : 0],
            'code' => 'UAS',
            'description' => 'Ujian Akhir Semester: Penguasaan materi minggu 8-14 dan integrasi konsep',
            'criteria' => 'Ujian Akhir Semester mencakup materi minggu 8-14 dengan penekanan pada integrasi seluruh konsep pembelajaran, sintesis, evaluasi, dan studi kasus komprehensif',
            'rubric' => $standardRubric,
            'weight' => 30.00,
            'assessment_type' => 'Ujian Tulis',
            'passing_grade' => 56.00,
            'grading_scale' => $universityGradingScale,
            'grading_scale_level' => 'Universitas',
            'order' => $order++,
            'is_active' => true,
        ]);

        // 4. KUIS (2 kuis, bobot 5% each = 10% total)
        $kuisDescriptions = [
            'Pemahaman konsep dasar dan penerapan teori',
            'Analisis dan evaluasi materi pembelajaran'
        ];

        foreach ($kuisDescriptions as $index => $desc) {
            // Distribusi CPMK dengan offset untuk variasi
            $targetCpmkIndex = ($index + $cpmkCount - 2) % $cpmkCount;

            PerformanceIndicator::create([
                'course_learning_outcome_id' => $cpmkIds[$targetCpmkIndex],
                'code' => 'Q' . ($index + 1),
                'description' => $desc,
                'criteria' => 'Kuis dikerjakan secara individu dalam waktu yang ditentukan, menguji pemahaman konsep dan kemampuan aplikasi dasar',
                'rubric' => $standardRubric,
                'weight' => 5.00,
                'assessment_type' => 'Quiz',
                'passing_grade' => 56.00,
                'grading_scale' => $universityGradingScale,
                'grading_scale_level' => 'Universitas',
                'order' => $order++,
                'is_active' => true,
            ]);
        }

        // 5. TUGAS INDIVIDU (2 tugas, bobot 5% each = 10% total)
        $tugasDescriptions = [
            'Implementasi konsep dalam kasus sederhana',
            'Analisis dan dokumentasi hasil pembelajaran'
        ];

        foreach ($tugasDescriptions as $index => $desc) {
            // Distribusi CPMK
            $targetCpmkIndex = ($index + $cpmkCount - 1) % $cpmkCount;

            PerformanceIndicator::create([
                'course_learning_outcome_id' => $cpmkIds[$targetCpmkIndex],
                'code' => 'T' . ($index + 1),
                'description' => $desc,
                'criteria' => 'Tugas dikerjakan secara mandiri sesuai instruksi, diserahkan tepat waktu, dan memenuhi standar kualitas yang ditetapkan',
                'rubric' => $standardRubric,
                'weight' => 5.00,
                'assessment_type' => 'Tugas Individu',
                'passing_grade' => 56.00,
                'grading_scale' => $universityGradingScale,
                'grading_scale_level' => 'Universitas',
                'order' => $order++,
                'is_active' => true,
            ]);
        }

        $this->command->info("Created indicators for: {$course->code} - {$course->name}");
    }
}
