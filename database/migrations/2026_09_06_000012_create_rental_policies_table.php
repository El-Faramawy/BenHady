<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_policies', function (Blueprint $table) {
            $table->id();
            $table->text('tenant_instructions_ar');
            $table->text('tenant_instructions_en')->nullable();
            $table->text('insurance_policy_ar');
            $table->text('insurance_policy_en')->nullable();
            $table->text('cancellation_policy_ar');
            $table->text('cancellation_policy_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_policies');
    }
};
