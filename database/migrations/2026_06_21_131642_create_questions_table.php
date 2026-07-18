<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_set_id')->constrained('question_sets')->onDelete('cascade');
            $table->integer('question_number');
            $table->enum('type', ['two_numbers', 'three_numbers', 'missing_number']);
            $table->integer('num1');
            $table->integer('num2');
            $table->integer('num3')->nullable();
            $table->integer('answer');
            $table->integer('missing_position')->nullable(); // 1, 2, or 3 for missing number
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
