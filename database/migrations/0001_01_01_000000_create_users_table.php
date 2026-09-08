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
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('type')->nullable();
            
            $table->string('id_number')->unique()->nullable();
            $table->date('id_number_end_date')->nullable();
            $table->string('version_number')->nullable();

            $table->string('license_number')->nullable();
            $table->string('border_entry_number')->unique()->nullable();

            $table->string('driving_license_number')->nullable();
            $table->date('license_expiry_date')->nullable();

            $table->double('wallet')->default(0.0);
            $table->string('language')->nullable();
            $table->string('status')->nullable();
            $table->boolean('phone_verified')->default(false);
            $table->boolean('licence_verified')->default(false);
            $table->boolean('id_verified')->default(false);

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
