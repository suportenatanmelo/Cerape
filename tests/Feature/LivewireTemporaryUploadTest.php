<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Tests\TestCase;

class LivewireTemporaryUploadTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('tmp-for-tests');
    }

    public function test_public_disk_writes_and_removes_a_file(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('tests/cerape-upload-test.txt', 'CERAPE');

        Storage::disk('public')->assertExists('tests/cerape-upload-test.txt');

        Storage::disk('public')->delete('tests/cerape-upload-test.txt');

        Storage::disk('public')->assertMissing('tests/cerape-upload-test.txt');
    }

    public function test_livewire_stores_a_valid_temporary_image(): void
    {
        $url = URL::temporarySignedRoute('livewire.upload-file', now()->addMinute());

        $response = $this->post($url, [
            'files' => [UploadedFile::fake()->image('cerape.png', 10, 10)],
        ]);

        $response->assertOk()->assertJsonCount(1, 'paths');

        $signedPath = $response->json('paths.0');
        $temporaryPath = TemporaryUploadedFile::extractPathFromSignedPath($signedPath);

        Storage::disk('tmp-for-tests')
            ->assertExists(FileUploadConfiguration::path($temporaryPath));
    }

    public function test_livewire_rejects_a_temporary_file_above_the_global_limit(): void
    {
        $validator = Validator::make([
            'files' => [UploadedFile::fake()->create('too-large.png', 2049, 'image/png')],
        ], [
            'files.*' => FileUploadConfiguration::rules(),
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('files.0', $validator->errors()->toArray());
    }
}
