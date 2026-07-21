<?php

namespace Tests\Unit\Support;

use App\Support\ImageStorageNaming;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageStorageNamingTest extends TestCase
{
    public function test_it_builds_media_preview_urls_for_public_storage_files(): void
    {
        Storage::fake('public');

        $path = 'documentos/profile-avatar/avatar-test.jpg';
        Storage::disk('public')->put($path, 'fake-image-content');

        $this->assertSame(
            route('media.serve', ['path' => $path]),
            ImageStorageNaming::previewUrl($path),
        );
    }
}
