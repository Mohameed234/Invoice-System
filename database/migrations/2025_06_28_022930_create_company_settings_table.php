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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_email')->nullable();
            $table->string('company_phone')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_website')->nullable();
            $table->string('tax_number')->nullable(); // VAT/Tax ID
            $table->string('registration_number')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->text('invoice_terms')->nullable();
            $table->text('invoice_footer')->nullable();
            $table->string('invoice_prefix')->default('INV');
            $table->integer('invoice_start_number')->default(1);
            $table->integer('invoice_padding')->default(6); // Number of zeros to pad
            $table->string('default_currency', 3)->default('USD');
            $table->string('default_language', 2)->default('en');
            $table->boolean('show_tax_details')->default(true);
            $table->boolean('show_discount_details')->default(true);
            $table->boolean('enable_recurring_invoices')->default(false);
            $table->boolean('enable_attachments')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
