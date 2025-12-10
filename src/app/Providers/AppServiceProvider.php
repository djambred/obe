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
        }
        
        // Override MinIO URL generator untuk force HTTPS dan replace hostname
        \Illuminate\Support\Facades\Storage::disk('minio')
            ->buildTemporaryUrlsUsing(function ($path, $expiration, $options) {
                $adapter = \Illuminate\Support\Facades\Storage::disk('minio')->getAdapter();
                $client = $adapter->getClient();
                
                $command = $client->getCommand('GetObject', array_merge([
                    'Bucket' => config('filesystems.disks.minio.bucket'),
                    'Key' => $adapter->prefixer()->prefixPath($path),
                ], $options));
                
                $request = $client->createPresignedRequest(
                    $command,
                    $expiration
                );
                
                $url = (string) $request->getUri();
                
                // Jika request dari HTTPS, force semua URL jadi HTTPS
                if (request()->secure()) {
                    $url = str_replace('http://', 'https://', $url);
                    
                    // Replace internal hostname dengan public hostname
                    $publicHost = parse_url(config('app.url'), PHP_URL_HOST);
                    $url = str_replace('minio:9000', $publicHost . '/s3', $url);
                }
                
                return $url;
            });
        
        \Illuminate\Support\Facades\Storage::disk('minio')
            ->buildPutTemporaryUrlsUsing(function ($path, $expiration, $options) {
                $adapter = \Illuminate\Support\Facades\Storage::disk('minio')->getAdapter();
                $client = $adapter->getClient();
                
                $command = $client->getCommand('PutObject', array_merge([
                    'Bucket' => config('filesystems.disks.minio.bucket'),
                    'Key' => $adapter->prefixer()->prefixPath($path),
                ], $options));
                
                $request = $client->createPresignedRequest(
                    $command,
                    $expiration
                );
                
                $url = (string) $request->getUri();
                
                // Jika request dari HTTPS, force semua URL jadi HTTPS
                if (request()->secure()) {
                    $url = str_replace('http://', 'https://', $url);
                    
                    // Replace internal hostname dengan public hostname
                    $publicHost = parse_url(config('app.url'), PHP_URL_HOST);
                    $url = str_replace('minio:9000', $publicHost . '/s3', $url);
                }
                
                return $url;
            });
        
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
}
