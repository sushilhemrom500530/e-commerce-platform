<?php

namespace Tests\Unit;

use App\Services\FileUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadServiceTest extends TestCase
{
    public function test_upload_returns_null_when_no_file_given(): void
    {
        $this->assertNull(FileUploadService::upload(null));
    }

    public function test_upload_stores_file_to_public_disk_by_default_in_testing(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');

        $path = FileUploadService::upload($file, 'products');

        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_delete_removes_local_file(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');
        $path = FileUploadService::upload($file, 'products');

        $this->assertTrue(FileUploadService::delete($path));
        Storage::disk('public')->assertMissing($path);
    }

    public function test_delete_returns_false_for_empty_input(): void
    {
        $this->assertFalse(FileUploadService::delete(null));
        $this->assertFalse(FileUploadService::delete(''));
    }

    public function test_delete_triggers_cloudinary_destroy_for_cloudinary_urls(): void
    {
        Config::set('services.cloudinary.cloud_name', 'dz0hdhcn0');
        Config::set('services.cloudinary.api_key', '312399472434817');
        Config::set('services.cloudinary.api_secret', 'secret123');

        Http::fake([
            'api.cloudinary.com/*' => Http::response(['result' => 'ok'], 200),
        ]);

        $cloudinaryUrl = 'https://res.cloudinary.com/dz0hdhcn0/image/upload/v1793944726/products/sample_img.png';
        $result = FileUploadService::delete($cloudinaryUrl);

        $this->assertTrue($result);
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'image/destroy')
                && $request['public_id'] === 'products/sample_img';
        });
    }

    public function test_replace_deletes_old_file_and_stores_new(): void
    {
        Storage::fake('public');
        $oldFile = UploadedFile::fake()->image('old.jpg');
        $oldPath = FileUploadService::upload($oldFile, 'products');

        $newFile = UploadedFile::fake()->image('new.jpg');
        $newPath = FileUploadService::replace($oldPath, $newFile, 'products');

        $this->assertNotEquals($oldPath, $newPath);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);
    }
}

