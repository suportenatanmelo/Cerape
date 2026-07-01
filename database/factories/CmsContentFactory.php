<?php

namespace Database\Factories;

use App\Models\CmsContent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CmsContent>
 */
class CmsContentFactory extends Factory
{
    protected $model = CmsContent::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'type' => fake()->randomElement(array_keys(CmsContent::TYPES)),
            'title' => $title,
            'slug' => Str::slug($title),
            'subtitle' => fake()->sentence(),
            'summary' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'position' => fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
