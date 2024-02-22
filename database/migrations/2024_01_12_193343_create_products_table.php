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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid("id")->index();
            $table->string('name');
            $table->text('description')->nullable();

            # Moved to values table
//            $table->integer('quantity')->default(0);
//            $table->double('price');
//            $table->char('currency', 3)->default('IQD');
//            $table->string('image_path');
//            $table->string('image_name');

            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignUuid('discount_id')->nullable()->constrained()->nullOnDelete();
//            $table->foreignId('group_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
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
