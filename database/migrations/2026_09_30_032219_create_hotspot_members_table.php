<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotspot_members', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('fullname')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('profile_id')->nullable()->constrained('hotspot_profiles')->nullOnDelete();
            $table->string('status')->default('active');
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('username');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotspot_members');
    }
};
