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
        URL::forceScheme('https');

        // Configure MinIO URL resolver
        $this->configureMinioUrls();

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
     * Configure MinIO URLs untuk Livewire FileUpload
     * Handles both local & production environments
     */
    protected function configureMinioUrls(): void
    {
        // Extend Storage to resolve MinIO disk dinamis
        Storage::extend('minio-wrapper', function ($app, $config) {
            $disk = Storage::createS3Driver($config);

            // Wrap url() method dengan anonymous class
            return new class($disk, $config) extends FilesystemAdapter {
                private $wrappedDisk;

                public function __construct($disk, $config) {
                    $this->wrappedDisk = $disk;
                    parent::__construct($disk->getDriver(), $disk->getAdapter(), $config);
                }

                public function url($path): string
                {
                    // Build URL dari config (gunakan parent's protected $config)
                    $baseUrl = rtrim($this->config['url'], '/');
                    $url = $baseUrl . '/' . ltrim($path, '/');

                    // Convert ke HTTPS production URL
                    return $this->convertMinioUrl($url);
                }

                private function convertMinioUrl(string $url): string
                {
                    // Convert ke HTTPS
                    $url = str_replace('http://', 'https://', $url);

                    // Replace internal hostname dengan public hostname + /s3 path
                    $publicHost = parse_url(config('app.url'), PHP_URL_HOST);
                    $url = preg_replace('/(minio|localhost):9000/', $publicHost . '/s3', $url);

                    return $url;
                }

                public function __call($method, $parameters)
                {
                    return $this->wrappedDisk->$method(...$parameters);
                }
            };
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
