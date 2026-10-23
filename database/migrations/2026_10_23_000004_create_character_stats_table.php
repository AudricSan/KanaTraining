<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-user, per-character answer counters: drives the weighted draw towards
     * characters the user struggles with, the "À travailler" list and category achievements.
     */
    public function up(): void
    {
        Schema::create('character_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('character_set_id')->constrained()->cascadeOnDelete();
            $table->string('character', 10);
            $table->unsignedInteger('wrong_count')->default(0);
            $table->unsignedInteger('correct_count')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'character_set_id', 'character']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_stats');
    }
};
