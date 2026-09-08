<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('model_year_id')->constrained('model_years')->cascadeOnDelete();
            $table->foreignId('transmission_id')->constrained('transmissions')->cascadeOnDelete();
            $table->foreignId('fuel_type_id')->constrained('fuel_types')->cascadeOnDelete();
            $table->string('name_ar');
            $table->string('name_en');
            $table->integer('seats')->default(5);
            $table->decimal('daily_price', 10, 2);
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_handpicked')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
