<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\HeroSlide;
use App\Models\PillarCard;
use App\Models\TeamMember;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FrontendContentSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Um novo amanhecer começa com um passo de coragem.',
                'subtitle' => 'Casa de Recuperação · Acolhimento 24h',
                'description' => 'Ambiente seguro, equipe multiprofissional e um plano de tratamento pensado para cada etapa da recuperação.',
                'image_path' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1600&auto=format&fit=crop',
                'cta_label' => 'Agendar uma conversa',
                'cta_url' => '#contato',
                'position' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Recuperação com rotina, cuidado e presença.',
                'subtitle' => 'Tratamento humanizado',
                'description' => 'Acompanhamento contínuo, apoio familiar e atividades terapêuticas em cada fase do processo.',
                'image_path' => 'https://images.unsplash.com/photo-1521477716071-8623476a3a4e?q=80&w=1600&auto=format&fit=crop',
                'cta_label' => 'Conhecer a jornada',
                'cta_url' => '#jornada',
                'position' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'A casa certa para recomeçar com dignidade.',
                'subtitle' => 'Acolhimento seguro',
                'description' => 'Estrutura preparada para acolher pessoas e famílias com respeito, transparência e organização.',
                'image_path' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=1600&auto=format&fit=crop',
                'cta_label' => 'Falar com a equipe',
                'cta_url' => '#contato',
                'position' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::query()->updateOrCreate(
                ['title' => $slide['title']],
                $slide
            );
        }

        $pillars = [
            ['Acolhimento', 'Primeiro contato com escuta, triagem e adaptação à rotina da casa.', 1],
            ['Estabilização', 'Cuidado clínico e emocional para fortalecer corpo e mente.', 2],
            ['Fortalecimento', 'Terapias, hábitos saudáveis e construção de autonomia.', 3],
            ['Reinserção', 'Planejamento de alta, vínculos familiares e retorno à vida social.', 4],
        ];

        foreach ($pillars as [$title, $summary, $position]) {
            PillarCard::query()->updateOrCreate(
                ['title' => $title],
                [
                    'summary' => $summary,
                    'position' => $position,
                    'active' => true,
                ]
            );
        }

        $team = [
            ['Psiquiatria', 'Dr. Carlos Lima', 'Avaliação clínica e acompanhamento medicamentoso.', 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?q=80&w=500&auto=format&fit=crop', 1],
            ['Psicologia', 'Marina Souza', 'Terapias individuais e em grupo durante o tratamento.', 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?q=80&w=500&auto=format&fit=crop', 2],
            ['Enfermagem', 'Patrícia Alves', 'Cuidados de saúde e monitoramento contínuo 24h.', 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?q=80&w=500&auto=format&fit=crop', 3],
            ['Terapia Ocupacional', 'Rafael Costa', 'Atividades terapêuticas e construção de novos hábitos.', 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?q=80&w=500&auto=format&fit=crop', 4],
            ['Serviço Social', 'Juliana Pires', 'Apoio à família e planejamento da reinserção social.', 'https://images.unsplash.com/photo-1582750433449-648ed127bb54?q=80&w=500&auto=format&fit=crop', 5],
            ['Nutrição', 'André Ferreira', 'Acompanhamento alimentar e reeducação nutricional.', 'https://images.unsplash.com/photo-1607990281513-2c110a25bd8c?q=80&w=500&auto=format&fit=crop', 6],
            ['Educação Física', 'Bruno Tavares', 'Atividade física orientada para corpo e mente.', 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?q=80&w=500&auto=format&fit=crop', 7],
            ['Coordenação Clínica', 'Renata Borges', 'Supervisão geral do plano terapêutico de cada residente.', 'https://images.unsplash.com/photo-1551601651-2a8555f1a136?q=80&w=500&auto=format&fit=crop', 8],
        ];

        foreach ($team as [$role, $name, $description, $photoPath, $position]) {
            TeamMember::query()->updateOrCreate(
                ['name' => $name],
                [
                    'role' => $role,
                    'description' => $description,
                    'photo_path' => $photoPath,
                    'position' => $position,
                    'active' => true,
                ]
            );
        }

        $categories = [
            ['Áreas Comuns', 'areas-comuns', 1, true, true],
            ['Quartos', 'quartos', 2, true, true],
            ['Jardim', 'jardim', 3, true, true],
            ['Terapêutico', 'terapeutico', 4, true, true],
        ];

        $galleryItems = [
            ['Áreas Comuns', 'Sala de estar', 'Ambiente de convivência para descanso e conversas.', 'https://images.unsplash.com/photo-1567016432779-094069958ea5?q=80&w=900&auto=format&fit=crop', 1],
            ['Áreas Comuns', 'Refeitório', 'Espaço de alimentação e rotina compartilhada.', 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=900&auto=format&fit=crop', 2],
            ['Áreas Comuns', 'Espaço de leitura', 'Momento de calma, foco e rotina leve.', 'https://images.unsplash.com/photo-1582037928769-181cf1ea3201?q=80&w=900&auto=format&fit=crop', 3],
            ['Quartos', 'Quarto compartilhado', 'Conforto e organização para o descanso diário.', 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=900&auto=format&fit=crop', 1],
            ['Quartos', 'Quarto individual', 'Privacidade com simplicidade e acolhimento.', 'https://images.unsplash.com/photo-1560448075-bb485b067938?q=80&w=900&auto=format&fit=crop', 2],
            ['Quartos', 'Acomodações', 'Espaço pensado para segurança e higiene.', 'https://images.unsplash.com/photo-1582582429416-1f5d2f3d4dbe?q=80&w=900&auto=format&fit=crop', 3],
            ['Jardim', 'Jardim de convivência', 'Área verde para respirar e desacelerar.', 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?q=80&w=900&auto=format&fit=crop', 1],
            ['Jardim', 'Área externa', 'Atividade ao ar livre e socialização saudável.', 'https://images.unsplash.com/photo-1521477716071-8623476a3a4e?q=80&w=900&auto=format&fit=crop', 2],
            ['Jardim', 'Horta comunitária', 'Cuidado com a terra e aprendizado de rotina.', 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?q=80&w=900&auto=format&fit=crop', 3],
            ['Terapêutico', 'Espaço terapêutico', 'Sessões individuais e acolhimento clínico.', 'https://images.unsplash.com/photo-1571902943202-507ec2618e8f?q=80&w=900&auto=format&fit=crop', 1],
            ['Terapêutico', 'Sala de grupo', 'Dinâmicas e construção de vínculos.', 'https://images.unsplash.com/photo-1551076805-e1869033e561?q=80&w=900&auto=format&fit=crop', 2],
            ['Terapêutico', 'Atividade orientada', 'Prática guiada com foco em reabilitação.', 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?q=80&w=900&auto=format&fit=crop', 3],
        ];

        foreach ($categories as [$name, $slug, $position, $showOnHome, $showInMenu]) {
            $category = GalleryCategory::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'position' => $position,
                    'show_on_home' => $showOnHome,
                    'show_in_menu' => $showInMenu,
                    'active' => true,
                ]
            );

            foreach ($galleryItems as [$itemCategory, $title, $caption, $imagePath, $itemPosition]) {
                if ($itemCategory !== $name) {
                    continue;
                }

                GalleryItem::query()->updateOrCreate(
                    [
                        'gallery_category_id' => $category->id,
                        'title' => $title,
                    ],
                    [
                        'caption' => $caption,
                        'image_path' => $imagePath,
                        'position' => $itemPosition,
                        'active' => true,
                    ]
                );
            }
        }

        $blogPosts = [
            [
                'title' => 'O que esperar nas primeiras semanas de internação',
                'slug' => 'o-que-esperar-nas-primeiras-semanas-de-internacao',
                'excerpt' => 'A adaptação à rotina, a avaliação inicial e o papel da família nos primeiros dias.',
                'content' => 'As primeiras semanas costumam ser dedicadas à adaptação, escuta e construção de vínculo. A equipe avalia necessidades clínicas, define prioridades e organiza a rotina para oferecer segurança e previsibilidade.',
                'author_name' => 'Equipe CERAPE',
                'published_at' => Carbon::parse('2026-05-22'),
                'image_path' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?q=80&w=1200&auto=format&fit=crop',
                'tags' => ['internação', 'adaptação', 'família'],
                'show_on_home' => true,
                'show_in_blog' => true,
                'position' => 1,
                'active' => true,
            ],
            [
                'title' => 'Voltar para casa: planejando a fase de alta',
                'slug' => 'voltar-para-casa-planejando-a-fase-de-alta',
                'excerpt' => 'A alta não termina no portão da casa. Ela precisa de planejamento, rede e rotina.',
                'content' => 'O planejamento de alta ajuda a reduzir riscos e a sustentar o progresso conquistado. Isso inclui alinhar expectativas, reforçar hábitos saudáveis e mapear apoio familiar e comunitário.',
                'author_name' => 'Equipe CERAPE',
                'published_at' => Carbon::parse('2026-05-09'),
                'image_path' => 'https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?q=80&w=1200&auto=format&fit=crop',
                'tags' => ['alta', 'reinserção', 'rotina'],
                'show_on_home' => true,
                'show_in_blog' => true,
                'position' => 2,
                'active' => true,
            ],
            [
                'title' => 'Como apoiar um familiar sem se esgotar',
                'slug' => 'como-apoiar-um-familiar-sem-se-esgotar',
                'excerpt' => 'Apoiar também exige limites, descanso e apoio para quem cuida.',
                'content' => 'Famílias que acompanham um tratamento costumam assumir muitas responsabilidades. Este texto aborda limites saudáveis, autocuidado e a importância de pedir ajuda no momento certo.',
                'author_name' => 'Equipe CERAPE',
                'published_at' => Carbon::parse('2026-06-03'),
                'image_path' => 'https://images.unsplash.com/photo-1499209974431-9dddcece7f88?q=80&w=1200&auto=format&fit=crop',
                'tags' => ['família', 'apoio', 'autocuidado'],
                'show_on_home' => true,
                'show_in_blog' => true,
                'position' => 3,
                'active' => true,
            ],
            [
                'title' => 'A importância da rotina na recuperação',
                'slug' => 'a-importancia-da-rotina-na-recuperacao',
                'excerpt' => 'Previsibilidade, sono, alimentação e horários ajudam a estabilizar o dia a dia.',
                'content' => 'Rotina não é rigidez: é estrutura. Ao longo da recuperação, a previsibilidade ajuda a reduzir ansiedade, fortalecer hábitos e facilitar o acompanhamento terapêutico.',
                'author_name' => 'Equipe CERAPE',
                'published_at' => Carbon::parse('2026-06-01'),
                'image_path' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=1200&auto=format&fit=crop',
                'tags' => ['rotina', 'hábitos', 'saúde'],
                'show_on_home' => true,
                'show_in_blog' => true,
                'position' => 4,
                'active' => true,
            ],
            [
                'title' => 'Relapse prevention: como reconhecer sinais de alerta',
                'slug' => 'relapse-prevention-como-reconhecer-sinais-de-alerta',
                'excerpt' => 'Identificar gatilhos cedo é uma das formas mais eficazes de manter o cuidado em andamento.',
                'content' => 'Sinais de alerta podem aparecer como isolamento, irritabilidade, quebra de rotina ou contato com ambientes de risco. Detectá-los cedo permite atuar com apoio profissional e familiar.',
                'author_name' => 'Equipe CERAPE',
                'published_at' => Carbon::parse('2026-06-05'),
                'image_path' => 'https://images.unsplash.com/photo-1526256262350-7da7584cf5eb?q=80&w=1200&auto=format&fit=crop',
                'tags' => ['prevenção', 'gatilhos', 'cuidado'],
                'show_on_home' => true,
                'show_in_blog' => true,
                'position' => 5,
                'active' => true,
            ],
            [
                'title' => 'Terapia em grupo e por que ela funciona',
                'slug' => 'terapia-em-grupo-e-por-que-ela-funciona',
                'excerpt' => 'O grupo ajuda a construir confiança, pertencimento e troca de estratégias.',
                'content' => 'A terapia em grupo amplia repertórios e reduz a sensação de isolamento. Quando há escuta qualificada e regras claras, o ambiente favorece aprendizado e suporte entre pares.',
                'author_name' => 'Equipe CERAPE',
                'published_at' => Carbon::parse('2026-06-07'),
                'image_path' => 'https://images.unsplash.com/photo-1571902943202-507ec2618e8f?q=80&w=1200&auto=format&fit=crop',
                'tags' => ['terapia', 'grupo', 'vínculo'],
                'show_on_home' => true,
                'show_in_blog' => true,
                'position' => 6,
                'active' => true,
            ],
            [
                'title' => 'Como o sono impacta o processo de recuperação',
                'slug' => 'como-o-sono-impacta-o-processo-de-recuperacao',
                'excerpt' => 'Dormir bem influencia humor, energia e capacidade de decisão.',
                'content' => 'Uma boa noite de sono não resolve tudo, mas sustenta a recuperação. O texto aborda higiene do sono, rotina noturna e sinais de quando buscar avaliação clínica.',
                'author_name' => 'Equipe CERAPE',
                'published_at' => Carbon::parse('2026-06-10'),
                'image_path' => 'https://images.unsplash.com/photo-1474631245212-32dc3c8310c6?q=80&w=1200&auto=format&fit=crop',
                'tags' => ['sono', 'saúde mental', 'rotina'],
                'show_on_home' => true,
                'show_in_blog' => true,
                'position' => 7,
                'active' => true,
            ],
            [
                'title' => 'Alimentação e reabilitação: o que muda na prática',
                'slug' => 'alimentacao-e-reabilitacao-o-que-muda-na-pratica',
                'excerpt' => 'Comer bem é parte do cuidado diário e da recuperação física.',
                'content' => 'A alimentação oferece energia, ajuda na estabilização e reforça a sensação de rotina. É importante respeitar necessidades clínicas, preferências e acompanhamento nutricional.',
                'author_name' => 'Equipe CERAPE',
                'published_at' => Carbon::parse('2026-06-12'),
                'image_path' => 'https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1200&auto=format&fit=crop',
                'tags' => ['nutrição', 'saúde', 'cuidados'],
                'show_on_home' => true,
                'show_in_blog' => true,
                'position' => 8,
                'active' => true,
            ],
            [
                'title' => 'Recomeços depois da internação: reconstruindo vínculos',
                'slug' => 'recomecos-depois-da-internacao-reconstruindo-vinculos',
                'excerpt' => 'A volta ao convívio pede conversa, consistência e paciência de todos os lados.',
                'content' => 'Reconstruir vínculos leva tempo. Este artigo orienta sobre comunicação, limites e pequenas metas de convivência para que a reinserção seja mais segura.',
                'author_name' => 'Equipe CERAPE',
                'published_at' => Carbon::parse('2026-06-15'),
                'image_path' => 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=1200&auto=format&fit=crop',
                'tags' => ['reinserção', 'família', 'vínculos'],
                'show_on_home' => true,
                'show_in_blog' => true,
                'position' => 9,
                'active' => true,
            ],
            [
                'title' => 'Apoio contínuo depois da alta: por que isso importa',
                'slug' => 'apoio-continuo-depois-da-alta-por-que-isso-importa',
                'excerpt' => 'Acompanhar o pós-alta ajuda a preservar os ganhos do tratamento.',
                'content' => 'Após a alta, o suporte continua sendo importante. Retomar rotina, manter contato com a equipe e preservar hábitos saudáveis são passos que sustentam o progresso.',
                'author_name' => 'Equipe CERAPE',
                'published_at' => Carbon::parse('2026-06-18'),
                'image_path' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1200&auto=format&fit=crop',
                'tags' => ['pós-alta', 'apoio', 'continuidade'],
                'show_on_home' => true,
                'show_in_blog' => true,
                'position' => 10,
                'active' => true,
            ],
        ];

        foreach ($blogPosts as $post) {
            BlogPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                $post
            );
        }
    }
}
