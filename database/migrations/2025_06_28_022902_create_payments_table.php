<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Recorded by
            $table->string('payment_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'credit_card', 'wallet', 'check', 'other'])->default('cash');
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->string('reference_number')->nullable(); // Bank reference, check number, etc.
            $table->string('transaction_id')->nullable(); // For online payments
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');
            $table->timestamps();

            $table->index(['invoice_id', 'payment_date']);
            $table->index(['payment_method', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
