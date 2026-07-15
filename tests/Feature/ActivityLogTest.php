<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_persists_an_audit_log_entry(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        AuditLogger::log(
            model: $user,
            event: 'created',
            action: 'create',
            oldValues: [],
            newValues: ['name' => $user->name],
            message: 'Usuário criado',
        );

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'resource' => 'User',
            'event' => 'created',
            'action' => 'create',
            'status' => 'success',
        ]);
    }
}
