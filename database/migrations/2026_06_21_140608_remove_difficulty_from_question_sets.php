<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('question_sets', function (Blueprint $table) {
            $table->dropColumn('difficulty');
        });
    }

    public function down()
    {
        Schema::table('question_sets', function (Blueprint $table) {
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
        });
    }
};