<?php

namespace Tests\Unit;

use App\Filament\Resources\Roles\RoleResource;
use App\Support\PortalContext;
use PHPUnit\Framework\TestCase;

class RoleResourceNavigationTest extends TestCase
{
    public function test_role_resource_does_not_register_navigation_for_admin_panel_users(): void
    {
        $this->assertFalse(RoleResource::shouldRegisterNavigation());
    }
}
