<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('active');
            $table->unsignedTinyInteger('billing_day')->default(1);
            $table->date('joined_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('billing_day');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
