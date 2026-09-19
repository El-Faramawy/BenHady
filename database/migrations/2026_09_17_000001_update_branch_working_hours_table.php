<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branch_working_hours', function (Blueprint $table) {
            if (Schema::hasColumn('branch_working_hours', 'opening_time') && !Schema::hasColumn('branch_working_hours', 'open_at')) {
                $table->renameColumn('opening_time', 'open_at');
            }

            if (Schema::hasColumn('branch_working_hours', 'closing_time') && !Schema::hasColumn('branch_working_hours', 'close_at')) {
                $table->renameColumn('closing_time', 'close_at');
            }

            if (!Schema::hasColumn('branch_working_hours', 'reservation_start_at')) {
                $table->time('reservation_start_at')->nullable();
            }

            if (!Schema::hasColumn('branch_working_hours', 'reservation_close_at')) {
                $table->time('reservation_close_at')->nullable();
            }
        });

        // Populate reservation times from open_at and close_at if empty
        if (Schema::hasColumn('branch_working_hours', 'open_at') && Schema::hasColumn('branch_working_hours', 'reservation_start_at')) {
            DB::table('branch_working_hours')
                ->whereNull('reservation_start_at')
                ->whereNotNull('open_at')
                ->update([
                    'reservation_start_at' => DB::raw('open_at'),
                    'reservation_close_at' => DB::raw('close_at'),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('branch_working_hours', function (Blueprint $table) {
            if (Schema::hasColumn('branch_working_hours', 'reservation_close_at')) {
                $table->dropColumn('reservation_close_at');
            }

            if (Schema::hasColumn('branch_working_hours', 'reservation_start_at')) {
                $table->dropColumn('reservation_start_at');
            }

            if (Schema::hasColumn('branch_working_hours', 'open_at') && !Schema::hasColumn('branch_working_hours', 'opening_time')) {
                $table->renameColumn('open_at', 'opening_time');
            }

            if (Schema::hasColumn('branch_working_hours', 'close_at') && !Schema::hasColumn('branch_working_hours', 'closing_time')) {
                $table->renameColumn('close_at', 'closing_time');
            }
        });
    }
};
