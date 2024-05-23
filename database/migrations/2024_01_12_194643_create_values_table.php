<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// decrement quantity of available products when added to order
// add quantity to products table which is derived from quantity of attributes for each product 

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('values', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('attribute_id')->constrained('attributes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('image_path')->nullable();
            $table->string('image_name')->nullable();
            $table->double('price')->nullable();
            $table->char('currency', 3)->nullable();
            $table->integer('quantity')->default(0);
            $table->string("display_type");
            $table->string('value');

            $table->index(['product_id', 'attribute_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('values');
    }
};
