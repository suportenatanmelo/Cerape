<?php

use App\Models\Acolhido;
use App\Models\Agenda;
use App\Models\BlogPost;
use App\Models\CmsContent;
use App\Models\ContactLead;
use App\Models\DemandaAcolhido;
use App\Models\DiariaTrabalho;
use App\Models\EmpresaParceira;
use App\Models\FrontendSetting;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\GeradorAtividade;
use App\Models\HeroSlide;
use App\Models\NewsletterSubscriber;
use App\Models\ProntuarioEvolucao;
use App\Models\Reuniao;
use App\Models\Saude;
use App\Models\SubstanciaPsicoativas;
use App\Models\TeamMember;
use App\Models\ThemePalette;
use App\Models\User;

return [
    'queue' => env('ACTIVITY_LOG_QUEUE', true),

    'modules' => [
        User::class => 'Usuários',
        Acolhido::class => 'Acolhidos',
        Agenda::class => 'Agenda',
        BlogPost::class => 'Blog',
        CmsContent::class => 'CMS',
        ContactLead::class => 'Frontend',
        DemandaAcolhido::class => 'Demandas',
        DiariaTrabalho::class => 'Financeiro',
        EmpresaParceira::class => 'Financeiro',
        FrontendSetting::class => 'Frontend',
        GalleryCategory::class => 'Galeria',
        GalleryItem::class => 'Galeria',
        GeradorAtividade::class => 'Gerador de Atividades',
        HeroSlide::class => 'Hero Slides',
        NewsletterSubscriber::class => 'Newsletter',
        ProntuarioEvolucao::class => 'Prontuários',
        Reuniao::class => 'Reuniões',
        Saude::class => 'Saúde',
        SubstanciaPsicoativas::class => 'Saúde',
        TeamMember::class => 'Frontend',
        ThemePalette::class => 'Frontend',
    ],
];
