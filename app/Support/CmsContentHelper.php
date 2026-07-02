<?php

namespace App\Support;

use App\Models\CmsContent;

class CmsContentHelper
{
    public static function helperText(?string $type = null): string
    {
        return match ($type) {
            CmsContent::TYPE_HOME_BLOCK => 'O bloco Home permite alterar esta seção do site público.',
            CmsContent::TYPE_TREATMENT => 'Cadastre aqui os tratamentos. Eles aparecerão automaticamente na página pública.',
            CmsContent::TYPE_NEWS => 'Cadastre notícias institucionais no mesmo padrão editorial do blog. Use slug e resumo para criar listagens profissionais.',
            CmsContent::TYPE_EVENT => 'Cadastre eventos com data, imagem, mapa e link de inscrição. Cada evento pode ter página própria para SEO.',
            CmsContent::TYPE_TESTIMONIAL => 'Cadastre depoimentos para reforçar a confiança no site institucional.',
            CmsContent::TYPE_PARTNER => 'Cadastre parceiros com logo, site e ordem de exibição.',
            CmsContent::TYPE_FAQ => 'Cadastre perguntas frequentes para orientar visitantes e familiares.',
            CmsContent::TYPE_BANNER => 'Cadastre banners reutilizáveis para campanhas e chamadas do site.',
            CmsContent::TYPE_POPUP => 'Cadastre popups agendáveis com período de exibição.',
            CmsContent::TYPE_MENU_ITEM => 'Monte menus com links internos ou externos, ordenados pelo painel.',
            CmsContent::TYPE_FOOTER_WIDGET => 'Configure widgets do rodapé, menus e links institucionais.',
            CmsContent::TYPE_SOCIAL_LINK => 'Cadastre redes sociais como Facebook, Instagram, YouTube, TikTok, LinkedIn, Threads e X.',
            default => 'Gerencie conteúdos institucionais reutilizáveis do site público.',
        };
    }
}
