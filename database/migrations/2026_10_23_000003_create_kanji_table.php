<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kanji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_set_id')->constrained()->cascadeOnDelete();
            $table->string('character', 10);
            // Accepted readings / meanings, separated by "-"
            $table->string('readings');
            $table->string('meanings');
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['character_set_id', 'character']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanji');
    }
};
