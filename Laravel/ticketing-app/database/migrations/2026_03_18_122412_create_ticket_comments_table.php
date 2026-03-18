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
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->id();
            
            // On relie le commentaire au Ticket (si on supprime le ticket, ça supprime les commentaires liés)
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            
            // On relie le commentaire à l'Auteur (Admin, Collab ou Client)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Le texte du message
            $table->text('content');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_comments');
    }
};
