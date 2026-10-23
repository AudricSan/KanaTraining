<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('twitch_id')->unique();
            $table->string('name', 50);
            $table->string('avatar')->default('');
            $table->string('email')->nullable();
            $table->unsignedInteger('global_xp')->default(0);
            $table->unsignedInteger('streak_days')->default(0);
            $table->unsignedInteger('high_score')->default(0);
            $table->unsignedInteger('last_score')->default(0);
            $table->date('last_played_date')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Leaderboard order
            $table->index(['global_xp', 'streak_days']);
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
