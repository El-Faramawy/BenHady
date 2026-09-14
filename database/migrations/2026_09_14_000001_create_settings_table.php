<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('fav_icon')->nullable();
            $table->text('about_us_ar')->nullable();
            $table->text('about_us_en')->nullable();
            $table->text('terms_conditions_ar')->nullable();
            $table->text('terms_conditions_en')->nullable();
            $table->text('privacy_policy_ar')->nullable();
            $table->text('privacy_policy_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
