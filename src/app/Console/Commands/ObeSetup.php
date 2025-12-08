<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ObeSetup extends Command
{
    protected $signature = 'obe:setup';

    protected $description = 'Setup OBE System 2025 - Create initial data';

    public function handle()
    {
        $this->info('OBE System 2025 - Initial Setup');
        $this->newLine();

        // Check database connection
        try {
            DB::connection()->getPdo();
            $this->info('✓ Database connection successful');
        } catch (\Exception $e) {
            $this->error('✗ Database connection failed: ' . $e->getMessage());
            return 1;
        }

        // Run migrations if needed
        if ($this->confirm('Run migrations?', true)) {
            $this->info('Running migrations...');
            $this->call('migrate', ['--force' => true]);
        }

        // Create university
        if ($this->confirm('Create sample university data?', true)) {
            $this->createSampleUniversity();
        }

        // Setup storage
        if ($this->confirm('Setup storage link?', true)) {
            $this->call('storage:link');
        }

        // Clear cache
        $this->info('Clearing cache...');
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');

        $this->newLine();
        $this->info('✓ Setup completed successfully!');
        $this->newLine();
        $this->info('Access your application at: ' . config('app.url'));

        return 0;
    }

    protected function createSampleUniversity()
    {
        $this->info('Creating sample university...');

        $university = \App\Models\University::firstOrCreate(
            ['code' => 'UNIV001'],
            [
                'name' => 'Universitas Contoh',
                'vision' => 'Menjadi universitas terkemuka di Indonesia',
                'mission' => [
                    'Menyelenggarakan pendidikan berkualitas',
                    'Melakukan penelitian yang inovatif',
                    'Memberikan pengabdian kepada masyarakat',
                ],
                'objectives' => [
                    'Menghasilkan lulusan yang kompeten',
                    'Mengembangkan ilmu pengetahuan dan teknologi',
                    'Memberikan kontribusi kepada masyarakat',
                ],
                'rector_name' => 'Prof. Dr. Rektor Contoh',
                'is_active' => true,
            ]
        );

        $this->info('✓ University created: ' . $university->name);

        // Create sample faculty
        $faculty = \App\Models\Faculty::firstOrCreate(
            ['code' => 'FAK001', 'university_id' => $university->id],
            [
                'name' => 'Fakultas Contoh',
                'vision' => 'Menjadi fakultas unggulan',
                'mission' => [
                    'Menyelenggarakan pendidikan berkualitas di bidang...',
                ],
                'dean_name' => 'Dr. Dekan Contoh',
                'is_active' => true,
            ]
        );

        $this->info('✓ Faculty created: ' . $faculty->name);

        // Create sample study program
        $studyProgram = \App\Models\StudyProgram::firstOrCreate(
            ['code' => 'PRODI001', 'faculty_id' => $faculty->id],
            [
                'name' => 'Program Studi Contoh',
                'level' => 'S1',
                'vision' => 'Menjadi program studi terdepan',
                'mission' => [
                    'Menghasilkan lulusan yang kompeten',
                ],
                'head_of_program' => 'Dr. Kaprodi Contoh',
                'degree_awarded' => 'S.Kom',
                'is_active' => true,
            ]
        );

        $this->info('✓ Study Program created: ' . $studyProgram->name);
    }
}
