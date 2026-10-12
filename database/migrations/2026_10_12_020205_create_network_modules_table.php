<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Routers (MikroTik)
        Schema::create('routers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('host'); // IP / hostname
            $table->unsignedSmallInteger('port')->default(8728);
            $table->string('username');
            $table->string('password_encrypted');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_checked_at')->nullable();
            $table->json('last_status')->nullable(); // cpu, mem, uptime, version
            $table->timestamps();
        });

        // PPPoE Profiles (sinkron ke MikroTik /ppp profile)
        Schema::create('pppoe_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->constrained()->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('local_address')->nullable();
            $table->string('remote_address')->nullable(); // pool name
            $table->string('rate_limit')->nullable(); // e.g. 10M/10M
            $table->string('dns_server')->nullable();
            $table->timestamps();
        });

        // PPPoE Secrets (akun pelanggan PPPoE)
        Schema::create('pppoe_secrets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pppoe_profile_id')->nullable()->constrained('pppoe_profiles')->nullOnDelete();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('service')->default('pppoe');
            $table->string('local_address')->nullable();
            $table->string('remote_address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('comment')->nullable();
            $table->timestamp('last_connected_at')->nullable();
            $table->timestamps();

            $table->index(['router_id', 'is_active']);
        });

        // RouterBox Mobile (tunnel agent utk router tanpa IP publik)
        Schema::create('router_boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->constrained()->cascadeOnDelete();
            $table->string('box_id')->unique();
            $table->string('token')->unique();
            $table->enum('status', ['online', 'offline'])->default('offline');
            $table->string('remote_ip')->nullable();
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->timestamps();
        });

        // Bandwidth Monitoring (snapshot per interface)
        Schema::create('bandwidth_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->constrained()->cascadeOnDelete();
            $table->string('interface_name');
            $table->bigInteger('rx_bytes')->default(0);
            $table->bigInteger('tx_bytes')->default(0);
            $table->unsignedInteger('rx_bps')->default(0);
            $table->unsignedInteger('tx_bps')->default(0);
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();

            $table->index(['router_id', 'interface_name', 'recorded_at']);
        });

        // RADIUS Servers (FreeRADIUS / RADIUS Mobile config)
        Schema::create('radius_servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('host');
            $table->unsignedSmallInteger('auth_port')->default(1812);
            $table->unsignedSmallInteger('acct_port')->default(1813);
            $table->string('secret');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('radius_servers');
        Schema::dropIfExists('bandwidth_snapshots');
        Schema::dropIfExists('router_boxes');
        Schema::dropIfExists('pppoe_secrets');
        Schema::dropIfExists('pppoe_profiles');
        Schema::dropIfExists('routers');
    }
};
