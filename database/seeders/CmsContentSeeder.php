<?php

namespace Database\Seeders;

use App\Models\CmsContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [CmsContent::TYPE_TREATMENT, 'Acolhimento terapêutico', 'Cuidado inicial com escuta, avaliação e plano individual.'],
            [CmsContent::TYPE_TREATMENT, 'Reinserção social', 'Acompanhamento para reconstrução de rotina, vínculos e autonomia.'],
            [CmsContent::TYPE_FAQ, 'Como funciona a internação?', 'A equipe orienta a família, avalia o caso e acompanha cada etapa com sigilo.'],
            [CmsContent::TYPE_TESTIMONIAL, 'Família acolhida', 'Encontramos orientação e presença em um momento muito difícil.'],
            [CmsContent::TYPE_PARTNER, 'Parceiro institucional', 'Organização parceira em ações de cuidado e reinserção.'],
            [CmsContent::TYPE_BANNER, 'Atendimento confidencial', 'Fale com a equipe CERAPE pelo canal oficial.'],
            [CmsContent::TYPE_SOCIAL_LINK, 'Instagram', 'Acompanhe conteúdos e novidades da CERAPE.'],
        ];

        foreach ($items as $index => [$type, $title, $summary]) {
            CmsContent::query()->updateOrCreate(
                ['type' => $type, 'slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'summary' => $summary,
                    'content' => $summary,
                    'position' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
