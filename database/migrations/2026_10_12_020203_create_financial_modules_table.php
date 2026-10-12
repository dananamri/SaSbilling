<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pembukuan (Buku Kas Masuk / Keluar)
        Schema::create('book_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique();
            $table->enum('type', ['income', 'expense']);
            $table->string('category'); // e.g. Langganan, Backbone, Gaji, Listrik, Operasional, dsb.
            $table->decimal('amount', 14, 2);
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['type', 'date']);
            $table->index('category');
        });

        // 2. Setoran Kasir
        Schema::create('cash_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('deposit_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Kasir yang setor
            $table->decimal('amount', 14, 2);
            $table->date('deposit_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('proof_path')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['status', 'deposit_date']);
        });

        // 3. Tiket Pembayaran (Konfirmasi Transfer Pelanggan)
        Schema::create('payment_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 14, 2);
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->date('payment_date');
            $table->string('proof_path');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_tickets');
        Schema::dropIfExists('cash_deposits');
        Schema::dropIfExists('book_entries');
    }
};
