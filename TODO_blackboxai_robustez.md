# TODO - Robustez ponta a ponta (BLACKBOXAI)

## Passo 1 — Análise & plano

- [x] Ler `cerape/app/Filament/Resources/Acolhidos/Tables/AcolhidosTable.php`
- [x] Definir pontos frágeis: tooltip, tipagem/formatos, `afterStateUpdated`, filtros.
- [x] Confirmar com o usuário o escopo inicial.

## Passo 2 — Implementar correções

- [ ] Ajustar `AcolhidosTable.php`:
    - [ ] Melhorar `termo_desligamento` (conversão boolean/try-catch/sem falhas silenciosas)
    - [ ] Melhorar `data_nascimento` (exibição/ordenar de forma mais segura)
    - [ ] Tornar filtros mais robustos (CPF normalizado, trim, etc.)

## Passo 3 — Verificação

- [ ] Rodar `cd cerape && php artisan test`
- [ ] Se falhar, corrigir até passar

## Passo 4 — Limpeza

- [ ] Rodar `php artisan config:clear && php artisan optimize:clear`
