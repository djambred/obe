<?php

namespace Database\Seeders;

use App\Models\CourseLearningOutcome;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseLearningOutcomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * CPMK (Capaian Pembelajaran Mata Kuliah) untuk beberapa mata kuliah utama
     */
    public function run(): void
    {
        // Algoritma dan Pemrograman
        $algoProg = Course::where('code', 'ILK.102')->first();
        if ($algoProg) {
            $this->seedAlgoritmaPemrograman($algoProg->id);
        }

        // Struktur Data
        $strukturData = Course::where('code', 'ILK.201')->first();
        if ($strukturData) {
            $this->seedStrukturData($strukturData->id);
        }

        // Basis Data
        $basisData = Course::where('code', 'ILK.202')->first();
        if ($basisData) {
            $this->seedBasisData($basisData->id);
        }

        // Pemrograman Web
        $progWeb = Course::where('code', 'ILK.301')->first();
        if ($progWeb) {
            $this->seedPemrogramanWeb($progWeb->id);
        }

        // Machine Learning
        $ml = Course::where('code', 'ILK.401')->first();
        if ($ml) {
            $this->seedMachineLearning($ml->id);
        }

        $this->command->info('Course Learning Outcomes (CPMK) seeded successfully!');
    }

    private function seedAlgoritmaPemrograman($courseId)
    {
        $cpmk = [
            [
                'course_id' => $courseId,
                'code' => 'CPMK-01',
                'description' => 'Mahasiswa mampu memahami konsep dasar pemrograman, tipe data, variabel, dan operator',
                'bloom_cognitive_level' => 'C2',
                'weight' => 15.00,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-02',
                'description' => 'Mahasiswa mampu mengimplementasikan struktur kontrol (percabangan dan perulangan) dalam menyelesaikan masalah',
                'bloom_cognitive_level' => 'C3',
                'weight' => 20.00,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-03',
                'description' => 'Mahasiswa mampu merancang dan mengimplementasikan fungsi dan prosedur untuk modularisasi program',
                'bloom_cognitive_level' => 'C4',
                'weight' => 25.00,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-04',
                'description' => 'Mahasiswa mampu menganalisis kompleksitas algoritma dan memilih algoritma yang efisien',
                'bloom_cognitive_level' => 'C4',
                'weight' => 25.00,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-05',
                'description' => 'Mahasiswa mampu mengembangkan solusi pemrograman untuk masalah nyata dengan menerapkan konsep algoritma yang tepat',
                'bloom_cognitive_level' => 'C5',
                'weight' => 15.00,
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($cpmk as $data) {
            CourseLearningOutcome::create($data);
        }
    }

    private function seedStrukturData($courseId)
    {
        $cpmk = [
            [
                'course_id' => $courseId,
                'code' => 'CPMK-01',
                'description' => 'Mahasiswa mampu memahami konsep dan karakteristik struktur data dasar (array, linked list, stack, queue)',
                'bloom_cognitive_level' => 'C2',
                'weight' => 20.00,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-02',
                'description' => 'Mahasiswa mampu mengimplementasikan struktur data linear (array, linked list, stack, queue) dalam pemrograman',
                'bloom_cognitive_level' => 'C3',
                'weight' => 25.00,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-03',
                'description' => 'Mahasiswa mampu menganalisis dan mengimplementasikan struktur data non-linear (tree, graph, hash table)',
                'bloom_cognitive_level' => 'C4',
                'weight' => 25.00,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-04',
                'description' => 'Mahasiswa mampu mengevaluasi efisiensi waktu dan memori dari berbagai struktur data untuk kasus penggunaan spesifik',
                'bloom_cognitive_level' => 'C5',
                'weight' => 20.00,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-05',
                'description' => 'Mahasiswa mampu merancang dan mengembangkan aplikasi dengan memilih struktur data yang optimal',
                'bloom_cognitive_level' => 'C6',
                'weight' => 10.00,
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($cpmk as $data) {
            CourseLearningOutcome::create($data);
        }
    }

    private function seedBasisData($courseId)
    {
        $cpmk = [
            [
                'course_id' => $courseId,
                'code' => 'CPMK-01',
                'description' => 'Mahasiswa mampu memahami konsep dasar sistem basis data, model data, dan arsitektur DBMS',
                'bloom_cognitive_level' => 'C2',
                'weight' => 15.00,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-02',
                'description' => 'Mahasiswa mampu merancang database menggunakan Entity-Relationship Diagram (ERD) dan normalisasi',
                'bloom_cognitive_level' => 'C4',
                'weight' => 25.00,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-03',
                'description' => 'Mahasiswa mampu mengimplementasikan database relasional menggunakan SQL (DDL, DML, DCL)',
                'bloom_cognitive_level' => 'C3',
                'weight' => 25.00,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-04',
                'description' => 'Mahasiswa mampu menganalisis dan mengoptimasi query SQL untuk meningkatkan performa database',
                'bloom_cognitive_level' => 'C4',
                'weight' => 20.00,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-05',
                'description' => 'Mahasiswa mampu mengembangkan aplikasi database yang terintegrasi dengan mempertimbangkan aspek keamanan dan integritas data',
                'bloom_cognitive_level' => 'C5',
                'weight' => 15.00,
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($cpmk as $data) {
            CourseLearningOutcome::create($data);
        }
    }

    private function seedPemrogramanWeb($courseId)
    {
        $cpmk = [
            [
                'course_id' => $courseId,
                'code' => 'CPMK-01',
                'description' => 'Mahasiswa mampu memahami arsitektur web, protokol HTTP, dan konsep client-server',
                'bloom_cognitive_level' => 'C2',
                'weight' => 15.00,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-02',
                'description' => 'Mahasiswa mampu mengimplementasikan halaman web responsif menggunakan HTML5, CSS3, dan JavaScript',
                'bloom_cognitive_level' => 'C3',
                'weight' => 25.00,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-03',
                'description' => 'Mahasiswa mampu mengembangkan aplikasi web dinamis menggunakan framework modern (React/Vue/Angular)',
                'bloom_cognitive_level' => 'C4',
                'weight' => 25.00,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-04',
                'description' => 'Mahasiswa mampu mengintegrasikan frontend dengan backend melalui RESTful API atau GraphQL',
                'bloom_cognitive_level' => 'C4',
                'weight' => 20.00,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-05',
                'description' => 'Mahasiswa mampu mengevaluasi dan menerapkan best practices dalam pengembangan web termasuk keamanan, performa, dan accessibility',
                'bloom_cognitive_level' => 'C5',
                'weight' => 15.00,
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($cpmk as $data) {
            CourseLearningOutcome::create($data);
        }
    }

    private function seedMachineLearning($courseId)
    {
        $cpmk = [
            [
                'course_id' => $courseId,
                'code' => 'CPMK-01',
                'description' => 'Mahasiswa mampu memahami konsep dasar machine learning, jenis-jenis learning, dan aplikasinya',
                'bloom_cognitive_level' => 'C2',
                'weight' => 15.00,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-02',
                'description' => 'Mahasiswa mampu mengimplementasikan algoritma supervised learning (regression, classification)',
                'bloom_cognitive_level' => 'C3',
                'weight' => 25.00,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-03',
                'description' => 'Mahasiswa mampu mengimplementasikan algoritma unsupervised learning (clustering, dimensionality reduction)',
                'bloom_cognitive_level' => 'C3',
                'weight' => 20.00,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-04',
                'description' => 'Mahasiswa mampu melakukan preprocessing data, feature engineering, dan evaluasi model machine learning',
                'bloom_cognitive_level' => 'C4',
                'weight' => 25.00,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'course_id' => $courseId,
                'code' => 'CPMK-05',
                'description' => 'Mahasiswa mampu merancang dan mengembangkan solusi machine learning untuk permasalahan real-world dengan melakukan tuning dan optimization',
                'bloom_cognitive_level' => 'C5',
                'weight' => 15.00,
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($cpmk as $data) {
            CourseLearningOutcome::create($data);
        }
    }
}
