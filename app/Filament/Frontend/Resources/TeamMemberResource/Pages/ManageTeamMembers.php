<?php

namespace App\Filament\Frontend\Resources\TeamMemberResource\Pages;

use App\Filament\Frontend\Resources\TeamMemberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTeamMembers extends ManageRecords
{
    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
