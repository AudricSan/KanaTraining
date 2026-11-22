<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A character set is one toggle of the training menu (Hiragana, Katakana Combo, Kanji N3, ...).
     */
    public function up(): void
    {
        Schema::create('character_sets', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 30)->unique();
            $table->string('name', 50);
            $table->string('group', 30);
            $table->string('kind', 10);
            $table->unsignedSmallInteger('xp_value')->default(1);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_sets');
    }
};
