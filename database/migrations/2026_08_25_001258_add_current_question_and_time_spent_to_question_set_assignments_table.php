<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('question_set_assignments', function (Blueprint $table) {
            // Only add columns that don't already exist
            if (!Schema::hasColumn('question_set_assignments', 'current_question_index')) {
                $table->integer('current_question_index')->default(0)->after('status');
            }
            
            if (!Schema::hasColumn('question_set_assignments', 'time_spent')) {
                $table->integer('time_spent')->default(0)->after('current_question_index');
            }
            
            // started_at already exists, so don't add it again
            // if (!Schema::hasColumn('question_set_assignments', 'started_at')) {
            //     $table->timestamp('started_at')->nullable()->after('time_spent');
            // }
        });
    }

    public function down(): void
    {
        Schema::table('question_set_assignments', function (Blueprint $table) {
            $columns = ['current_question_index', 'time_spent'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('question_set_assignments', $column)) {
                    $table->dropColumn($column);
                }
            }
            // Don't drop started_at if it was added elsewhere
        });
    }
};