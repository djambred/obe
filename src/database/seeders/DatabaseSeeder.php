<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            UniversitySeeder::class,
            FacultySeeder::class,
            StudyProgramSeeder::class,
            CurriculumSeeder::class,
            StudyFieldSeeder::class,
            CourseSeeder::class,
            GraduateProfileSeeder::class,
            LecturerSeeder::class,
            ProgramLearningOutcomeSeeder::class,  // CPL
            CourseLearningOutcomeSeeder::class,    // CPMK
            SubCourseLearningOutcomeSeeder::class, // Sub-CPMK & Indikator Kinerja
            RpsSeeder::class,                      // RPS
        ]);
    }
}
