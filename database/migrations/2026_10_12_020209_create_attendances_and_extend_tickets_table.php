<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Absensi Pegawai & Teknisi
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->decimal('latitude_in', 10, 7)->nullable();
            $table->decimal('longitude_in', 10, 7)->nullable();
            $table->decimal('latitude_out', 10, 7)->nullable();
            $table->decimal('longitude_out', 10, 7)->nullable();
            $table->string('photo_in')->nullable();
            $table->string('photo_out')->nullable();
            $table->enum('status', ['present', 'late', 'sick', 'leave', 'absent'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'date']);
            $table->index(['date', 'status']);
        });

        // Extend tickets table: Helpdesk & Technician Workflow
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('assigned_to')->nullable()->after('customer_id')->constrained('users')->nullOnDelete();
            $table->timestamp('sla_due_at')->nullable()->after('priority');
            $table->timestamp('resolved_at')->nullable()->after('sla_due_at');
            $table->text('resolution_notes')->nullable()->after('resolved_at');
            $table->string('proof_photo')->nullable()->after('resolution_notes');
        });

        // Tenants (Multi-Tenancy)
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // e.g. CABANG-01
            $table->string('company_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn(['assigned_to', 'sla_due_at', 'resolved_at', 'resolution_notes', 'proof_photo']);
        });
        Schema::dropIfExists('attendances');
    }
};
