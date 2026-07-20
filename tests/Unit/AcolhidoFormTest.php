<?php

namespace Tests\Unit;

use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use Filament\Forms\Components\Radio;
use Tests\TestCase;

class AcolhidoFormTest extends TestCase
{
    public function test_documentation_fields_are_required_only_when_tem_documentacao_is_true(): void
    {
        $this->assertTrue(AcolhidoForm::shouldRequireDocumentationFields(true));
        $this->assertTrue(AcolhidoForm::shouldRequireDocumentationFields(1));
        $this->assertTrue(AcolhidoForm::shouldRequireDocumentationFields('1'));
        $this->assertTrue(AcolhidoForm::shouldRequireDocumentationFields('sim'));

        $this->assertFalse(AcolhidoForm::shouldRequireDocumentationFields(false));
        $this->assertFalse(AcolhidoForm::shouldRequireDocumentationFields(0));
        $this->assertFalse(AcolhidoForm::shouldRequireDocumentationFields('0'));
        $this->assertFalse(AcolhidoForm::shouldRequireDocumentationFields('nao'));
    }

    public function test_tem_documentacao_radio_validation_rules_do_not_throw_when_evaluated(): void
    {
        $component = Radio::make('tem_documentacao');

        $component->required();

        $rules = $component->getValidationRules();

        $this->assertContains('required', $rules);
        $this->assertCount(2, $rules);
    }
}
