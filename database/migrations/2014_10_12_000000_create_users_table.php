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
        /* ---------- USERS TABLE ---------- */
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            //  your specified identity columns
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('username', 50)->unique();
            $table->string('email')->unique();

            //  auth‑related columns
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');            // store ONLY the hashed password
            $table->rememberToken();               // for “remember‑me” login

            $table->timestamps();                  // created_at & updated_at
        });

        /* ---------- PASSWORD‑RESET TOKENS ---------- */
        // uses same structure as Laravel 11/12 starter kits :contentReference[oaicite:0]{index=0}
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        /* ---------- DATABASE SESSION STORE ---------- */
        // identical to schema produced by  `php artisan session:table` :contentReference[oaicite:1]{index=1}
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
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
