<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            
            // 🔗 Le temps est obligatoirement lié à un ticket
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            
            // 👤 Qui a travaillé ? (Nullable temporairement sans l'authentification)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // ⏱️ Les données du travail
            $table->date('date'); // Le jour de l'intervention
            $table->decimal('duration', 5, 2); // Ex: 1.5 pour 1h30 (jusqu'à 999.99h)
            $table->text('description'); // Ce qui a été fait (obligatoire pour la facturation)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};