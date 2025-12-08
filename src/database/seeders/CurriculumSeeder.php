<?php

namespace Database\Seeders;

use App\Models\Curriculum;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $ilmuKomputer = StudyProgram::where('code', 'ILK')->first();
        if (!$ilmuKomputer) {
            $this->command->error('Study Program Ilmu Komputer not found. Please run StudyProgramSeeder first.');
            return;
        }

        Curriculum::firstOrCreate([
            'study_program_id' => $ilmuKomputer->id,
            'code' => 'K2025',
        ], [
            'study_program_id' => $ilmuKomputer->id,
            'code' => 'K2025',
            'name' => 'Kurikulum 2025',
            'academic_year_start' => 2025,
            'academic_year_end' => 2029,
            'total_credits' => 144,
            'mandatory_university_credits' => 20,
            'mandatory_faculty_credits' => 12,
            'mandatory_program_credits' => 98,
            'elective_credits' => 8,
            'concentration_credits' => 6,
            'description' => 'Prodi kurikulum baru',
            'structure' => null,
            'concentration_list' => null,
            'is_active' => true,
            'effective_date' => '2025-08-01',
            'document_file' => null,
        ]);
    }
}
