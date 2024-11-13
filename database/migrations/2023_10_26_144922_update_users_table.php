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
        Schema::whenTableHasColumn('users', 'password', function (Blueprint $table) {
            $table->dropColumn('password');
        });

        Schema::whenTableDoesntHaveColumn('users', 'authentication_token', function (Blueprint $table) {
            $table->string('authentication_token')->nullable();
        });

        Schema::whenTableDoesntHaveColumn('users', 'authentication_token_generated_at', function (Blueprint $table) {
            $table->timestamp('authentication_token_generated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::whenTableDoesntHaveColumn('users', 'password', function (Blueprint $table) {
            $table->string('password')->default("Saisir un mot de passe");
        });

        Schema::whenTableHasColumn('users', 'authentication_token', function (Blueprint $table) {
            $table->dropColumn('authentication_token');
        });

        Schema::whenTableHasColumn('users', 'authentication_token_generated_at', function (Blueprint $table) {
            $table->dropColumn('authentication_token_generated_at');
        });
    }
};
