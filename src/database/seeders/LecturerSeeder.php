<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class LecturerSeeder extends Seeder
{
    public function run(): void
    {
        $fik = Faculty::where('code', 'FIK')->first();
        $ilmuKomputer = StudyProgram::where('code', 'ILK')->first();

        if (!$fik || !$ilmuKomputer) {
            $this->command->error('Faculty or Study Program not found. Please run FacultySeeder and StudyProgramSeeder first.');
            return;
        }

        $lecturers = [
            [
                'faculty_id' => $fik->id,
                'study_program_id' => $ilmuKomputer->id,
                'nidn' => '0306067009',
                'nip' => null,
                'name' => 'BERNADETTA KWINTIANA ANE',
                'email' => 'bernadetta.ane@mnc.ac.id',
                'phone' => null,
                'employment_status' => 'Dosen Tetap',
                'academic_rank' => 'Lektor',
                'functional_position' => 'Lektor',
                'highest_education' => 'S2',
                'education_field' => 'Ilmu Komputer',
                'education_background' => [
                    'S2 - Ilmu Komputer',
                    'S1 - Teknik Informatika'
                ],
                'expertise_areas' => [
                    'Algoritma dan Pemrograman',
                    'Struktur Data',
                    'Pemrograman Berorientasi Objek'
                ],
                'research_interests' => [
                    'Software Engineering',
                    'Algorithm Design',
                    'Data Structures'
                ],
                'certifications' => null,
                'sinta_id' => null,
                'sinta_score' => null,
                'sinta_rank_national' => null,
                'sinta_rank_affiliation' => null,
                'sinta_publications' => null,
                'sinta_data' => null,
                'google_scholar_id' => null,
                'h_index' => null,
                'i10_index' => null,
                'total_citations' => null,
                'total_publications' => null,
                'google_scholar_data' => null,
                'photo' => null,
                'biography' => 'Dosen Ilmu Komputer dengan keahlian dalam bidang algoritma, pemrograman, dan struktur data.',
                'achievements' => null,
                'is_active' => true,
                'last_profile_sync' => null,
            ],
            [
                'faculty_id' => $fik->id,
                'study_program_id' => $ilmuKomputer->id,
                'nidn' => '9990637475',
                'nip' => null,
                'name' => 'EKO AMRI JAYA',
                'email' => 'eko.jaya@mnc.ac.id',
                'phone' => null,
                'employment_status' => 'Dosen Tetap',
                'academic_rank' => 'Lektor',
                'functional_position' => 'Lektor',
                'highest_education' => 'S2',
                'education_field' => 'Ilmu Komputer',
                'education_background' => [
                    'S2 - Ilmu Komputer',
                    'S1 - Sistem Informasi'
                ],
                'expertise_areas' => [
                    'Basis Data',
                    'Sistem Informasi',
                    'Pemrograman Web'
                ],
                'research_interests' => [
                    'Database Systems',
                    'Information Systems',
                    'Web Technologies'
                ],
                'certifications' => null,
                'sinta_id' => null,
                'sinta_score' => null,
                'sinta_rank_national' => null,
                'sinta_rank_affiliation' => null,
                'sinta_publications' => null,
                'sinta_data' => null,
                'google_scholar_id' => null,
                'h_index' => null,
                'i10_index' => null,
                'total_citations' => null,
                'total_publications' => null,
                'google_scholar_data' => null,
                'photo' => null,
                'biography' => 'Dosen Ilmu Komputer dengan keahlian dalam bidang basis data, sistem informasi, dan pemrograman web.',
                'achievements' => null,
                'is_active' => true,
                'last_profile_sync' => null,
            ],
            [
                'faculty_id' => $fik->id,
                'study_program_id' => $ilmuKomputer->id,
                'nidn' => '0316019003',
                'nip' => null,
                'name' => 'JEFRY SUNUPURWA ASRI',
                'email' => 'jefry.asri@mnc.ac.id',
                'phone' => null,
                'employment_status' => 'Dosen Tetap',
                'academic_rank' => 'Asisten Ahli',
                'functional_position' => 'Asisten Ahli',
                'highest_education' => 'S2',
                'education_field' => 'Ilmu Komputer',
                'education_background' => [
                    'S2 - Ilmu Komputer',
                    'S1 - Teknik Informatika'
                ],
                'expertise_areas' => [
                    'Jaringan Komputer',
                    'Keamanan Informasi',
                    'Sistem Operasi'
                ],
                'research_interests' => [
                    'Network Security',
                    'Cybersecurity',
                    'Computer Networks'
                ],
                'certifications' => [
                    'Certified Network Security Professional',
                    'Cisco CCNA'
                ],
                'sinta_id' => null,
                'sinta_score' => null,
                'sinta_rank_national' => null,
                'sinta_rank_affiliation' => null,
                'sinta_publications' => null,
                'sinta_data' => null,
                'google_scholar_id' => null,
                'h_index' => null,
                'i10_index' => null,
                'total_citations' => null,
                'total_publications' => null,
                'google_scholar_data' => null,
                'photo' => null,
                'biography' => 'Dosen Ilmu Komputer dengan keahlian dalam bidang jaringan komputer, keamanan informasi, dan sistem operasi.',
                'achievements' => null,
                'is_active' => true,
                'last_profile_sync' => null,
            ],
        ];

        foreach ($lecturers as $lecturerData) {
            Lecturer::create($lecturerData);
        }

        $this->command->info('Lecturers seeded successfully for Ilmu Komputer!');
    }
}
