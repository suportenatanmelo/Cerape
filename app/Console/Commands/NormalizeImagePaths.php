<?php

namespace App\Console\Commands;

use App\Models\Acolhido;
use App\Models\BlogPost;
use App\Models\CarouselSlide;
use App\Models\ContactPage;
use App\Models\Home;
use App\Models\User;
use App\Support\ImageStorageNaming;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class NormalizeImagePaths extends Command
{
    protected $signature = 'cerape:normalize-image-paths {--dry-run : Show what would change without moving files}';

    protected $description = 'Normalize image storage paths into imagens/<categoria>/YYYY/MM/DD and rename them with the record id.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $total = 0;

        foreach ($this->models() as $rules) {
            /** @var class-string<Model> $modelClass */
            $modelClass = $rules['model'];
            $modelClass::query()
                ->whereNotNull($rules['attribute'])
                ->chunkById(100, function ($records) use ($rules, $dryRun, &$total, $modelClass): void {
                    foreach ($records as $record) {
                        $current = $record->getAttribute($rules['attribute']);

                        if (! is_string($current) || trim($current) === '') {
                            continue;
                        }

                        if ($dryRun) {
                            $this->line(sprintf(
                                '[dry-run] %s #%s -> %s',
                                class_basename($modelClass),
                                $record->getKey(),
                                ImageStorageNaming::canonicalPath($rules['category'], (string) $record->getKey(), $record->{$rules['label']} ?? null, pathinfo($current, PATHINFO_EXTENSION) ?: 'jpg'),
                            ));
                            $total++;
                            continue;
                        }

                        ImageStorageNaming::syncStoredImage(
                            $record,
                            $rules['attribute'],
                            $rules['category'],
                            $record->{$rules['label']} ?? null,
                        );

                        $total++;
                    }
                });
        }

        $this->info(($dryRun ? 'Dry-run concluido: ' : 'Normalizacao concluida: ') . $total . ' imagem(ns) processada(s).');

        return self::SUCCESS;
    }

    /**
     * @return array<int, array{model:class-string<Model>, attribute:string, category:string, label:string}>
     */
    private function models(): array
    {
        return [
            [
                'model' => User::class,
                'attribute' => 'avatar',
                'category' => 'backend/users/avatars',
                'label' => 'name',
            ],
            [
                'model' => Acolhido::class,
                'attribute' => 'avatar',
                'category' => 'backend/acolhidos/avatars',
                'label' => 'nome_completo_paciente',
            ],
            [
                'model' => BlogPost::class,
                'attribute' => 'cover_image',
                'category' => 'frontend/blog/posts',
                'label' => 'title',
            ],
            [
                'model' => CarouselSlide::class,
                'attribute' => 'image',
                'category' => 'frontend/carousel',
                'label' => 'title',
            ],
            [
                'model' => ContactPage::class,
                'attribute' => 'hero_image',
                'category' => 'frontend/contact',
                'label' => 'title',
            ],
            [
                'model' => Home::class,
                'attribute' => 'hero_image',
                'category' => 'frontend/homes/hero',
                'label' => 'title',
            ],
            [
                'model' => Home::class,
                'attribute' => 'about_image',
                'category' => 'frontend/homes/about',
                'label' => 'about_title',
            ],
            [
                'model' => Home::class,
                'attribute' => 'projects_image',
                'category' => 'frontend/homes/projects',
                'label' => 'projects_title',
            ],
            [
                'model' => Home::class,
                'attribute' => 'signup_image',
                'category' => 'frontend/homes/signup',
                'label' => 'signup_title',
            ],
        ];
    }
}
