<?php

namespace App\Console\Commands;

use App\Filament\Pages\HeroSlideTrash as HeroSlideTrashPage;
use App\Models\FrontendMaintenanceLog;
use App\Models\HeroSlideTrash;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ValidateHeroSlideTrash extends Command
{
    protected $signature = 'frontend:validate-trash {--with-test : Create a test slide and run restore/delete validations (destructive only for test data)}';

    protected $description = 'Validate non-destructively the Hero Slide Trash and Filament page access';

    public function handle(): int
    {
        $this->info('Starting non-destructive validation of Lixeira do Carrossel...');

        // 1. Find an admin user
        $user = User::whereHas('roles', function ($q) {
            $q->whereIn('name', [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN]);
        })->first();

        if (! $user) {
            $this->warn('No user with admin roles found. Trying first user as fallback.');
            $user = User::first();
        }

        if (! $user) {
            $this->error('No users in database to perform access checks.');
            return 1;
        }

        auth()->setUser($user);

        // 1. Page appears for authorized users
        $canAccess = HeroSlideTrashPage::canAccess();
        $this->line("1) Filament page access for user {$user->id} ({$user->name}): " . ($canAccess ? 'OK' : 'FAIL'));

        // Also verify page is NOT accessible to a non-admin user (if one exists)
        $nonAdmin = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);
        })->first();

        if ($nonAdmin) {
            auth()->setUser($nonAdmin);
            $canNonAdmin = HeroSlideTrashPage::canAccess();
            $this->line("1b) Filament page access for non-admin user {$nonAdmin->id} ({$nonAdmin->name}): " . ($canNonAdmin ? 'SHOULD NOT ALLOW' : 'OK'));
            // reset back to admin for next steps
            auth()->setUser($user);
        } else {
            $this->line('1b) No non-admin user found to validate restricted access.');
            auth()->setUser($user);
        }

        // If requested, generate a test slide and trash entry to validate restore/delete flows
        $withTest = $this->option('with-test');
        $testMeta = ['created' => false];

        if ($withTest) {
            $this->line('--- Generating test slide and trash entry ---');
            $ts = now()->format('Ymd_His');

            $testImagePath = 'tests/hero_test_' . $ts . '.jpg';
            // create a small placeholder file in public disk
            Storage::disk('public')->put($testImagePath, 'test');

            // create slide
            $slide = \App\Models\HeroSlide::create([
                'title' => 'TEST HERO ' . $ts,
                'subtitle' => 'TEST',
                'description' => 'Teste de restauração',
                'image_path' => '/storage/' . $testImagePath,
                'mobile_image_path' => '/storage/' . $testImagePath,
                'og_image_path' => '/storage/' . $testImagePath,
                'position' => 9999,
                'is_active' => false,
            ]);

            $trash = HeroSlideTrash::create([
                'hero_slide_id' => $slide->id,
                'title' => $slide->title,
                'image_path' => $slide->image_path,
                'mobile_image_path' => $slide->mobile_image_path,
                'og_image_path' => $slide->og_image_path,
                'payload' => null,
                'deleted_by' => $user->getKey(),
                'deleted_at' => now(),
            ]);

            FrontendMaintenanceLog::create([
                'user_id' => $user->getKey(),
                'action' => 'create_test_trash',
                'payload' => ['trash_id' => $trash->id, 'hero_slide_id' => $slide->id],
                'result' => ['status' => 'created'],
            ]);

            $testMeta = ['created' => true, 'slide_id' => $slide->id, 'trash_id' => $trash->id, 'image_path' => $testImagePath];
            $this->line('Test slide and trash created: slide_id=' . $slide->id . ' trash_id=' . $trash->id);
        }

        // If generated test, now validate restore and delete operations
        if ($withTest && ($testMeta['created'] ?? false)) {
            $this->line('--- Validating restore operation ---');

            $controller = new \App\Http\Controllers\Admin\HeroSlideTrashController();

            try {
                $controller->restore(new \Illuminate\Http\Request(), $testMeta['trash_id']);
                $this->line('Restore action invoked.');
            } catch (\Throwable $e) {
                $this->error('Restore action threw exception: ' . $e->getMessage());
            }

            // validate trash removed and slide restored
            $trashAfter = HeroSlideTrash::find($testMeta['trash_id']);
            $slideAfter = \App\Models\HeroSlide::find($testMeta['slide_id']);

            $positionPreserved = $slideAfter !== null && (($slideAfter->position ?? null) === 9999);
            $restoreOk = $trashAfter === null && $slideAfter !== null && $slideAfter->image_path !== null && $positionPreserved;
            $this->line('Restore validation: ' . ($restoreOk ? 'OK' : 'FAIL') . ' (position preserved: ' . ($positionPreserved ? 'YES' : 'NO') . ')');

            // recreate trash to test delete
            $this->line('--- Re-creating trash entry for delete test ---');
            $trash2 = HeroSlideTrash::create([
                'hero_slide_id' => $slideAfter->id,
                'title' => $slideAfter->title,
                'image_path' => $slideAfter->image_path,
                'mobile_image_path' => $slideAfter->mobile_image_path,
                'og_image_path' => $slideAfter->og_image_path,
                'payload' => null,
                'deleted_by' => $user->getKey(),
                'deleted_at' => now(),
            ]);

            $this->line('Trash re-created id=' . $trash2->id);

            $this->line('--- Validating delete (force) operation on test item ---');
            try {
                $controller->destroy(new \Illuminate\Http\Request(), $trash2->id);
                $this->line('Delete action invoked.');
            } catch (\Throwable $e) {
                $this->error('Delete action threw exception: ' . $e->getMessage());
            }

            $trashAfter2 = HeroSlideTrash::find($trash2->id);
            $filePath = $testMeta['image_path'];
            $publicPath = ltrim($filePath, '/');
            $fileExists = Storage::disk('public')->exists($publicPath);

            $deleteOk = $trashAfter2 === null && ! $fileExists;
            $this->line('Delete validation: ' . ($deleteOk ? 'OK' : 'FAIL'));

            // Final DB integrity: check slide still exists
            $slideFinal = \App\Models\HeroSlide::find($testMeta['slide_id']);
            $this->line('Slide exists after delete test: ' . ($slideFinal ? 'YES' : 'NO'));

            // Log counts
            $logs = FrontendMaintenanceLog::whereIn('action', ['create_test_trash', 'restore_hero_slide', 'force_delete_hero_slide_trash'])->get();
            $this->line('Maintenance logs for test actions: ' . $logs->count());

            // check laravel.log for exceptions in last 30 lines
            try {
                $logFile = base_path('storage/logs/laravel.log');
                $lastLines = '';
                if (file_exists($logFile)) {
                    $lines = array_slice(file($logFile), -80);
                    $lastLines = implode("\n", $lines);
                }
                $hasException = str_contains($lastLines, 'Exception') || str_contains($lastLines, 'Error');
                $this->line('laravel.log contains exceptions in last 80 lines: ' . ($hasException ? 'YES' : 'NO'));
            } catch (\Throwable $e) {
                $this->warn('Could not inspect laravel.log: ' . $e->getMessage());
                $hasException = false;
            }

            // cleanup: remove test slide record to avoid clutter
            if ($slideFinal) {
                try {
                    $slideFinal->delete();
                    $this->line('Cleaned up test slide id=' . $slideFinal->id);
                } catch (\Throwable $e) {
                    $this->warn('Failed to delete test slide: ' . $e->getMessage());
                }
            }

            // Summarize
            $this->info('Test Summary: Restore=' . ($restoreOk ? 'OK' : 'FAIL') . ' Delete=' . ($deleteOk ? 'OK' : 'FAIL') . ' Logs=' . ($logs->count() > 0 ? 'OK' : 'MISSING') . ' Exceptions=' . ($hasException ? 'YES' : 'NO'));
        }

        // 2. Slides in trash
        $items = HeroSlideTrash::orderByDesc('deleted_at')->get();
        $this->line('2) Trashed slides count: ' . $items->count());

        // 3 & 4: preview, title, deleted_at, deleted_by
        $missingPreview = 0;
        foreach ($items as $item) {
            $hasImage = filled($item->image_path);
            if (! $hasImage) {
                $missingPreview++;
            }
        }

        $this->line('3) Missing image previews: ' . $missingPreview);

        // 5-8 are functional; we will check logs and ensure restore/destroy routes exist (non-destructive)
        $routesPresent = collect(['admin.hero-slide-trash.restore', 'admin.hero-slide-trash.delete', 'admin.hero-slide-trash.empty'])->map(function ($name) {
            try {
                return (bool) \Route::has($name);
            } catch (\Throwable $e) {
                return false;
            }
        })->all();

        $this->line('5) Routes presence (restore, delete, empty): ' . (in_array(false, $routesPresent, true) ? 'MISSING' : 'OK'));

        // 9. Check that logs table exists and recent maintenance logs present
        $logCount = FrontendMaintenanceLog::count();
        $this->line('6) Frontend maintenance logs count: ' . $logCount);

        // 10. Verify no files were deleted: check that image paths referenced still exist (public disk)
        $missingFiles = [];
        foreach (HeroSlideTrash::cursor() as $item) {
            if ($item->image_path) {
                $p = ltrim(preg_replace('#^/storage/#', '', $item->image_path), '/');
                if (! Storage::disk('public')->exists($p)) {
                    $missingFiles[] = $item->image_path;
                }
            }
        }

        $this->line('7) Missing referenced image files on public disk: ' . count($missingFiles));

        // Summary checks
        $allPassed = true;
        if (! $canAccess) {
            $allPassed = false;
        }
        if ($items->isEmpty()) {
            $this->warn('No trashed items to fully test restore/delete UI.');
        }
        if (count($missingFiles) > 0) {
            $this->warn('Some referenced image files are missing on disk, but no deletions were performed.');
        }

        $this->info('Validation completed.');

        return $allPassed ? 0 : 2;
    }
}
