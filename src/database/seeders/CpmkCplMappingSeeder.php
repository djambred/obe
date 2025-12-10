<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseLearningOutcome;
use App\Models\ProgramLearningOutcome;
use Illuminate\Database\Seeder;

class CpmkCplMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeder ini membuat mapping antara CPMK dan CPL untuk menampilkan matriks kontribusi.
     * Setiap CPMK dapat berkontribusi ke satu atau lebih CPL.
     */
    public function run(): void
    {
        // Get courses with CPMK
        $courses = Course::with(['courseLearningOutcomes', 'curriculum.studyProgram.programLearningOutcomes'])
            ->whereHas('courseLearningOutcomes')
            ->whereHas('curriculum')
            ->get();

        foreach ($courses as $course) {
            // Get CPL from study program
            if (!$course->curriculum || !$course->curriculum->studyProgram) {
                continue;
            }

            $cpls = $course->curriculum->studyProgram->programLearningOutcomes;

            if ($cpls->count() == 0) {
                continue;
            }

            $cpmks = $course->courseLearningOutcomes;
            $cplIds = $cpls->pluck('id')->toArray();
            $cplCount = count($cplIds);

            if ($cplCount == 0) {
                continue;
            }

            // Create mapping based on sample pattern
            // Contoh: CPMK 1 & 2 -> CPL 9
            //         CPMK 3 -> CPL 13
            //         CPMK 4 -> CPL 14

            foreach ($cpmks as $index => $cpmk) {
                // Detach existing relations
                $cpmk->programLearningOutcomes()->detach();

                // Strategy: Map each CPMK to 1-2 CPL based on index
                if ($index < $cplCount) {
                    // First mapping: sequential
                    $targetCplIndex = $index % $cplCount;
                    $cpmk->programLearningOutcomes()->attach($cplIds[$targetCplIndex]);

                    // Second mapping: some CPMK map to additional CPL
                    if ($index % 3 == 0 && $cplCount > 1) {
                        // Every 3rd CPMK maps to an additional CPL
                        $additionalCplIndex = (int)(($index + $cplCount/2) % $cplCount);
                        if ($additionalCplIndex != $targetCplIndex) {
                            $cpmk->programLearningOutcomes()->attach($cplIds[$additionalCplIndex]);
                        }
                    }
                }
            }

            $this->command->info("Mapped CPMK to CPL for: {$course->code} - {$course->name}");
        }

        $this->command->info('CPMK-CPL mapping completed successfully!');
    }
}
