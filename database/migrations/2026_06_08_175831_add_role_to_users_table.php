<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users_congak', function (Blueprint $table) {
            $table->enum('role', ['student', 'teacher'])->default('student')->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users_congak', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};