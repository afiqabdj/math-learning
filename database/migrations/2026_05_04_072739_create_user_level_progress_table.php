<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_level_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('level_id')->constrained()->onDelete('cascade');
            
            // Cycle 1 (Practice Mode)
            $table->boolean('cycle1_completed')->default(false);
            $table->integer('cycle1_best_time')->nullable();
            $table->integer('cycle1_attempts')->default(0);
            $table->integer('cycle1_last_score')->default(0);
            
            // Cycle 2 (Timed Challenge Mode)
            $table->boolean('cycle2_completed')->default(false);
            $table->integer('cycle2_best_marks')->default(0);
            $table->integer('cycle2_attempts')->default(0);
            $table->integer('cycle2_last_score')->default(0);
            
            $table->integer('stars_earned')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'level_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_level_progress');
    }
};