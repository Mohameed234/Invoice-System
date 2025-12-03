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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('unit_price', 15, 2);
            $table->decimal('tax_rate', 5, 2)->default(0.00); // Tax percentage
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['product', 'service'])->default('product');
            $table->string('sku')->nullable(); // Stock Keeping Unit
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['name', 'type']);
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
