<?php

namespace Tests\Unit;

use App\Filament\Resources\CmsBlocks\BlockResource;
use PHPUnit\Framework\TestCase;

class BlockResourceTest extends TestCase
{
    public function test_block_resource_navigation_group_type_matches_filament_contract(): void
    {
        $reflection = new \ReflectionClass(BlockResource::class);
        $property = $reflection->getProperty('navigationGroup');
        $type = $property->getType();

        $this->assertNotNull($type);
        $this->assertSame('UnitEnum|string|null', (string) $type);
    }
}
