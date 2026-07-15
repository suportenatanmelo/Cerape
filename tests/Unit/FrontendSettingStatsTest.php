<?php

namespace Tests\Unit;

use App\Models\FrontendSetting;
use PHPUnit\Framework\TestCase;

class FrontendSettingStatsTest extends TestCase
{
    public function test_stats_items_are_returned_by_default(): void
    {
        $settings = new FrontendSetting();

        $this->assertSame([
            ['value' => '12+', 'label' => 'anos de atuação'],
            ['value' => '500+', 'label' => 'vidas acolhidas'],
            ['value' => '24h', 'label' => 'equipe de plantão'],
        ], $settings->statsItems());
    }

    public function test_stats_items_can_be_hidden(): void
    {
        $settings = new FrontendSetting(['stats_enabled' => false]);

        $this->assertSame([], $settings->statsItems());
    }

    public function test_stats_items_can_use_custom_values(): void
    {
        $settings = new FrontendSetting([
            'stats_enabled' => true,
            'stats_item_one_value' => '8+',
            'stats_item_one_label' => 'anos de experiência',
            'stats_item_two_value' => '250+',
            'stats_item_two_label' => 'casos atendidos',
            'stats_item_three_value' => '7/7',
            'stats_item_three_label' => 'disponibilidade',
        ]);

        $this->assertSame([
            ['value' => '8+', 'label' => 'anos de experiência'],
            ['value' => '250+', 'label' => 'casos atendidos'],
            ['value' => '7/7', 'label' => 'disponibilidade'],
        ], $settings->statsItems());
    }

    public function test_about_layout_settings_have_sensible_defaults(): void
    {
        $settings = new FrontendSetting();

        $this->assertSame('left', $settings->aboutTextAlignment());
        $this->assertSame('right', $settings->aboutImagePosition());
        $this->assertTrue($settings->aboutShouldShowImage());
        $this->assertTrue($settings->aboutShouldShowVideo());
    }

    public function test_about_layout_settings_can_be_customized(): void
    {
        $settings = new FrontendSetting([
            'about_text_alignment' => 'center',
            'about_image_position' => 'center',
            'about_show_image' => false,
            'about_show_video' => false,
        ]);

        $this->assertSame('center', $settings->aboutTextAlignment());
        $this->assertSame('center', $settings->aboutImagePosition());
        $this->assertFalse($settings->aboutShouldShowImage());
        $this->assertFalse($settings->aboutShouldShowVideo());
    }
}
