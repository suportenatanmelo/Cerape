<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('frontend_settings')) {
            return;
        }

        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `frontend_settings` ROW_FORMAT=DYNAMIC');
        foreach ([
            'brand_name',
            'hero_title',
            'hero_subtitle',
            'about_title',
            'menu_label_about',
            'menu_label_home',
            'menu_label_pillars',
            'menu_label_team',
            'menu_label_gallery',
            'menu_label_blog',
            'menu_label_contact',
            'clinic_name',
            'clinic_description',
            'clinic_contact_title',
            'clinic_contact_name',
            'clinic_contact_address_label',
            'clinic_contact_city_label',
            'clinic_contact_state_label',
            'clinic_contact_zip_label',
            'clinic_contact_phone_label',
            'clinic_contact_email_label',
            'contact_eyebrow',
            'contact_title',
            'contact_description',
            'contact_phone_label',
            'contact_phone_line',
            'contact_whatsapp_cta_label',
            'contact_address_label',
            'contact_address_line',
            'contact_email_label',
            'contact_email_line',
            'contact_form_name_placeholder',
            'contact_form_phone_placeholder',
            'contact_form_email_placeholder',
            'contact_form_message_placeholder',
        ] as $column) {
            if (Schema::hasColumn('frontend_settings', $column)) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `frontend_settings` MODIFY `{$column}` TEXT NULL");
            }
        }

        Schema::table('frontend_settings', function (Blueprint $table): void {
            foreach ([
                ['hero_cta_label', fn (Blueprint $table) => $table->text('hero_cta_label')->nullable()],
                ['hero_secondary_cta_label', fn (Blueprint $table) => $table->text('hero_secondary_cta_label')->nullable()],
                ['journey_eyebrow', fn (Blueprint $table) => $table->text('journey_eyebrow')->nullable()],
                ['journey_title', fn (Blueprint $table) => $table->text('journey_title')->nullable()],
                ['journey_description', fn (Blueprint $table) => $table->text('journey_description')->nullable()],
                ['journey_empty_title_one', fn (Blueprint $table) => $table->text('journey_empty_title_one')->nullable()],
                ['journey_empty_title_two', fn (Blueprint $table) => $table->text('journey_empty_title_two')->nullable()],
                ['journey_empty_title_three', fn (Blueprint $table) => $table->text('journey_empty_title_three')->nullable()],
                ['journey_empty_title_four', fn (Blueprint $table) => $table->text('journey_empty_title_four')->nullable()],
                ['journey_empty_description', fn (Blueprint $table) => $table->text('journey_empty_description')->nullable()],
                ['team_eyebrow', fn (Blueprint $table) => $table->text('team_eyebrow')->nullable()],
                ['team_title', fn (Blueprint $table) => $table->text('team_title')->nullable()],
                ['team_description', fn (Blueprint $table) => $table->text('team_description')->nullable()],
                ['team_empty_message', fn (Blueprint $table) => $table->text('team_empty_message')->nullable()],
                ['gallery_eyebrow', fn (Blueprint $table) => $table->text('gallery_eyebrow')->nullable()],
                ['gallery_title', fn (Blueprint $table) => $table->text('gallery_title')->nullable()],
                ['gallery_description', fn (Blueprint $table) => $table->text('gallery_description')->nullable()],
                ['gallery_empty_message', fn (Blueprint $table) => $table->text('gallery_empty_message')->nullable()],
                ['gallery_all_label', fn (Blueprint $table) => $table->text('gallery_all_label')->nullable()],
                ['blog_eyebrow', fn (Blueprint $table) => $table->text('blog_eyebrow')->nullable()],
                ['blog_title', fn (Blueprint $table) => $table->text('blog_title')->nullable()],
                ['blog_description', fn (Blueprint $table) => $table->text('blog_description')->nullable()],
                ['blog_empty_message', fn (Blueprint $table) => $table->text('blog_empty_message')->nullable()],
                ['contact_section_eyebrow', fn (Blueprint $table) => $table->text('contact_section_eyebrow')->nullable()],
                ['contact_section_title', fn (Blueprint $table) => $table->text('contact_section_title')->nullable()],
                ['contact_section_description', fn (Blueprint $table) => $table->text('contact_section_description')->nullable()],
                ['contact_whatsapp_title', fn (Blueprint $table) => $table->text('contact_whatsapp_title')->nullable()],
                ['contact_whatsapp_footer', fn (Blueprint $table) => $table->text('contact_whatsapp_footer')->nullable()],
                ['contact_whatsapp_cta_label', fn (Blueprint $table) => $table->text('contact_whatsapp_cta_label')->nullable()],
            ] as [$column, $adder]) {
                if (! Schema::hasColumn('frontend_settings', $column)) {
                    $adder($table);
                }
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('frontend_settings')) {
            return;
        }

        Schema::table('frontend_settings', function (Blueprint $table): void {
            $columns = [
                'hero_cta_label',
                'hero_secondary_cta_label',
                'journey_eyebrow',
                'journey_title',
                'journey_description',
                'journey_empty_title_one',
                'journey_empty_title_two',
                'journey_empty_title_three',
                'journey_empty_title_four',
                'journey_empty_description',
                'team_eyebrow',
                'team_title',
                'team_description',
                'team_empty_message',
                'gallery_eyebrow',
                'gallery_title',
                'gallery_description',
                'gallery_empty_message',
                'gallery_all_label',
                'blog_eyebrow',
                'blog_title',
                'blog_description',
                'blog_empty_message',
                'contact_section_eyebrow',
                'contact_section_title',
                'contact_section_description',
                'contact_whatsapp_title',
                'contact_whatsapp_footer',
                'contact_whatsapp_cta_label',
            ];

            $existing = array_values(array_filter($columns, fn (string $column) => Schema::hasColumn('frontend_settings', $column)));

            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });
    }
};
