# TODO - Desligamento Acolhido (Histórico de Desligamento)

- [ ] Criar migration e tabela `acolhido_desligamentos` (FK para `acolhidos`)
- [ ] Criar model `App\Models\AcolhidoDesligamento` com relacionamento `belongsTo(Acolhido)`
- [ ] Atualizar `App\Models\Acolhido` adicionando `hasMany(AcolhidoDesligamento)`
- [x] Atualizar `App\Filament\Pages\DesligamentoAcolhido`:
    - [x] ao enviar, persistir os dados do desligamento em `acolhido_desligamentos`
    - [x] manter (por compatibilidade) update atual em `Acolhidos` (formado/termo_desligamento/ativo)

- [ ] Criar Filament Resource `Desligamentos/DesligamentoResource.php`
- [ ] Criar Pages Filament:
    - [ ] `ListDesligamentos`
    - [ ] `ViewDesligamento`
    - [ ] `EditDesligamento`
- [ ] Ajustar navegação/grupo do Filament para aparecer como “Desligamentos”
- [ ] Rodar `php artisan migrate`
- [ ] Testar no Filament: criar (via page desligamento-acolhido) e depois listar/editar/excluir desligamentos
