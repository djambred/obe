<?php

namespace App\Console\Commands;

use App\Models\Lecturer;
use Illuminate\Console\Command;

class SyncLecturerProfilesCommand extends Command
{
    protected $signature = 'lecturer:sync-profiles {lecturer_id? : ID lecturer yang akan disync} {--all : Sync semua lecturer}';

    protected $description = 'Sinkronisasi profile SINTA dan Google Scholar untuk dosen';

    public function handle()
    {
        if ($this->option('all')) {
            $lecturers = Lecturer::whereNotNull('sinta_id')
                ->orWhereNotNull('google_scholar_id')
                ->get();

            $this->info("Syncing {$lecturers->count()} lecturer profiles...");

            foreach ($lecturers as $lecturer) {
                $this->syncLecturer($lecturer);
            }
        } else {
            $lecturerId = $this->argument('lecturer_id');
            if (!$lecturerId) {
                $this->error('Provide lecturer_id or use --all flag');
                return;
            }

            $lecturer = Lecturer::find($lecturerId);
            if (!$lecturer) {
                $this->error("Lecturer with ID {$lecturerId} not found");
                return;
            }

            $this->syncLecturer($lecturer);
        }

        $this->info('✅ Sync completed!');
    }

    protected function syncLecturer(Lecturer $lecturer): void
    {
        $this->line("Syncing {$lecturer->name}...");

        if (!empty($lecturer->sinta_id)) {
            $this->line("  → SINTA ID: {$lecturer->sinta_id}");
            if ($lecturer->syncSintaProfile()) {
                $this->info("    ✓ SINTA synced (Score: {$lecturer->sinta_score})");
            } else {
                $this->warn("    ✗ SINTA sync failed");
            }
        }

        if (!empty($lecturer->google_scholar_id)) {
            $this->line("  → Google Scholar ID: {$lecturer->google_scholar_id}");
            if ($lecturer->syncGoogleScholarProfile()) {
                $this->info("    ✓ Google Scholar synced (H-Index: {$lecturer->h_index})");
            } else {
                $this->warn("    ✗ Google Scholar sync failed");
            }
        }
    }
}
