<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Support\UserRoleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AccessControlStabilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_user_role_manager_can_reduce_roles_to_a_single_profile(): void
    {
        $user = User::factory()->create();
        $roleA = Role::query()->create(['name' => 'perfil_a', 'guard_name' => 'web']);
        $roleB = Role::query()->create(['name' => 'perfil_b', 'guard_name' => 'web']);

        UserRoleManager::syncRoles($user, [$roleA->id, $roleB->id]);
        UserRoleManager::syncRoles($user, [(string) $roleA->id]);

        $this->assertSame(['perfil_a'], $user->fresh()->roles()->pluck('name')->all());
    }

    public function test_user_role_manager_can_remove_all_profiles_without_reassigning_everything(): void
    {
        $user = User::factory()->create();
        $roleA = Role::query()->create(['name' => 'perfil_a', 'guard_name' => 'web']);
        $roleB = Role::query()->create(['name' => 'perfil_b', 'guard_name' => 'web']);

        UserRoleManager::syncRoles($user, [$roleA->id, $roleB->id]);
        UserRoleManager::syncRoles($user, []);

        $this->assertSame([], $user->fresh()->roles()->pluck('name')->all());
    }

    public function test_user_policy_accepts_normalized_shield_permission_names(): void
    {
        $user = User::factory()->create();

        Permission::query()->create([
            'name' => 'view_any:user',
            'guard_name' => 'web',
        ]);

        $user->givePermissionTo('view_any:user');

        $this->assertTrue(app(UserPolicy::class)->viewAny($user));
    }

    public function test_restricted_family_user_cannot_manage_roles(): void
    {
        $user = User::factory()->make([
            'acolhido_id' => 123,
        ]);

        $this->assertFalse(app(RolePolicy::class)->viewAny($user));
    }
}
