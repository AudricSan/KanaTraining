<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_set_id')->constrained()->cascadeOnDelete();
            $table->string('character', 10);
            // Accepted romaji, separated by "-" (e.g. "chi-ti-tsi")
            $table->string('romaji', 50);
            // Obsolete kana (ゐ, ゑ, ...) stay listed in the help table but are never drawn
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['character_set_id', 'character']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kana');
    }
};
