<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendSetting extends Model
{
    protected $attributes = [
        'site_enabled' => true,
        'home_enabled' => true,
        'brand_name' => 'Cerape',
        'hero_title' => 'Como funciona',
        'hero_subtitle' => 'Conteúdo editável pelo painel /frontend.',
        'about_title' => 'Sobre a CERAPE',
        'about_paragraph_one' => 'A CERAPE é uma casa de recuperação dedicada a oferecer acolhimento, tratamento e um novo começo para quem enfrenta a dependência química.',
        'about_paragraph_two' => 'Acreditamos que a recuperação acontece em comunidade: por isso trabalhamos junto às famílias, com transparência e respeito ao tempo de cada pessoa, do primeiro dia até a reinserção social.',
        'menu_label_about' => 'Quem somos',
        'menu_label_home' => 'Iniciar',
        'menu_label_pillars' => 'Pilares',
        'menu_label_team' => 'Equipe',
        'menu_label_gallery' => 'Galeria',
        'menu_label_blog' => 'Blog',
        'menu_label_contact' => 'Contato',
        'header_primary_color' => '#0f172a',
        'header_secondary_color' => '#155e75',
        'footer_primary_color' => '#111827',
        'footer_secondary_color' => '#0f766e',
        'font_color' => '#e5e7eb',
        'accent_color' => '#38bdf8',
        'clinic_name' => 'Clínica CERAPE',
        'clinic_description' => 'Veja abaixo onde fica a clínica de recuperação e como chegar.',
    ];

    protected $fillable = [
        'site_enabled',
        'home_enabled',
        'brand_name',
        'hero_title',
        'hero_subtitle',
        'about_title',
        'about_paragraph_one',
        'about_paragraph_two',
        'about_image_path',
        'menu_label_about',
        'menu_label_home',
        'menu_label_pillars',
        'menu_label_team',
        'menu_label_gallery',
        'menu_label_blog',
        'menu_label_contact',
        'header_primary_color',
        'header_secondary_color',
        'footer_primary_color',
        'footer_secondary_color',
        'font_color',
        'accent_color',
        'clinic_name',
        'clinic_address',
        'clinic_city',
        'clinic_state',
        'clinic_zip_code',
        'clinic_google_maps_embed',
        'clinic_maps_link',
        'clinic_description',
        'whatsapp_number',
        'whatsapp_message',
        'site_email',
    ];

    protected $casts = [
        'site_enabled' => 'boolean',
        'home_enabled' => 'boolean',
    ];
}
