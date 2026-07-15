# TODO - Dashboard CERAPE (Filament 5)

## Planejamento aprovado

Home do painel: manter `App\Filament\Pages\Dashboard` como base.
Implementar dashboard “hospitalar” criando widgets (cards, tabelas e gráficos) e registrando-os no `App\Providers\Filament\AdminPanelProvider`.

## Checklist de execução

- [ ]   1. Inspecionar modelos e recursos existentes para mapear: acolhidos ativos, consultas do dia, estoque baixo, altas do mês, agenda do dia, pendências, aniversariantes, notificações.
- [ ]   2. Implementar widget de indicadores/cards (StatsOverviewWidget equivalente).
- [ ]   3. Implementar widget(s) de tabela: Agenda de hoje, Últimos acolhidos, Pendências.
- [ ]   4. Implementar gráficos adicionais (se faltarem): Entradas x Altas, Evolução mensal, Faixa etária, Atendimentos por profissional.
- [ ]   5. Implementar widget de aniversariantes do dia.
- [ ]   6. Implementar widget de últimas notificações/avisos.
- [ ]   7. Registrar novos widgets em `AdminPanelProvider->widgets()` (mantendo os gráficos atuais existentes se fizer sentido).
- [ ]   8. Rodar `php artisan filament:discover` e validar renderização.
- [ ]   9. Ajustar queries para performance e permissões (incluindo `PortalContext::isFamilyUser()`).
- [ ]   10. Testar na interface do Filament e ajustar layout/ordem.
