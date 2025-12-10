<?php

namespace Database\Seeders;

use App\Models\SubCourseLearningOutcome;
use App\Models\CourseLearningOutcome;
use Illuminate\Database\Seeder;

class SubCourseLearningOutcomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Sub-CPMK dan Indikator Kinerja untuk CPMK
     */
    public function run(): void
    {
        // Seed Sub-CPMK untuk Algoritma dan Pemrograman
        $cpmkAlgo = CourseLearningOutcome::whereHas('course', function($q) {
            $q->where('code', 'ILK.102');
        })->get();

        if ($cpmkAlgo->count() > 0) {
            $this->seedAlgoritmaPemrogramanSubCPMK($cpmkAlgo);
        }

        // Seed Sub-CPMK untuk Machine Learning
        $cpmkML = CourseLearningOutcome::whereHas('course', function($q) {
            $q->where('code', 'ILK.401');
        })->get();

        if ($cpmkML->count() > 0) {
            $this->seedMachineLearningSubCPMK($cpmkML);
        }

        $this->command->info('Sub-CPMK seeded successfully!');
    }

    private function seedAlgoritmaPemrogramanSubCPMK($cpmkList)
    {
        // CPMK-01: Konsep dasar pemrograman
        $cpmk01 = $cpmkList->where('code', 'CPMK-01')->first();
        if ($cpmk01) {
            $subCpmk = [
                [
                    'course_learning_outcome_id' => $cpmk01->id,
                    'code' => 'Sub-CPMK-01.1',
                    'description' => 'Mahasiswa mampu menjelaskan konsep variabel, tipe data primitif, dan deklarasi variabel',
                    'bloom_cognitive_level' => 'C2',
                    'week_number' => 1,
                    'learning_materials' => 'Pengenalan pemrograman, konsep variabel dan tipe data',
                    'learning_methods' => 'Ceramah, diskusi, praktikum',
                    'duration_minutes' => 150,
                    'order' => 1,
                    'is_active' => true,
                ],
                [
                    'course_learning_outcome_id' => $cpmk01->id,
                    'code' => 'Sub-CPMK-01.2',
                    'description' => 'Mahasiswa mampu menggunakan operator aritmatika, relasional, dan logika',
                    'bloom_cognitive_level' => 'C3',
                    'week_number' => 2,
                    'learning_materials' => 'Operator dan ekspresi dalam pemrograman',
                    'learning_methods' => 'Praktikum, latihan soal',
                    'duration_minutes' => 150,
                    'order' => 2,
                    'is_active' => true,
                ],
            ];

            foreach ($subCpmk as $data) {
                SubCourseLearningOutcome::create($data);
            }
        }

        // CPMK-02: Struktur kontrol
        $cpmk02 = $cpmkList->where('code', 'CPMK-02')->first();
        if ($cpmk02) {
            $subCpmk = [
                [
                    'course_learning_outcome_id' => $cpmk02->id,
                    'code' => 'Sub-CPMK-02.1',
                    'description' => 'Mahasiswa mampu mengimplementasikan struktur percabangan (if, if-else, switch-case)',
                    'bloom_cognitive_level' => 'C3',
                    'week_number' => 3,
                    'learning_materials' => 'Struktur kontrol percabangan, decision making',
                    'learning_methods' => 'Praktikum, studi kasus',
                    'duration_minutes' => 150,
                    'order' => 1,
                    'is_active' => true,
                ],
                [
                    'course_learning_outcome_id' => $cpmk02->id,
                    'code' => 'Sub-CPMK-02.2',
                    'description' => 'Mahasiswa mampu mengimplementasikan struktur perulangan (for, while, do-while)',
                    'bloom_cognitive_level' => 'C3',
                    'week_number' => 4,
                    'learning_materials' => 'Struktur kontrol perulangan, iterasi',
                    'learning_methods' => 'Praktikum, problem solving',
                    'duration_minutes' => 150,
                    'order' => 2,
                    'is_active' => true,
                ],
                [
                    'course_learning_outcome_id' => $cpmk02->id,
                    'code' => 'Sub-CPMK-02.3',
                    'description' => 'Mahasiswa mampu menerapkan nested loop dan kombinasi struktur kontrol',
                    'bloom_cognitive_level' => 'C4',
                    'week_number' => 5,
                    'learning_materials' => 'Nested loop, kombinasi percabangan dan perulangan',
                    'learning_methods' => 'Praktikum, project-based learning',
                    'duration_minutes' => 150,
                    'order' => 3,
                    'is_active' => true,
                ],
            ];

            foreach ($subCpmk as $data) {
                SubCourseLearningOutcome::create($data);
            }
        }

        // CPMK-03: Fungsi dan prosedur
        $cpmk03 = $cpmkList->where('code', 'CPMK-03')->first();
        if ($cpmk03) {
            $subCpmk = [
                [
                    'course_learning_outcome_id' => $cpmk03->id,
                    'code' => 'Sub-CPMK-03.1',
                    'description' => 'Mahasiswa mampu membuat dan memanggil fungsi dengan parameter dan return value',
                    'bloom_cognitive_level' => 'C3',
                    'week_number' => 6,
                    'learning_materials' => 'Konsep fungsi, parameter, return value',
                    'learning_methods' => 'Praktikum, modular programming',
                    'duration_minutes' => 150,
                    'order' => 1,
                    'is_active' => true,
                ],
                [
                    'course_learning_outcome_id' => $cpmk03->id,
                    'code' => 'Sub-CPMK-03.2',
                    'description' => 'Mahasiswa mampu menerapkan konsep rekursi untuk menyelesaikan masalah',
                    'bloom_cognitive_level' => 'C4',
                    'week_number' => 7,
                    'learning_materials' => 'Rekursi, recursive thinking',
                    'learning_methods' => 'Problem-based learning, praktikum',
                    'duration_minutes' => 150,
                    'order' => 2,
                    'is_active' => true,
                ],
            ];

            foreach ($subCpmk as $data) {
                SubCourseLearningOutcome::create($data);
            }
        }
    }

    private function seedMachineLearningSubCPMK($cpmkList)
    {
        // CPMK-01: Konsep dasar ML
        $cpmk01 = $cpmkList->where('code', 'CPMK-01')->first();
        if ($cpmk01) {
            $subCpmk = [
                [
                    'course_learning_outcome_id' => $cpmk01->id,
                    'code' => 'Sub-CPMK-01.1',
                    'description' => 'Mahasiswa mampu menjelaskan perbedaan supervised, unsupervised, dan reinforcement learning',
                    'bloom_cognitive_level' => 'C2',
                    'week_number' => 1,
                    'learning_materials' => 'Introduction to Machine Learning, types of learning',
                    'learning_methods' => 'Ceramah, diskusi, video tutorial',
                    'duration_minutes' => 150,
                    'order' => 1,
                    'is_active' => true,
                ],
                [
                    'course_learning_outcome_id' => $cpmk01->id,
                    'code' => 'Sub-CPMK-01.2',
                    'description' => 'Mahasiswa mampu mengidentifikasi use case dan aplikasi machine learning di berbagai domain',
                    'bloom_cognitive_level' => 'C2',
                    'week_number' => 2,
                    'learning_materials' => 'Machine Learning applications, real-world case studies',
                    'learning_methods' => 'Studi kasus, presentasi',
                    'duration_minutes' => 150,
                    'order' => 2,
                    'is_active' => true,
                ],
            ];

            foreach ($subCpmk as $data) {
                SubCourseLearningOutcome::create($data);
            }
        }

        // CPMK-04: Data preprocessing dan feature engineering
        $cpmk04 = $cpmkList->where('code', 'CPMK-04')->first();
        if ($cpmk04) {
            $subCpmk = [
                [
                    'course_learning_outcome_id' => $cpmk04->id,
                    'code' => 'Sub-CPMK-04.1',
                    'description' => 'Mahasiswa mampu melakukan data cleaning, handling missing values, dan outlier detection',
                    'bloom_cognitive_level' => 'C3',
                    'week_number' => 8,
                    'learning_materials' => 'Data preprocessing, data cleaning techniques',
                    'learning_methods' => 'Praktikum dengan Python (Pandas, NumPy)',
                    'duration_minutes' => 150,
                    'order' => 1,
                    'is_active' => true,
                ],
                [
                    'course_learning_outcome_id' => $cpmk04->id,
                    'code' => 'Sub-CPMK-04.2',
                    'description' => 'Mahasiswa mampu melakukan feature scaling, encoding, dan feature selection',
                    'bloom_cognitive_level' => 'C3',
                    'week_number' => 9,
                    'learning_materials' => 'Feature engineering, feature transformation',
                    'learning_methods' => 'Praktikum, hands-on exercise',
                    'duration_minutes' => 150,
                    'order' => 2,
                    'is_active' => true,
                ],
                [
                    'course_learning_outcome_id' => $cpmk04->id,
                    'code' => 'Sub-CPMK-04.3',
                    'description' => 'Mahasiswa mampu mengevaluasi model menggunakan berbagai metrik (accuracy, precision, recall, F1-score)',
                    'bloom_cognitive_level' => 'C4',
                    'week_number' => 10,
                    'learning_materials' => 'Model evaluation, confusion matrix, metrics',
                    'learning_methods' => 'Praktikum, analisis hasil',
                    'duration_minutes' => 150,
                    'order' => 3,
                    'is_active' => true,
                ],
            ];

            foreach ($subCpmk as $data) {
                SubCourseLearningOutcome::create($data);
            }
        }
    }
}
