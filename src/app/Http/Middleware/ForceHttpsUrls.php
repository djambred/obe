<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsUrls
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Jika request dari HTTPS, force semua URLs di response jadi HTTPS
        if ($request->secure()) {
            $content = $response->getContent();
            
            if ($content) {
                // Replace HTTP MinIO URLs jadi HTTPS
                $minioEndpoint = config('filesystems.disks.minio.endpoint');
                
                if ($minioEndpoint && str_starts_with($minioEndpoint, 'http://')) {
                    $httpUrl = $minioEndpoint;
                    $httpsUrl = str_replace('http://', 'https://', $minioEndpoint);
                    
                    // Replace di content
                    $content = str_replace($httpUrl, $httpsUrl, $content);
                    $response->setContent($content);
                }
            }
        }

        return $response;
    }
}
