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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Created by
            $table->foreignId('currency_id')->constrained()->onDelete('cascade');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->decimal('tax_total', 15, 2)->default(0.00);
            $table->decimal('discount_total', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2)->default(0.00);
            $table->decimal('paid_amount', 15, 2)->default(0.00);
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->enum('status', ['draft', 'sent', 'paid', 'partially_paid', 'overdue', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->string('payment_terms')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->integer('recurring_interval')->nullable(); // Days between recurring invoices
            $table->date('next_recurring_date')->nullable();
            $table->timestamps();

            $table->index(['invoice_number', 'status']);
            $table->index(['customer_id', 'status']);
            $table->index(['invoice_date', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
