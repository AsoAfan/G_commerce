<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('cart_id')->unique()->index()->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'delivered']);
            $table->foreignUuid('location_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
