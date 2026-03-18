<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute la migration pour créer la table.
     */
    public function up(): void
{
    Schema::create('clients', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique()->nullable(); // 👈 Ajoute cette ligne
        $table->timestamps();
    });
}

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};