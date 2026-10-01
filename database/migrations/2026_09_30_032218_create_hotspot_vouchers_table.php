<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotspot_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('hotspot_profiles')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('status')->default('available');
            $table->timestamp('used_at')->nullable();
            $table->foreignId('used_by')->nullable()->constrained('hotspot_members')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotspot_vouchers');
    }
};
