<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class MinioPublic extends Command
{
    protected $signature = 'minio:public';
    protected $description = 'Set MinIO bucket to public with proper policy';

    public function handle()
    {
        $this->info('Setting MinIO bucket to public...');

        try {
            $config = config('filesystems.disks.minio');
            $bucket = $config['bucket'];

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

            $this->info("Setting bucket '{$bucket}' to public...");

            // Set bucket policy to public read
            $policy = [
                'Version' => '2012-10-17',
                'Statement' => [
                    [
                        'Effect' => 'Allow',
                        'Principal' => '*',
                        'Action' => 's3:GetObject',
                        'Resource' => "arn:aws:s3:::{$bucket}/*",
                    ],
                ],
            ];

            $client->putBucketPolicy([
                'Bucket' => $bucket,
                'Policy' => json_encode($policy),
            ]);

            $this->info("✓ Bucket policy updated successfully");

            // Set bucket versioning (optional but recommended)
            try {
                $client->putBucketVersioning([
                    'Bucket' => $bucket,
                    'VersioningConfiguration' => [
                        'Status' => 'Enabled',
                    ],
                ]);
                $this->info("✓ Versioning enabled");
            } catch (AwsException $e) {
                $this->warn("Could not enable versioning: " . $e->getMessage());
            }

            // Check current policy
            $this->info("\nCurrent bucket policy:");
            try {
                $result = $client->getBucketPolicy(['Bucket' => $bucket]);
                $this->line($result['Policy']);
            } catch (AwsException $e) {
                $this->warn("Could not retrieve policy: " . $e->getMessage());
            }

            $this->newLine();
            $this->info("✓ Bucket '{$bucket}' is now public!");
            $this->info("Access files via: " . config('filesystems.disks.minio.url'));

            return self::SUCCESS;

        } catch (AwsException $e) {
            $this->error('MinIO Error: ' . $e->getMessage());
            return self::FAILURE;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
