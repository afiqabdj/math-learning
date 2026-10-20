<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('question_set_assignments', function (Blueprint $table) {
            // Add time_taken column if it doesn't exist
            if (!Schema::hasColumn('question_set_assignments', 'time_taken')) {
                $table->integer('time_taken')->nullable()->after('time_spent');
            }
            
            // Add total_attempts column if it doesn't exist
            if (!Schema::hasColumn('question_set_assignments', 'total_attempts')) {
                $table->integer('total_attempts')->default(0)->after('time_taken');
            }
        });
    }

    public function down(): void
    {
        Schema::table('question_set_assignments', function (Blueprint $table) {
            $table->dropColumn(['time_taken', 'total_attempts']);
        });
    }
};