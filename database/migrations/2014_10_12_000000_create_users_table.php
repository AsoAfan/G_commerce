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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->index();
            /*
                $table->string('google_id')->nullable();
                $table->string('facebook_id')->nullable();
            */
            $table->string('username')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->unique()->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_name')->nullable();
            $table->string('password')->nullable();
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->foreignId('cart_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->integer('otp_code')->nullable();
            $table->string('otp_secret')->nullable();
            $table->string('otp_slug')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
