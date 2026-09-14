<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_working_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('day_of_week');
            $table->boolean('is_open')->default(true);
            $table->time('open_at')->nullable();
            $table->time('close_at')->nullable();
            $table->time('reservation_start_at')->nullable();
            $table->time('reservation_close_at')->nullable();
            $table->boolean('is_24_hours')->default(false);
            $table->timestamps();

            $table->unique(['branch_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_working_hours');
    }
};
