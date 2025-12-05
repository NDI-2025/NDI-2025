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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->unique()->nullable()->after('name');
            $table->string('voice_hash')->nullable()->after('password');
            $table->text('secret_phrase')->nullable()->after('voice_hash');
            $table->timestamp('voice_registered_at')->nullable()->after('secret_phrase');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'voice_hash', 'secret_phrase', 'voice_registered_at']);
        });
    }
};

