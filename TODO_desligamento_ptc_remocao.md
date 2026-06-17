# Remoção: tabela `acolhido_desligamentos` e telas/rota admin/desligamentos

## Checklist

- [ ] Remover código que persiste em `acolhido_desligamentos` (Page `DesligamentoAcolhido`).
- [ ] Remover model `AcolhidoDesligamento`.
- [ ] Remover relação `Acolhido::desligamentos()`.
- [ ] Remover Resource Filament `Desligamentos` (admin/desligamentos) e Pages (List/View/Edit).
- [ ] Remover migração `2026_06_07_000000_create_acolhido_desligamentos_table.php`.
- [ ] Rodar `php artisan optimize:clear` e checar se aplicação/Filament sobe sem erros.
