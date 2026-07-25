<?php

namespace Tests\Feature;

use App\Filament\Resources\Acolhidos\Pages\EditAcolhido;
use App\Models\Acolhido;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class EditAcolhidoStepSaveTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! in_array('sqlite', \PDO::getAvailableDrivers(), true)) {
            $this->markTestSkipped('O ambiente de testes atual não possui o driver PDO SQLite.');
        }

        Schema::dropIfExists('acolhidos');
        Schema::dropIfExists('users');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');

        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create('model_has_roles', function (Blueprint $table): void {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('acolhido_id')->nullable();
            $table->timestamps();
        });

        Schema::create('acolhidos', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('ativo')->default(true);
            $table->string('avatar')->nullable();
            $table->string('nome_completo_paciente')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('nome_do_conjuge')->nullable();
            $table->string('nome_da_mae')->nullable();
            $table->string('nome_do_pai')->nullable();
            $table->string('cor_da_pele')->nullable();
            $table->string('escolaridade')->nullable();
            $table->string('escolaridade_observacao')->nullable();
            $table->string('profissao')->nullable();
            $table->string('religiao')->nullable();
            $table->boolean('trabalha')->default(false);
            $table->string('nome_da_empresa_que_trabalha')->nullable();
            $table->boolean('tem_telefone')->default(false);
            $table->string('numero_do_telefone')->nullable();
            $table->boolean('tem_meio_de_encaminhamento')->default(false);
            $table->json('meio_de_encaminhamento')->nullable();
            $table->string('outro_meio_de_encaminhamento_qual')->nullable();
            $table->string('indicacao')->nullable();
            $table->boolean('toma_medicamento')->default(false);
            $table->json('qual_sao_as_medicacao')->nullable();
            $table->boolean('tem_receituario')->default(false);
            $table->json('receituario')->nullable();
            $table->boolean('exames_laboratoriais')->default(false);
            $table->string('outros')->nullable();
            $table->timestamps();
        });

        Gate::before(fn (): bool => true);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_a_valid_step_saves_when_a_required_field_in_another_step_is_invalid(): void
    {
        $record = $this->makeRecord();

        Livewire::actingAs(User::query()->findOrFail($record->user_id))
            ->test(EditAcolhido::class, ['record' => $record->getKey()])
            ->set('data.nome_completo_paciente', null) // Cadastro: required, but not this step.
            ->set('data.tem_telefone', true)
            ->set('data.numero_do_telefone', '(61) 99999-9999')
            ->call('saveStep', 'encaminhamento')
            ->assertHasNoErrors();

        $record->refresh();

        $this->assertSame('(61) 99999-9999', $record->numero_do_telefone);
        $this->assertSame('Nome preservado', $record->nome_completo_paciente);
    }

    public function test_current_step_required_rules_still_block_its_own_save(): void
    {
        $record = $this->makeRecord();

        Livewire::actingAs(User::query()->findOrFail($record->user_id))
            ->test(EditAcolhido::class, ['record' => $record->getKey()])
            ->set('data.toma_medicamento', null)
            ->call('saveStep', 'encaminhamento')
            ->assertHasErrors(['data.toma_medicamento' => 'required']);

        $this->assertFalse((bool) $record->fresh()->toma_medicamento);
    }

    public function test_only_the_whitelisted_attributes_of_the_saved_step_are_updated(): void
    {
        $record = $this->makeRecord();

        Livewire::actingAs(User::query()->findOrFail($record->user_id))
            ->test(EditAcolhido::class, ['record' => $record->getKey()])
            ->set('data.nome_completo_paciente', 'Tentativa de sobrescrever cadastro')
            ->set('data.tem_telefone', true)
            ->set('data.numero_do_telefone', '(61) 98888-7777')
            ->set('data.indicacao', 'CAPS')
            ->call('saveStep', 'encaminhamento')
            ->assertHasNoErrors();

        $record->refresh();

        $this->assertSame('Nome preservado', $record->nome_completo_paciente);
        $this->assertSame('(61) 98888-7777', $record->numero_do_telefone);
        $this->assertSame('CAPS', $record->indicacao);
    }

    public function test_a_visible_conditional_required_field_blocks_its_own_step_save(): void
    {
        $record = $this->makeRecord();

        Livewire::actingAs(User::query()->findOrFail($record->user_id))
            ->test(EditAcolhido::class, ['record' => $record->getKey()])
            ->set('data.toma_medicamento', true)
            ->set('data.tem_receituario', true)
            ->set('data.receituario', [])
            ->call('saveStep', 'encaminhamento')
            ->assertHasErrors(['data.receituario' => 'required']);

        $this->assertNull($record->fresh()->receituario);
    }

    public function test_a_hidden_conditional_field_does_not_block_the_step_save(): void
    {
        $record = $this->makeRecord();

        Livewire::actingAs(User::query()->findOrFail($record->user_id))
            ->test(EditAcolhido::class, ['record' => $record->getKey()])
            ->set('data.toma_medicamento', false)
            ->set('data.tem_receituario', false)
            ->set('data.receituario', null)
            ->set('data.tem_telefone', true)
            ->set('data.numero_do_telefone', '(61) 97777-6666')
            ->call('saveStep', 'encaminhamento')
            ->assertHasNoErrors();

        $record->refresh();

        $this->assertSame('(61) 97777-6666', $record->numero_do_telefone);
        $this->assertFalse((bool) $record->tem_receituario);
        $this->assertNull($record->receituario);
    }

    public function test_saving_a_step_with_an_existing_avatar_keeps_file_upload_state_compatible_with_livewire(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('documentos/acolhido-avatar/existing.jpg', 'image');

        $record = $this->makeRecord();
        $record->forceFill([
            'avatar' => 'documentos/acolhido-avatar/existing.jpg',
        ])->saveQuietly();

        Livewire::actingAs(User::query()->findOrFail($record->user_id))
            ->test(EditAcolhido::class, ['record' => $record->getKey()])
            ->call('saveStep', 'cadastro')
            ->assertHasNoErrors();

        $this->assertNotNull($record->fresh()->avatar);
    }

    private function makeRecord(): Acolhido
    {
        $user = User::query()->create([
            'name' => 'Usuário de teste',
            'email' => uniqid('acolhido-', true).'@example.test',
            'password' => 'secret',
        ]);

        return Acolhido::query()->create([
            'user_id' => $user->getKey(),
            'nome_completo_paciente' => 'Nome preservado',
            'data_nascimento' => '1990-01-01',
            'nome_da_mae' => 'Mae',
            'nome_do_pai' => 'Pai',
            'cor_da_pele' => 'Parda',
            'escolaridade' => 'ensino_medio_completo',
            'profissao' => 'Profissional',
            'religiao' => 'Nenhuma',
            'toma_medicamento' => false,
        ]);
    }
}
