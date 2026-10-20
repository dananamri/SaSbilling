<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HakAksesTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(UserRole $role, string $username): User
    {
        return User::create([
            'name' => 'User Test '.$role->value,
            'username' => $username,
            'email' => $username.'@test.local',
            'password' => Hash::make('secret123'),
            'role' => $role,
        ]);
    }

    public function test_superadmin_can_open_hak_akses_page(): void
    {
        $this->actingAs($this->makeUser(UserRole::SuperAdmin, 'superadmin_hak1'));

        $this->get('/admin/hak-akses')->assertOk();
    }

    public function test_non_superadmin_cannot_open_hak_akses_page(): void
    {
        $this->actingAs($this->makeUser(UserRole::Admin, 'admin_hak'));

        $this->get('/admin/hak-akses')->assertStatus(403);
    }

    public function test_non_superadmin_blocked_even_when_role_unconfigured(): void
    {
        // Role admin belum pernah disetel (default diizinkan semua),
        // tetapi halaman hak akses tetap khusus super admin.
        $this->actingAs($this->makeUser(UserRole::Noc, 'noc_hak'));

        $this->put('/admin/hak-akses', ['role' => 'kasir', 'permissions' => []])->assertStatus(403);
    }

    public function test_superadmin_can_save_role_permissions(): void
    {
        $this->actingAs($this->makeUser(UserRole::SuperAdmin, 'superadmin_hak2'));

        $this->put('/admin/hak-akses', [
            'role' => 'kasir',
            'permissions' => ['invoices', 'payments'],
        ])->assertRedirect(route('admin.hak-akses.index', ['role' => 'kasir']));

        $this->assertDatabaseHas('role_permissions', ['role' => 'kasir']);

        $saved = RolePermission::where('role', 'kasir')->first();
        $this->assertEqualsCanonicalizing(['invoices', 'payments'], $saved->permissions);
    }

    public function test_save_rejects_unknown_menu_key(): void
    {
        $this->actingAs($this->makeUser(UserRole::SuperAdmin, 'superadmin_hak3'));

        $this->put('/admin/hak-akses', [
            'role' => 'kasir',
            'permissions' => ['menu-tidak-ada'],
        ])->assertSessionHasErrors('permissions.0');

        $this->assertDatabaseMissing('role_permissions', ['role' => 'kasir']);
    }

    public function test_unconfigured_role_can_access_all_menus(): void
    {
        $this->actingAs($this->makeUser(UserRole::Kasir, 'kasir_unconfigured'));

        $this->get('/admin/customers')->assertOk();
    }

    public function test_restricted_role_gets_403_on_unchecked_menu(): void
    {
        RolePermission::create(['role' => 'kasir', 'permissions' => ['payments']]);

        $this->actingAs($this->makeUser(UserRole::Kasir, 'kasir_restricted'));

        $this->get('/admin/customers')->assertStatus(403);
    }

    public function test_restricted_role_can_access_checked_menu(): void
    {
        RolePermission::create(['role' => 'kasir', 'permissions' => ['customers']]);

        $this->actingAs($this->makeUser(UserRole::Kasir, 'kasir_allowed'));

        $this->get('/admin/customers')->assertOk();
    }

    public function test_superadmin_bypasses_restriction(): void
    {
        RolePermission::create(['role' => 'superadmin', 'permissions' => []]);

        $this->actingAs($this->makeUser(UserRole::SuperAdmin, 'superadmin_hak4'));

        $this->get('/admin/customers')->assertOk();
    }

    public function test_empty_selection_blocks_every_menu(): void
    {
        $this->actingAs($this->makeUser(UserRole::SuperAdmin, 'superadmin_hak5'));
        $this->put('/admin/hak-akses', ['role' => 'teknisi'])->assertRedirect();

        $this->actingAs($this->makeUser(UserRole::Teknisi, 'teknisi_blocked'));

        $this->get('/admin/customers')->assertStatus(403);
    }

    public function test_restricted_menu_hidden_from_sidebar(): void
    {
        RolePermission::create(['role' => 'kasir', 'permissions' => ['payments']]);

        $this->actingAs($this->makeUser(UserRole::Kasir, 'kasir_sidebar'));

        $response = $this->get('/admin/dashboard');
        $response->assertOk();
        $response->assertDontSee('href="'.route('admin.olts.index').'"', false);
        $response->assertSee('href="'.route('admin.payments.index').'"', false);
    }

    public function test_hak_akses_menu_only_shows_for_superadmin(): void
    {
        $this->actingAs($this->makeUser(UserRole::Kasir, 'kasir_menu'));
        $this->get('/admin/dashboard')->assertOk()
            ->assertDontSee('href="'.route('admin.hak-akses.index').'"', false);

        $this->actingAs($this->makeUser(UserRole::SuperAdmin, 'superadmin_hak6'));
        $this->get('/admin/dashboard')->assertOk()
            ->assertSee('href="'.route('admin.hak-akses.index').'"', false);
    }
}
