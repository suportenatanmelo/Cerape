<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Home;
use App\Support\BlogPostSchema;
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
