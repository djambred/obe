<?php

namespace App\Providers;

use App\Policies\ActivityPolicy;
use Aws\S3\S3Client;
use Filament\Actions\MountableAction;
use Filament\Notifications\Livewire\Notifications;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Illuminate\Filesystem\AwsS3V3Adapter;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use League\Flysystem\Filesystem;
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
            URL::forceScheme('https');
        }

        // Configure MinIO presigned URLs
        $this->configureMinioPresignedUrls();

        // Add macro untuk Livewire temporary upload URLs
        $this->addFilesystemMacros();

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
        $disk = Storage::disk('minio');

        // Override URL generation (untuk view/display files)
        Storage::disk('minio')->buildTemporaryUrlsUsing(function ($path, $expiration, $options) use ($disk) {
            $adapter = $disk->getAdapter();
            $client = $adapter->getClient();

            $command = $client->getCommand('GetObject', array_merge([
                'Bucket' => config('filesystems.disks.minio.bucket'),
                'Key' => $adapter->prefixer()->prefixPath($path),
            ], $options));

            $request = $client->createPresignedRequest($command, $expiration);
            $url = (string) $request->getUri();

            return $this->convertMinioUrl($url);
        });
    }

    /**
     * Add custom macros to FilesystemAdapter for Livewire
     */
    protected function addFilesystemMacros(): void
    {
        // Override url() method untuk convert MinIO URLs
        FilesystemAdapter::macro('url', function ($path) {
            $url = $this->config['url'] . '/' . ltrim($path, '/');
            return app(AppServiceProvider::class)->convertMinioUrl($url);
        });

        // Macro untuk generate temporary upload URL (Livewire FileUpload)
        FilesystemAdapter::macro('temporaryUploadUrl', function ($path, $expiration = null, array $options = []) {
            $adapter = $this->adapter;

            if (!method_exists($adapter, 'getClient')) {
                throw new \RuntimeException('Adapter does not support temporary URLs');
            }

            $client = $adapter->getClient();
            $expiration = $expiration ?? now()->addMinutes(5);

            $command = $client->getCommand('PutObject', array_merge([
                'Bucket' => $this->config['bucket'],
                'Key' => $adapter->prefixer()->prefixPath($path),
                'ACL' => 'public-read',
            ], $options));

            $request = $client->createPresignedRequest($command, $expiration);
            $url = (string) $request->getUri();

            return app(AppServiceProvider::class)->convertMinioUrl($url);
        });
    }

    /**
     * Convert internal MinIO URL ke public URL
     */
    public function convertMinioUrl(string $url): string
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
