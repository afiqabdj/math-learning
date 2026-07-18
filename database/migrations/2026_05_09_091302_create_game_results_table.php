<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('level_number');
            $table->integer('cycle')->comment('1 = Practice, 2 = Challenge');
            $table->integer('score');
            $table->integer('accuracy');
            $table->integer('time_taken')->nullable()->comment('time spent or time remaining');
            $table->boolean('is_completed')->default(true);
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Index for faster queries
            $table->index(['user_id', 'level_number', 'cycle']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_results');
    }
};