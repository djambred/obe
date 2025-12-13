<?php

namespace App\Jobs;

use App\Models\Lecturer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncLecturerProfiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lecturer;

    public function __construct(Lecturer $lecturer)
    {
        $this->lecturer = $lecturer;
    }

    public function handle(): void
    {
        // Sync SINTA profile
        if (!empty($this->lecturer->sinta_id)) {
            $this->lecturer->syncSintaProfile();
        }

        // Sync Google Scholar profile
        if (!empty($this->lecturer->google_scholar_id)) {
            $this->lecturer->syncGoogleScholarProfile();
        }
    }
}
