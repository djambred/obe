<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class MinioSetup extends Command
{
    protected $signature = 'minio:setup';
    protected $description = 'Setup MinIO bucket and required directories';

    public function handle()
    {
        $this->info('Setting up MinIO storage...');

        try {
            $config = config('filesystems.disks.minio');
            $bucket = $config['bucket'];
            
            // Create S3 client directly
            $client = new S3Client([
                'version' => 'latest',
                'region' => $config['region'],
                'endpoint' => $config['endpoint'],
                'use_path_style_endpoint' => $config['use_path_style_endpoint'],
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
            ]);

            // Check if bucket exists
            $this->info("Checking bucket: {$bucket}");
            
            try {
                $client->headBucket(['Bucket' => $bucket]);
                $this->info("✓ Bucket already exists");
            } catch (AwsException $e) {
                if ($e->getAwsErrorCode() === 'NotFound' || $e->getStatusCode() === 404) {
                    $this->info("Creating bucket: {$bucket}");
                    $client->createBucket([
                        'Bucket' => $bucket,
                    ]);
                    
                    // Set bucket policy to public read
                    $policy = json_encode([
                        'Version' => '2012-10-17',
                        'Statement' => [
                            [
                                'Effect' => 'Allow',
                                'Principal' => ['AWS' => ['*']],
                                'Action' => ['s3:GetObject'],
                                'Resource' => ["arn:aws:s3:::{$bucket}/*"],
                            ],
                        ],
                    ]);
                    
                    try {
                        $client->putBucketPolicy([
                            'Bucket' => $bucket,
                            'Policy' => $policy,
                        ]);
                    } catch (AwsException $policyException) {
                        $this->warn("Could not set bucket policy (MinIO might not support this): " . $policyException->getMessage());
                    }
                    
                    $this->info("✓ Bucket created successfully");
                } else {
                    throw $e;
                }
            }

            // Create required directories using Storage facade
            $disk = Storage::disk('minio');
            
            $directories = [
                'universities/logos',
                'faculties/logos',
                'study-programs/logos',
                'temp',
            ];

            foreach ($directories as $dir) {
                if (!$disk->exists($dir)) {
                    $disk->makeDirectory($dir);
                    $this->info("✓ Created directory: {$dir}");
                } else {
                    $this->info("✓ Directory exists: {$dir}");
                }
            }

            // Test write
            $testFile = 'test.txt';
            $disk->put($testFile, 'MinIO setup test - ' . now());
            
            if ($disk->exists($testFile)) {
                $this->info("✓ Write test successful");
                $disk->delete($testFile);
            }

            $this->newLine();
            $this->info('✓ MinIO setup completed successfully!');
            $this->info('Bucket URL: ' . config('filesystems.disks.minio.url'));

            return self::SUCCESS;

        } catch (AwsException $e) {
            $this->error('MinIO S3 Error: ' . $e->getMessage());
            $this->error('Check your MinIO credentials and endpoint configuration.');
            return self::FAILURE;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
