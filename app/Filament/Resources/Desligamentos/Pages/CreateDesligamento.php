<?php

namespace App\Filament\Resources\Desligamentos\Pages;

use App\Filament\Resources\Desligamentos\DesligamentoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDesligamento extends CreateRecord
{
    protected static string $resource = DesligamentoResource::class;

    // Custom view generation is not configured via static $view in this Filament version.
    // The create page will keep the default Filament layout.
}