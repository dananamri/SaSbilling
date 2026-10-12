<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ODP (Optical Distribution Point) utk GIS
        Schema::create('odps', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedSmallInteger('capacity')->default(8);
            $table->unsignedSmallInteger('used_ports')->default(0);
            $table->enum('status', ['active', 'full', 'maintenance'])->default('active');
            $table->timestamps();
        });

        // OLT devices
        Schema::create('olts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('brand', ['zte', 'huawei', 'vsol', 'hioso', 'other'])->default('zte');
            $table->string('host'); // IP management
            $table->string('snmp_community')->default('public');
            $table->unsignedSmallInteger('snmp_port')->default(161);
            $table->string('telnet_username')->nullable();
            $table->string('telnet_password_encrypted')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_polled_at')->nullable();
            $table->json('last_status')->nullable();
            $table->timestamps();
        });

        // ONU/ONT per pelanggan
        Schema::create('onus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('olt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('pon_port')->nullable(); // e.g. gpon-olt_1/2/3
            $table->string('onu_index')->nullable(); // e.g. 5
            $table->string('serial_number')->unique();
            $table->string('model')->nullable();
            $table->decimal('rx_power', 6, 2)->nullable(); // dBm
            $table->decimal('tx_power', 6, 2)->nullable(); // dBm
            $table->decimal('temperature', 5, 2)->nullable();
            $table->enum('status', ['online', 'offline', 'los', 'unknown'])->default('unknown');
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();

            $table->index(['olt_id', 'status']);
        });

        // GACS V2 / TR-069 ACS devices (ONT remote management)
        Schema::create('acs_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('serial_number')->unique();
            $table->string('oui')->nullable();
            $table->string('product_class')->nullable();
            $table->string('model_name')->nullable();
            $table->string('firmware_version')->nullable();
            $table->string('ssid_2g')->nullable();
            $table->string('ssid_5g')->nullable();
            $table->timestamp('last_inform_at')->nullable();
            $table->json('parameters')->nullable();
            $table->timestamps();
        });

        // Extend customers: GIS koordinat + ODP
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('odp_id')->nullable()->after('package_id')->constrained()->nullOnDelete();
            $table->decimal('latitude', 10, 7)->nullable()->after('address');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('installation_address')->nullable()->after('longitude');
            $table->string('onu_serial')->nullable()->after('installation_address');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['odp_id']);
            $table->dropColumn(['odp_id', 'latitude', 'longitude', 'installation_address', 'onu_serial']);
        });
        Schema::dropIfExists('acs_devices');
        Schema::dropIfExists('onus');
        Schema::dropIfExists('olts');
        Schema::dropIfExists('odps');
    }
};
