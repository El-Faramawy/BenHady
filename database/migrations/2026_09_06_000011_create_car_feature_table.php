<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_feature', function (Blueprint $table) {
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->foreignId('feature_id')->constrained('features')->cascadeOnDelete();
            $table->primary(['car_id', 'feature_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_feature');
    }
};
