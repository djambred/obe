<?php

namespace App\Providers;

use App\Policies\ActivityPolicy;
use Filament\Actions\MountableAction;
use Filament\Notifications\Livewire\Notifications;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS URLs in production
        if (request()->secure()) {
            \Illuminate\Support\Facades\URL::forceScheme('https');

            // Override MinIO presigned URL generation untuk production
            $this->configureMinioPresignedUrls();
        }

        Gate::policy(Activity::class, ActivityPolicy::class);
        Page::formActionsAlignment(Alignment::Right);
        Notifications::alignment(Alignment::End);
        Notifications::verticalAlignment(VerticalAlignment::End);
        Page::$reportValidationErrorUsing = function (ValidationException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        };
        MountableAction::configureUsing(function (MountableAction $action) {
            $action->modalFooterActionsAlignment(Alignment::Right);
        });
    }

    /**
     * Configure MinIO presigned URLs untuk Livewire FileUpload
     * Handles both local & production environments
     */
    protected function configureMinioPresignedUrls(): void
    {
        $disk = \Illuminate\Support\Facades\Storage::disk('minio');

        // Override temporary URL generation (untuk download/view)
        $disk->buildTemporaryUrlsUsing(function ($path, $expiration, $options) {
            $adapter = \Illuminate\Support\Facades\Storage::disk('minio')->getAdapter();
            $client = $adapter->getClient();

            $command = $client->getCommand('GetObject', array_merge([
                'Bucket' => config('filesystems.disks.minio.bucket'),
                'Key' => $adapter->prefixer()->prefixPath($path),
            ], $options));

            $request = $client->createPresignedRequest($command, $expiration);
            $url = (string) $request->getUri();

            return $this->convertMinioUrl($url);
        });

        // Override temporary upload URL generation (untuk Livewire FileUpload)
        $disk->buildPutTemporaryUrlsUsing(function ($path, $expiration, $options) {
            $adapter = \Illuminate\Support\Facades\Storage::disk('minio')->getAdapter();
            $client = $adapter->getClient();

            // Livewire FileUpload butuh public-read ACL
            $command = $client->getCommand('PutObject', array_merge([
                'Bucket' => config('filesystems.disks.minio.bucket'),
                'Key' => $adapter->prefixer()->prefixPath($path),
                'ACL' => 'public-read', // Important untuk Livewire
            ], $options));

            $request = $client->createPresignedRequest($command, $expiration);
            $url = (string) $request->getUri();

            return $this->convertMinioUrl($url);
        });
    }

    /**
     * Convert internal MinIO URL ke public URL
     */
    protected function convertMinioUrl(string $url): string
    {
        // Jika HTTPS request, convert ke HTTPS
        if (request()->secure()) {
            $url = str_replace('http://', 'https://', $url);
        }

        // Replace internal hostname dengan public hostname + /s3 path
        $publicHost = parse_url(config('app.url'), PHP_URL_HOST);

        // Replace minio:9000 atau localhost:9000 dengan domain/s3
        $url = preg_replace(
            '/(minio|localhost):9000/',
            $publicHost . '/s3',
            $url
        );

        return $url;
    }
}
