<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Students can now log in with several providers: the Twitch id moves
     * from users to one social_accounts row per identity.
     */
    public function up(): void
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 20);
            $table->string('provider_id');
            $table->string('nickname')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'provider_id']);
            $table->unique(['user_id', 'provider']);
        });

        $now = now();
        DB::table('users')->whereNotNull('twitch_id')->orderBy('id')->each(function ($user) use ($now) {
            DB::table('social_accounts')->insert([
                'user_id' => $user->id,
                'provider' => 'twitch',
                'provider_id' => $user->twitch_id,
                'nickname' => $user->name,
                'avatar' => $user->avatar,
                'created_at' => $user->created_at ?? $now,
                'updated_at' => $now,
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['twitch_id']);
            $table->dropColumn('twitch_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('twitch_id')->nullable()->unique()->after('id');
        });

        DB::table('social_accounts')->where('provider', 'twitch')->orderBy('id')->each(function ($account) {
            DB::table('users')->where('id', $account->user_id)->update(['twitch_id' => $account->provider_id]);
        });

        Schema::dropIfExists('social_accounts');
    }
};
