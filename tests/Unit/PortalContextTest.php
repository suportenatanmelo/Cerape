<?php

namespace Tests\Unit;

use App\Support\PortalContext;
use Tests\TestCase;

class PortalContextTest extends TestCase
{
    public function test_portal_navigation_group_uses_canonical_acolhimento_label(): void
    {
        $this->assertSame('Cadastros e Acolhimento', PortalContext::portalNavigationGroup());
    }
}
