<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curriculum;
use App\Models\Course;
use App\Models\StudyProgram;
use App\Models\Faculty;

class SistemInformasiKurikulumSeeder extends Seeder
{
    public function run()
    {
        // Buat Study Program Sistem Informasi
        // Buat atau ambil Fakultas default
        $faculty = Faculty::firstOrCreate([
            'name' => 'Fakultas Industri Kreatif',
            'code' => 'FIK',
        ]);

        $studyProgram = StudyProgram::firstOrCreate([
            'name' => 'Sistem Informasi',
            'code' => 'SI',
            'level' => 'S1',
            'faculty_id' => $faculty->id,
        ]);

        // Buat Curriculum
        $curriculum = Curriculum::firstOrCreate([
            'name' => 'Kurikulum 2025 Sistem Informasi',
            'code' => 'SI-2025',
            'academic_year_start' => 2025,
            'academic_year_end' => 2029,
            'study_program_id' => $studyProgram->id,
        ]);

        // Data mata kuliah per semester (diambil dari gambar)
        $courses = [
            // SEMESTER 1
            ['code' => 'SI101', 'name' => 'Matematika Dasar', 'credits' => 3, 'semester' => 1, 'type' => 'Wajib Prodi'],
            ['code' => 'SI102', 'name' => 'Algoritma dan Pemrograman', 'credits' => 3, 'semester' => 1, 'type' => 'Wajib Prodi'],
            ['code' => 'SI103', 'name' => 'Bahasa Inggris', 'credits' => 2, 'semester' => 1, 'type' => 'Wajib Prodi'],
            ['code' => 'SI104', 'name' => 'Pengantar Teknologi Informasi', 'credits' => 3, 'semester' => 1, 'type' => 'Wajib Prodi'],
            ['code' => 'SI105', 'name' => 'Dasar Sistem Informasi', 'credits' => 3, 'semester' => 1, 'type' => 'Wajib Prodi'],
            ['code' => 'SI106', 'name' => 'Kewirausahaan', 'credits' => 3, 'semester' => 1, 'type' => 'Wajib Prodi'],
            ['code' => 'SI107', 'name' => 'Agama', 'credits' => 2, 'semester' => 1, 'type' => 'Wajib Universitas'],
            // SEMESTER 2
            ['code' => 'SI201', 'name' => 'Transformasi Digital', 'credits' => 3, 'semester' => 2, 'type' => 'Wajib Prodi'],
            ['code' => 'SI202', 'name' => 'Statistika dan Probabilitas', 'credits' => 3, 'semester' => 2, 'type' => 'Wajib Prodi'],
            ['code' => 'SI203', 'name' => 'Pemrograman Web Dasar', 'credits' => 3, 'semester' => 2, 'type' => 'Wajib Prodi'],
            ['code' => 'SI204', 'name' => 'Jaringan Komputer', 'credits' => 3, 'semester' => 2, 'type' => 'Wajib Prodi'],
            ['code' => 'SI205', 'name' => 'Interaksi Manusia dan Komputer', 'credits' => 3, 'semester' => 2, 'type' => 'Wajib Prodi'],
            ['code' => 'SI206', 'name' => 'Basis Data', 'credits' => 3, 'semester' => 2, 'type' => 'Wajib Prodi'],
            ['code' => 'SI207', 'name' => 'Bahasa Inggris', 'credits' => 2, 'semester' => 2, 'type' => 'Wajib Prodi'],
            // SEMESTER 3
            ['code' => 'SI301', 'name' => 'Analisis dan Perancangan Sistem Informasi', 'credits' => 3, 'semester' => 3, 'type' => 'Wajib Prodi'],
            ['code' => 'SI302', 'name' => 'Manajemen Basis Data', 'credits' => 3, 'semester' => 3, 'type' => 'Wajib Prodi'],
            ['code' => 'SI303', 'name' => 'Audit Sistem Informasi', 'credits' => 3, 'semester' => 3, 'type' => 'Wajib Prodi'],
            ['code' => 'SI304', 'name' => 'Data Mining', 'credits' => 3, 'semester' => 3, 'type' => 'Wajib Prodi'],
            ['code' => 'SI305', 'name' => 'Mobile App Development', 'credits' => 4, 'semester' => 3, 'type' => 'Wajib Prodi'],
            ['code' => 'SI306', 'name' => 'Seminar Usulan Penelitian', 'credits' => 3, 'semester' => 3, 'type' => 'Wajib Prodi'],
            // SEMESTER 4
            ['code' => 'SI401', 'name' => 'Software Architecture & Design Patterns', 'credits' => 3, 'semester' => 4, 'type' => 'Wajib Prodi'],
            ['code' => 'SI402', 'name' => 'Hybrid Application Development', 'credits' => 3, 'semester' => 4, 'type' => 'Wajib Prodi'],
            ['code' => 'SI403', 'name' => 'DevOps & CI/CD Fundamentals', 'credits' => 3, 'semester' => 4, 'type' => 'Wajib Prodi'],
            ['code' => 'SI404', 'name' => 'Enterprise Systems Modelling', 'credits' => 3, 'semester' => 4, 'type' => 'Wajib Prodi'],
            ['code' => 'SI405', 'name' => 'Software Testing & Quality Assurance', 'credits' => 3, 'semester' => 4, 'type' => 'Wajib Prodi'],
            ['code' => 'SI406', 'name' => 'Customer Relationship Management', 'credits' => 3, 'semester' => 4, 'type' => 'Wajib Prodi'],
            // SEMESTER 5
            ['code' => 'SI501', 'name' => 'Cross-Platform Application Development', 'credits' => 3, 'semester' => 5, 'type' => 'Wajib Prodi'],
            ['code' => 'SI502', 'name' => 'Advanced Mobile Application Development', 'credits' => 3, 'semester' => 5, 'type' => 'Wajib Prodi'],
            ['code' => 'SI503', 'name' => 'Sustainable Software Development', 'credits' => 3, 'semester' => 5, 'type' => 'Wajib Prodi'],
            ['code' => 'SI504', 'name' => 'Proyek Sistem Informasi', 'credits' => 3, 'semester' => 5, 'type' => 'Wajib Prodi'],
            ['code' => 'SI505', 'name' => 'Solution Architect', 'credits' => 3, 'semester' => 5, 'type' => 'Wajib Prodi'],
            ['code' => 'SI506', 'name' => 'Agile Information System Project Management', 'credits' => 3, 'semester' => 5, 'type' => 'Wajib Prodi'],
            // SEMESTER 6
            ['code' => 'SI601', 'name' => 'Digital Marketing', 'credits' => 2, 'semester' => 6, 'type' => 'Pilihan'],
            ['code' => 'SI602', 'name' => 'Startup Digital', 'credits' => 2, 'semester' => 6, 'type' => 'Pilihan'],
            ['code' => 'SI603', 'name' => 'Bahasa Inggris', 'credits' => 2, 'semester' => 6, 'type' => 'Wajib Prodi'],
            ['code' => 'SI604', 'name' => 'Etika Profesi dan Profesional', 'credits' => 2, 'semester' => 6, 'type' => 'Wajib Prodi'],
            // SEMESTER 7
            ['code' => 'SI701', 'name' => 'Kerja Praktik / Magang', 'credits' => 6, 'semester' => 7, 'type' => 'Wajib Prodi'],
            ['code' => 'SI702', 'name' => 'Seminar Tugas Akhir', 'credits' => 2, 'semester' => 7, 'type' => 'Wajib Prodi'],
            ['code' => 'SI703', 'name' => 'Tugas Akhir / Skripsi', 'credits' => 6, 'semester' => 7, 'type' => 'Wajib Prodi'],
            ['code' => 'SI704', 'name' => 'Kepemimpinan dan Komunikasi', 'credits' => 2, 'semester' => 7, 'type' => 'Pilihan'],
        ];

        foreach ($courses as $course) {
            Course::firstOrCreate([
                'code' => $course['code'],
                'name' => $course['name'],
                'english_name' => null,
                'type' => $course['type'],
                'concentration' => null,
                'credits' => $course['credits'],
                'theory_credits' => $course['credits'] > 2 ? 2 : 1,
                'practice_credits' => $course['credits'] > 2 ? $course['credits'] - 2 : 1,
                'field_credits' => 0,
                'semester' => $course['semester'],
                'description' => null,
                'prerequisites' => null,
                'corequisites' => null,
                'learning_media' => null,
                'learning_methods' => null,
                'assessment_methods' => null,
                'references' => null,
                'is_active' => true,
                'curriculum_id' => $curriculum->id,
                'study_program_id' => $studyProgram->id,
            ]);
        }
    }
}
