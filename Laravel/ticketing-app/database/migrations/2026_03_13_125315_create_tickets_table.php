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
            $table->foreignId('author_id')->constrained('users'); // Qui a créé le ticket
            $table->foreignId('assigned_to')->nullable()->constrained('users'); // Qui s'en occupe
            
            $table->string('title');
            $table->text('description');
            
            // 🚦 Statuts demandés dans ton TP
            $table->enum('status', [
                'new', 'progress', 'pending_client', 'done', 'to_validate', 'validated', 'refused'
            ])->default('new');
            
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
