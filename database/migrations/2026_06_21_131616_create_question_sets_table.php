<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('question_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users_congak')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->enum('operation_type', ['addition', 'subtraction', 'mixed'])->default('addition');
            $table->integer('number_of_questions')->default(10);
            $table->integer('time_limit')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('min_value')->default(0);
            $table->integer('max_value')->default(20);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_sets');
    }
};