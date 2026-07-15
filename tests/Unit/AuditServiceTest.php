<?php

namespace Tests\Unit;

use App\Audit\Services\AuditService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(AuditService::class)]
class AuditServiceTest extends TestCase
{
    public function test_mask_sensitive_values_replaces_secret_fields(): void
    {
        $service = new AuditService(app(ActivityLogger::class));

        $payload = [
            'name' => 'Ana',
            'password' => 'secret',
            'access_token' => 'abc123',
            'metadata' => ['api_token' => 'xyz'],
        ];

        $masked = $service->maskSensitiveValues($payload);

        $this->assertSame('Ana', $masked['name']);
        $this->assertSame('********', $masked['password']);
        $this->assertSame('********', $masked['access_token']);
        $this->assertSame('********', $masked['metadata']['api_token']);
    }
}
