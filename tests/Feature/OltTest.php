<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OltTest extends TestCase
{
    use RefreshDatabase;

    private function makeSuperAdmin(): User
    {
        return User::create([
            'name' => 'Super Admin OLT',
            'username' => 'superadmin_olt',
            'email' => 'superadmin_olt@test.local',
            'password' => Hash::make('secret123'),
            'role' => UserRole::SuperAdmin,
        ]);
    }

    public function test_index_page_shows_tambah_olt_button(): void
    {
        $this->actingAs($this->makeSuperAdmin());

        $this->get('/admin/olts')
            ->assertOk()
            ->assertSee('+ Tambah OLT')
            ->assertSee(route('admin.olts.create'), false);
    }

    public function test_create_page_renders_form(): void
    {
        // Sebelum fix: Route [admin.olt.index] tidak ada -> 500 di tombol Batal.
        $this->actingAs($this->makeSuperAdmin());

        $this->get('/admin/olts/create')
            ->assertOk()
            ->assertSee(route('admin.olts.store'), false)
            ->assertSee(route('admin.olts.index'), false);
    }

    public function test_store_creates_olt_and_redirects_to_index(): void
    {
        $this->actingAs($this->makeSuperAdmin());

        $response = $this->post('/admin/olts', [
            'name' => 'OLT Test Utama',
            'brand' => 'zte',
            'host' => '10.10.10.10',
            'snmp_community' => 'public',
            'location' => 'POP Test',
        ]);

        $response->assertRedirect(route('admin.olts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('olts', [
            'name' => 'OLT Test Utama',
            'brand' => 'zte',
            'host' => '10.10.10.10',
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->actingAs($this->makeSuperAdmin());

        $response = $this->post('/admin/olts', ['name' => '']);

        $response->assertSessionHasErrors(['name', 'brand', 'host', 'snmp_community']);
        $this->assertDatabaseCount('olts', 0);
    }
}
