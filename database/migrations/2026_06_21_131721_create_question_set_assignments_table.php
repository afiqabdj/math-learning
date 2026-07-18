<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('question_set_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_set_id')->constrained('question_sets')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['assigned', 'in_progress', 'completed'])->default('assigned');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->integer('score')->nullable();
            $table->integer('total_attempts')->default(0);
            $table->timestamps();
            
            $table->unique(['question_set_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_set_assignments');
    }
};