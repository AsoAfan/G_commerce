<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_sub_category', function (Blueprint $table) {

            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('sub_category_id')->constrained('sub_categories')->cascadeOnDelete();

            $table->unique(['product_id', 'sub_category_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sub_category');
    }
};
