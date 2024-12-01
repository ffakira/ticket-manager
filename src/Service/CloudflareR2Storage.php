<?php

namespace App\Service;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;

class CloudflareR2Storage {
    private Filesystem $filesystem;

    public function __construct(
        string $accessKeyId,
        string $secretAccessKey,
        string $bucketName,
        string $region,
        string $endpoint
    ) {
        $client = new S3Client([
            'credentials' => [
                'key' => $accessKeyId,
                'secret' => $secretAccessKey,
            ],
            'region' => $region,
            'version' => 'latest',
            'endpoint' => $endpoint,
        ]);

        $adapter = new AwsS3V3Adapter($client, $bucketName);
        $this->filesystem = new Filesystem($adapter);
    }

    public function upload(string $path, string $contents): void {
        try {
            $this->filesystem->write($path, $contents);
        } catch (FilesystemException $e) {
            throw new \RuntimeException('Failed to upload file to Cloudflare R2 storage');
        }
    }

    public function download(string $path): string {
        try {
            return $this->filesystem->read($path);
        } catch (FilesystemException $e) {
            throw new \RuntimeException('Failed to download file from Cloudflare R2 storage');
        }
    }

    public function delete(string $path): void {
        try {
            $this->filesystem->delete($path);
        } catch (FilesystemException $e) {
            throw new \RuntimeException('Failed to delete file from Cloudflare R2 storage');
        }
    }
}
