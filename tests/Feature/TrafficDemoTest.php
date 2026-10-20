<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\BandwidthSnapshot;
use App\Models\Router;
use App\Models\Setting;
use App\Models\User;
use App\Support\TrafficDemo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TrafficDemoTest extends TestCase
{
    use RefreshDatabase;

    private function makeSuperAdmin(): User
    {
        return User::create([
            'name' => 'Super Admin Demo',
            'username' => 'superadmin_demo',
            'email' => 'superadmin_demo@test.local',
            'password' => Hash::make('secret123'),
            'role' => UserRole::SuperAdmin,
        ]);
    }

    public function test_seed_creates_six_months_of_demo_traffic(): void
    {
        TrafficDemo::seed(2);

        $this->assertSame(18, BandwidthSnapshot::count());
        $this->assertSame(1, Router::where('name', 'DEMO Traffic (autohapus)')->count());

        $months = BandwidthSnapshot::query()
            ->selectRaw('DISTINCT strftime("%Y-%m", created_at) as month')
            ->pluck('month')
            ->count();
        $this->assertSame(6, $months);
    }

    public function test_cleanup_keeps_demo_until_expiry_then_removes_it(): void
    {
        TrafficDemo::seed(2);

        // Sebelum masa berlaku habis: data tetap ada.
        $this->assertFalse(TrafficDemo::cleanup());
        $this->assertSame(18, BandwidthSnapshot::count());

        $this->travel(3)->minutes();

        $this->assertTrue(TrafficDemo::cleanup());
        $this->assertSame(0, BandwidthSnapshot::count());
        $this->assertSame(0, Router::where('name', 'DEMO Traffic (autohapus)')->count());
        $this->assertNull(Setting::get(TrafficDemo::SETTING_KEY));
    }

    public function test_seeding_twice_replaces_previous_demo(): void
    {
        TrafficDemo::seed(2);
        TrafficDemo::seed(2);

        $this->assertSame(18, BandwidthSnapshot::count());
        $this->assertSame(1, Router::where('name', 'DEMO Traffic (autohapus)')->count());
    }

    public function test_real_snapshots_survive_demo_cleanup(): void
    {
        $realRouter = Router::create([
            'name' => 'Router Kantor',
            'host' => '10.10.10.1',
            'username' => 'admin',
            'password_encrypted' => 'rahasia',
            'is_active' => true,
        ]);
        $real = BandwidthSnapshot::create([
            'router_id' => $realRouter->id,
            'interface_name' => 'ether1',
            'rx_bytes' => 1024,
            'tx_bytes' => 512,
            'recorded_at' => now(),
        ]);

        TrafficDemo::seed(2);
        $this->travel(3)->minutes();
        TrafficDemo::cleanup();

        $this->assertDatabaseHas('bandwidth_snapshots', ['id' => $real->id]);
        $this->assertDatabaseHas('routers', ['id' => $realRouter->id]);
    }

    public function test_dashboard_open_after_expiry_removes_demo_automatically(): void
    {
        TrafficDemo::seed(2);
        $this->assertSame(18, BandwidthSnapshot::count());

        $this->travel(3)->minutes();

        $this->actingAs($this->makeSuperAdmin());
        $this->get('/admin/dashboard')->assertOk();

        $this->assertSame(0, BandwidthSnapshot::count());
        $this->assertNull(Setting::get(TrafficDemo::SETTING_KEY));
    }
}
