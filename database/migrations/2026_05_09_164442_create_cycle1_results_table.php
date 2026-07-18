<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cycle1_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('level_number');
            $table->integer('questions_answered')->comment('Number of questions answered in given time');
            $table->integer('total_questions')->default(10);
            $table->integer('time_limit')->comment('Time limit in seconds (60 seconds default)');
            $table->integer('correct_answers');
            $table->integer('wrong_answers');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cycle1_results');
    }
};