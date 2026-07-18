<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users_congak')->onDelete('cascade');
            $table->foreignId('question_set_id')->constrained('question_sets')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->integer('student_answer');
            $table->boolean('is_correct')->default(false);
            $table->integer('time_taken')->nullable();
            $table->integer('attempt_number')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_attempts');
    }
};