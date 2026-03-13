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
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            
            // 🔗 Liaisons
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Qui a travaillé ?
            
            // ⏱️ Détails du temps passé
            $table->decimal('duration', 8, 2); // Durée en heures (ex: 1.5 pour 1h30)
            $table->date('work_date'); // Le jour où le travail a été fait
            $table->text('comment')->nullable(); // Ce qui a été fait (optionnel)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};
