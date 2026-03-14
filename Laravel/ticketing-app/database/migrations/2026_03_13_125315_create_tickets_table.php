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
            
            // 🔗 Liaisons (Clés étrangères)
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            
            // ⚠️ Rendus 'nullable' temporairement en attendant l'installation de l'Authentification
            $table->foreignId('author_id')->nullable()->constrained('users'); 
            $table->foreignId('assigned_to')->nullable()->constrained('users'); 
            
            $table->string('title');
            $table->text('description')->nullable(); // Sécurisé : accepte désormais les champs vides
            
            // 🚦 Statuts standardisés avec notre interface et le FormRequest
            $table->enum('status', [
                'todo', 'in_progress', 'in_review', 'completed'
            ])->default('todo');
            
            // ⚡ Priorité et Type
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('type', ['included', 'billable'])->default('included');
            
            $table->decimal('estimated_hours', 8, 2)->default(0);
            
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