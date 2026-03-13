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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            
            // 🔗 La clé étrangère : relie ce ticket à un projet existant
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['new', 'progress', 'done', 'refused'])->default('new');
            $table->enum('priority', ['Basse', 'Moyenne', 'Haute', 'Urgente'])->default('Moyenne');
            
            // ⏱️ Colonnes pour la gestion du temps (Étape 6)
            $table->decimal('estimated_hours', 8, 2)->default(0);
            $table->decimal('spent_hours', 8, 2)->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
