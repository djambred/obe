<?php

namespace Database\Seeders;

use App\Models\StudyField;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class StudyFieldSeeder extends Seeder
{
    public function run(): void
    {
        $ilmuKomputer = StudyProgram::where('code', 'ILK')->first();
        if (!$ilmuKomputer) {
            $this->command->error('Study Program Ilmu Komputer not found. Please run StudyProgramSeeder first.');
            return;
        }

        // Contoh bahan kajian utama untuk Ilmu Komputer
        $fields = [
            [
                'code' => 'BK01',
                'name' => 'Algoritma dan Pemrograman',
                'description' => 'Dasar-dasar algoritma, logika pemrograman, dan implementasi dalam berbagai bahasa pemrograman.',
                'category' => 'Dasar Keilmuan',
                'sub_fields' => ['Algoritma Dasar', 'Struktur Data', 'Pemrograman Dasar', 'Pemrograman Lanjut'],
                'order' => 1,
                'is_active' => true,
            ],
            [
                'code' => 'BK02',
                'name' => 'Kecerdasan Buatan',
                'description' => 'Konsep, teknik, dan aplikasi artificial intelligence, machine learning, dan data science.',
                'category' => 'Keahlian Berkarya',
                'sub_fields' => ['Machine Learning', 'Deep Learning', 'Computer Vision', 'Natural Language Processing'],
                'order' => 2,
                'is_active' => true,
            ],
            [
                'code' => 'BK03',
                'name' => 'Rekayasa Perangkat Lunak',
                'description' => 'Prinsip, metodologi, dan praktik pengembangan perangkat lunak modern.',
                'category' => 'Keahlian Berkarya',
                'sub_fields' => ['Analisis Kebutuhan', 'Desain Sistem', 'Testing', 'DevOps'],
                'order' => 3,
                'is_active' => true,
            ],
            [
                'code' => 'BK04',
                'name' => 'Jaringan Komputer',
                'description' => 'Konsep dasar jaringan, protokol komunikasi, dan keamanan jaringan.',
                'category' => 'Keahlian Berkarya',
                'sub_fields' => ['Jaringan Dasar', 'Keamanan Jaringan', 'Cloud Computing'],
                'order' => 4,
                'is_active' => true,
            ],
            [
                'code' => 'BK05',
                'name' => 'Basis Data',
                'description' => 'Konsep, desain, dan implementasi sistem basis data relasional dan non-relasional.',
                'category' => 'Keahlian Berkarya',
                'sub_fields' => ['Database Relasional', 'NoSQL', 'Big Data', 'Data Warehousing'],
                'order' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'BK06',
                'name' => 'Etika dan Profesionalisme',
                'description' => 'Etika profesi, tanggung jawab sosial, dan perilaku profesional di bidang teknologi informasi.',
                'category' => 'Perilaku Berkarya',
                'sub_fields' => ['Etika Profesi', 'Tanggung Jawab Sosial', 'Komunikasi Profesional'],
                'order' => 6,
                'is_active' => true,
            ],
            [
                'code' => 'BK07',
                'name' => 'Kewirausahaan dan Inovasi',
                'description' => 'Pengembangan jiwa kewirausahaan dan inovasi dalam bidang teknologi.',
                'category' => 'Cara Berkehidupan Bermasyarakat',
                'sub_fields' => ['Startup', 'Manajemen Inovasi', 'Bisnis TI'],
                'order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($fields as $field) {
            StudyField::firstOrCreate([
                'study_program_id' => $ilmuKomputer->id,
                'code' => $field['code'],
            ], array_merge($field, [
                'study_program_id' => $ilmuKomputer->id,
            ]));
        }
    }
}
