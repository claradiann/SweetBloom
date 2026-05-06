<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 255)->unique();
            $table->string('password');
            $table->enum('role', ['customer', 'admin'])->default('customer');

            // Konfirmasi email
            $table->boolean('is_confirmed')->default(false);
            $table->string('confirm_token', 64)->nullable()->index();
            $table->dateTime('confirm_expires')->nullable();

            // Reset password
            $table->string('reset_token', 64)->nullable()->index();
            $table->dateTime('reset_expires')->nullable();

            // Brute-force protection
            $table->unsignedTinyInteger('login_attempts')->default(0);
            $table->dateTime('locked_until')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};