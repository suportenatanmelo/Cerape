<?php

return [
    'temporary_file_upload' => [
        // Keep temporary files outside the public disk. Final public files are
        // stored by Filament on the `public` disk after validation.
        'disk' => env('LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK', 'local'),
        'directory' => env('LIVEWIRE_TEMPORARY_FILE_UPLOAD_DIRECTORY', 'livewire-tmp'),
        'rules' => ['required', 'file', 'max:2048'],
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'jpg', 'jpeg', 'webp',
        ],
        'max_upload_time' => 5,
        'cleanup' => true,
    ],
];
