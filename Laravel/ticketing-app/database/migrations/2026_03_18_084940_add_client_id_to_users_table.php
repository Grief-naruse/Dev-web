<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Enterprise Ready : On lie l'utilisateur à la table 'clients'.
            // Si l'entreprise est supprimée, on met l'ID à null (nullOnDelete) plutôt que de supprimer le compte utilisateur.
            $table->foreignId('client_id')->nullable()->after('role')->constrained('clients')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }
};