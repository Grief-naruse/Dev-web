<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter la migration.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // On ajoute une colonne 'avatar' de type string (qui contiendra le nom du fichier)
            // Elle peut être vide (nullable) et on la place après l'email pour l'organisation.
            $table->string('avatar')->nullable()->after('email');
        });
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // En cas de retour en arrière, on supprime la colonne
            $table->dropColumn('avatar');
        });
    }
};