<?php

namespace App\Observers;

use App\Models\Acolhido;
use App\Models\Agenda;
use App\Models\ArquivosDiario;
use App\Models\BlogPost;
use App\Models\CmsContent;
use App\Models\ContactLead;
use App\Models\DemandaAcolhido;
use App\Models\DiariaTrabalho;
use App\Models\EmpresaParceira;
use App\Models\FrontendSetting;
use App\Models\FrenteTrabalho;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\GeradorAtividade;
use App\Models\HeroSlide;
use App\Models\HeroSlideTrash;
use App\Models\NewsletterSubscriber;
use App\Models\PillarCard;
use App\Models\ProntuarioEvolucao;
use App\Models\Reuniao;
use App\Models\Saude;
use App\Models\SaqueFinanceiro;
use App\Models\SubstanciaPsicoativas;
use App\Models\TeamMember;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Throwable;

class ActivityLogObserver
{
    public function __construct(private readonly ActivityLogService $activityLogService)
    {
    }

    public function created(Model $model): void
    {
        $this->record($model, 'created');
    }

    public function updated(Model $model): void
    {
        $this->record($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        $this->record($model, 'deleted');
    }

    public function restored(Model $model): void
    {
        $this->record($model, 'restored');
    }

    public function forceDeleted(Model $model): void
    {
        $this->record($model, 'force_deleted');
    }

    private function record(Model $model, string $event): void
    {
        try {
            [$module, $label] = $this->resolveModuleAndLabel($model);

            match ($event) {
                'created' => $this->activityLogService->recordModelCreated($model, $module, $label . ' criado'),
                'updated' => $this->activityLogService->recordModelUpdated($model, $module, $label . ' atualizado'),
                'deleted' => $this->activityLogService->recordModelDeleted($model, $module, $label . ' excluído'),
                'restored' => $this->activityLogService->recordManual($module, 'restored', $label . ' restaurado', $model, [], $model->attributesToArray()),
                'force_deleted' => $this->activityLogService->recordManual($module, 'force_deleted', $label . ' removido definitivamente', $model, $model->attributesToArray(), []),
                default => null,
            };
        } catch (Throwable $e) {
            report($e);
            Log::warning('Falha ao observar auditoria.', [
                'model' => $model::class,
                'event' => $event,
            ]);
        }
    }

    /**
     * @return array{0:string,1:string}
     */
    private function resolveModuleAndLabel(Model $model): array
    {
        return match (true) {
            $model instanceof User => ['Usuários', 'Usuário'],
            $model instanceof Acolhido => ['Acolhidos', 'Acolhido'],
            $model instanceof Agenda => ['Agenda', 'Agenda'],
            $model instanceof DiariaTrabalho => ['Financeiro', 'Diária de trabalho'],
            $model instanceof SaqueFinanceiro => ['Financeiro', 'Saque financeiro'],
            $model instanceof EmpresaParceira => ['Financeiro', 'Empresa parceira'],
            $model instanceof FrenteTrabalho => ['Financeiro', 'Frente de trabalho'],
            $model instanceof SubstanciaPsicoativas => ['Cadastros', 'Substância psicoativa'],
            $model instanceof DemandaAcolhido => ['Cadastros', 'Demanda do acolhido'],
            $model instanceof GalleryCategory => ['Galeria', 'Categoria da galeria'],
            $model instanceof GalleryItem => ['Galeria', 'Item da galeria'],
            $model instanceof BlogPost => ['Conteúdo', 'Post do blog'],
            $model instanceof CmsContent => ['Conteúdo', 'Conteúdo CMS'],
            $model instanceof TeamMember => ['Equipe', 'Membro da equipe'],
            $model instanceof PillarCard => ['Site público', 'Pilar'],
            $model instanceof FrontendSetting => ['Site público', 'Configuração do site'],
            $model instanceof ContactLead => ['Comunicação', 'Contato'],
            $model instanceof NewsletterSubscriber => ['Comunicação', 'Inscrito da newsletter'],
            $model instanceof HeroSlide => ['Site público', 'Slide do hero'],
            $model instanceof HeroSlideTrash => ['Site público', 'Lixeira do carrossel'],
            $model instanceof ProntuarioEvolucao => ['Prontuários', 'Evolução'],
            $model instanceof Saude => ['Saúde', 'Registro de saúde'],
            $model instanceof ArquivosDiario => ['Documentos', 'Arquivo diário'],
            $model instanceof Reuniao => ['Reuniões', 'Reunião'],
            $model instanceof GeradorAtividade => ['Documentos', 'Gerador de atividades'],
            default => ['Sistema', class_basename($model)],
        };
    }
}
