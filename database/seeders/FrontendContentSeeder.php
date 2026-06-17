<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\FrontendThemeProfile;
use App\Models\FrontendTestimonial;
use App\Models\Home;
use App\Support\BlogPostSchema;
use App\Support\FrontendThemePresets;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class FrontendContentSeeder extends Seeder
{
    public function run(): void
    {
        $home = Home::query()->latest('id')->first();

        if ($home) {
            $home->update($this->homePayload());
        } else {
            Home::create($this->homePayload());
        }

        $this->seedThemeProfiles();
        $this->seedTestimonials();

        BlogPostSchema::ensureTableExists();

        foreach ($this->blogPostsPayload() as $payload) {
            BlogPost::query()->updateOrCreate(
                ['slug' => $payload['slug']],
                Arr::except($payload, ['slug']),
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function homePayload(): array
    {
        return [
            'title' => 'CERAPE',
            'subtitle' => 'Acolhimento, acompanhamento e comunicacao institucional em um site elegante, claro e facil de atualizar pelo painel.',
            'hero_image' => 'grayscale/assets/img/bg-masthead.jpg',
            'hero_image_alt' => 'Equipe CERAPE em destaque',
            'cta_label' => 'Conhecer o projeto',
            'cta_url' => '#about',
            'about_title' => 'Sobre o CERAPE',
            'about_subtitle' => 'O site publico foi desenhado para apresentar a instituicao, fortalecer a relacao com a familia e centralizar noticias, conteudos e contato em um unico lugar.',
            'about_image' => 'grayscale/assets/img/demo-image-01.jpg',
            'about_image_alt' => 'Institucional CERAPE',
            'projects_title' => 'Servicos e iniciativas',
            'projects_subtitle' => 'Use esta area para destacar projetos, programas e informacoes que merecem atencao especial na pagina inicial.',
            'projects_image' => 'grayscale/assets/img/demo-image-02.jpg',
            'projects_image_alt' => 'Projetos do CERAPE',
            'signup_title' => 'Fale com a equipe',
            'signup_subtitle' => 'Envie uma mensagem pelo formulario e retorne com facilidade. O contato tambem pode ser atualizado pelo painel, se necessario.',
            'signup_image' => null,
            'signup_image_alt' => null,
            'testimonials' => [
                [
                    'name' => 'Família acolhida',
                    'role' => 'Cuidado e acompanhamento',
                    'summary' => 'O atendimento ficou mais humano, claro e organizado depois que o site passou a comunicar melhor cada etapa.',
                    'image' => 'grayscale/assets/img/demo-image-01.jpg',
                    'image_alt' => 'Família recebendo orientações',
                ],
                [
                    'name' => 'Equipe técnica',
                    'role' => 'Fluxo de trabalho',
                    'summary' => 'Os conteúdos estão muito mais fáceis de atualizar, e isso deixou a rotina da equipe bem mais leve.',
                    'image' => 'grayscale/assets/img/demo-image-02.jpg',
                    'image_alt' => 'Equipe reunida em trabalho',
                ],
                [
                    'name' => 'Acompanhamento social',
                    'role' => 'Comunicação institucional',
                    'summary' => 'A identidade visual transmite confiança e acolhimento sem perder a formalidade necessária.',
                    'image' => 'grayscale/assets/img/bg-signup.jpg',
                    'image_alt' => 'Acolhimento institucional',
                ],
                [
                    'name' => 'Rede de apoio',
                    'role' => 'Experiência do visitante',
                    'summary' => 'A navegação ficou intuitiva e o layout tornou a leitura rápida, bonita e muito mais convidativa.',
                    'image' => 'grayscale/assets/img/demo-image-01.jpg',
                    'image_alt' => 'Rede de apoio e orientação',
                ],
                [
                    'name' => 'Gestão e cuidado',
                    'role' => 'Atualização contínua',
                    'summary' => 'Ter tudo concentrado no painel `/frontend` ajuda bastante na manutenção e no crescimento do projeto.',
                    'image' => 'grayscale/assets/img/demo-image-02.jpg',
                    'image_alt' => 'Gestão de conteúdo',
                ],
            ],
            'enable_carousel' => true,
            'carousel_items' => [
                [
                    'title' => 'Acompanhamento com proximidade',
                    'description' => 'Uma comunicacao acolhedora, organizada e pronta para informar com transparencia.',
                    'image' => 'grayscale/assets/img/demo-image-01.jpg',
                    'alt' => 'Equipe em atendimento',
                    'link_label' => 'Saiba mais',
                    'link_url' => '#about',
                ],
                [
                    'title' => 'Conteudo institucional vivo',
                    'description' => 'Atualize imagens, botoes e chamadas sem precisar mexer no codigo do site.',
                    'image' => 'grayscale/assets/img/demo-image-02.jpg',
                    'alt' => 'Conteudo institucional',
                    'link_label' => 'Ver blog',
                    'link_url' => '/blog',
                ],
            ],
        ];
    }

    private function seedThemeProfiles(): void
    {
        foreach (FrontendThemePresets::profiles() as $key => $profile) {
            FrontendThemeProfile::query()->updateOrCreate(
                ['slug' => $key],
                [
                    'name' => $profile['name'],
                    'description' => $profile['description'],
                    'preset_key' => $key,
                    'primary_color' => $profile['primary_color'],
                    'secondary_color' => $profile['secondary_color'],
                    'accent_color' => $profile['accent_color'],
                    'background_color' => $profile['background_color'],
                    'surface_color' => $profile['surface_color'],
                    'surface_strong_color' => $profile['surface_strong_color'],
                    'ink_color' => $profile['ink_color'],
                    'text_color' => $profile['text_color'],
                    'muted_color' => $profile['muted_color'],
                    'body_font' => $profile['body_font'],
                    'display_font' => $profile['display_font'],
                    'is_active' => $key === FrontendThemePresets::defaultProfileKey(),
                ]
            );
        }
    }

    private function seedTestimonials(): void
    {
        $payloads = [
            [
                'name' => 'Família acolhida',
                'role' => 'Cuidado e acompanhamento',
                'summary' => 'O atendimento ficou mais humano, claro e organizado depois que o site passou a comunicar melhor cada etapa.',
                'image' => 'grayscale/assets/img/demo-image-01.jpg',
                'image_alt' => 'Família recebendo orientações',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Equipe técnica',
                'role' => 'Fluxo de trabalho',
                'summary' => 'Os conteúdos estão muito mais fáceis de atualizar, e isso deixou a rotina da equipe bem mais leve.',
                'image' => 'grayscale/assets/img/demo-image-02.jpg',
                'image_alt' => 'Equipe reunida em trabalho',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Acompanhamento social',
                'role' => 'Comunicação institucional',
                'summary' => 'A identidade visual transmite confiança e acolhimento sem perder a formalidade necessária.',
                'image' => 'grayscale/assets/img/bg-signup.jpg',
                'image_alt' => 'Acolhimento institucional',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Rede de apoio',
                'role' => 'Experiência do visitante',
                'summary' => 'A navegação ficou intuitiva e o layout tornou a leitura rápida, bonita e muito mais convidativa.',
                'image' => 'grayscale/assets/img/demo-image-01.jpg',
                'image_alt' => 'Rede de apoio e orientação',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Gestão e cuidado',
                'role' => 'Atualização contínua',
                'summary' => 'Ter tudo concentrado no painel `/frontend` ajuda bastante na manutenção e no crescimento do projeto.',
                'image' => 'grayscale/assets/img/demo-image-02.jpg',
                'image_alt' => 'Gestão de conteúdo',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($payloads as $payload) {
            FrontendTestimonial::query()->updateOrCreate(
                ['name' => $payload['name']],
                $payload,
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function blogPostsPayload(): array
    {
        $publishedAt = fn (int $daysAgo): Carbon => now()->subDays($daysAgo);

        return [
            [
                'slug' => 'cerape-estrutura-nova-apresentacao-institucional',
                'title' => 'CERAPE apresenta novo site institucional',
                'category' => 'Institucional',
                'excerpt' => 'Conheca a nova pagina publica, pensada para comunicar com clareza, acolher melhor as familias e facilitar a atualizacao de conteudo.',
                'content' => '<p>O novo frontend do CERAPE foi organizado para unir <strong>sobre</strong>, <strong>blog</strong>, <strong>carrossel</strong> e <strong>contato</strong> em uma experiencia simples e elegante.</p><p>O objetivo e facilitar a atualizacao pela equipe, sem abrir mao de uma apresentacao profissional para o publico.</p>',
                'cover_image' => 'grayscale/assets/img/demo-image-01.jpg',
                'cover_image_alt' => 'Site institucional CERAPE',
                'author_name' => 'CERAPE',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => $publishedAt(2),
            ],
            [
                'slug' => 'conteudo-organizado-para-familias-e-equipe',
                'title' => 'Conteudo organizado para familias e equipe',
                'category' => 'Comunicacao',
                'excerpt' => 'O blog publica avisos, novidades e informacoes uteis em cards claros, prontos para leitura no celular ou no computador.',
                'content' => '<p>O painel de administracao permite cadastrar posts com capa, resumo, status e destaque. Assim a equipe consegue manter a pagina sempre atualizada.</p><p>Esse formato favorece leitura rapida, acessibilidade e manutencao simples.</p>',
                'cover_image' => 'grayscale/assets/img/demo-image-02.jpg',
                'cover_image_alt' => 'Blog CERAPE',
                'author_name' => 'CERAPE',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => $publishedAt(4),
            ],
            [
                'slug' => 'carrossel-destaques-na-home',
                'title' => 'Carrossel de destaques na home',
                'category' => 'Home',
                'excerpt' => 'O carrossel destaca mensagens, imagens e chamadas para acao sem depender de plugins complicados.',
                'content' => '<p>Os slides podem ter imagem, titulo, descricao e botao proprio. Isso permite destacar campanhas, eventos ou avisos importantes.</p><p>O gerenciamento acontece direto no Filament, com edicao simples e segura.</p>',
                'cover_image' => 'grayscale/assets/img/bg-signup.jpg',
                'cover_image_alt' => 'Carrossel CERAPE',
                'author_name' => 'CERAPE',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => $publishedAt(6),
            ],
        ];
    }
}
