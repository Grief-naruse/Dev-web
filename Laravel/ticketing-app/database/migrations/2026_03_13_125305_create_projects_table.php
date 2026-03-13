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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            
            // 🔗 Le lien avec le client (Clé étrangère)
            // Cette ligne dit : "Ce projet appartient à un client existant"
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            
            $table->string('name');
            $table->text('description')->nullable();
            
            // 💰 Le contrat (comme demandé dans ton TP)
            $table->decimal('included_hours', 8, 2)->default(0); // Ex: 50.00 heures
            $table->decimal('hourly_rate', 8, 2)->default(0);   // Ex: 75.00 €/h
            
            $table->enum('status', ['active', 'on_hold', 'completed'])->default('active');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
