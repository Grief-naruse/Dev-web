<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Sème les données dans la base.
     */
    public function run(): void
    {
        // 👑 1. Ton compte Administrateur
        \App\Models\User::factory()->create([
            'name' => 'Ilan Rubaud',
            'email' => 'ilrub2005@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 🧑‍💻 2. Générer 5 Collaborateurs
        \App\Models\User::factory(5)->create([
            'role' => 'collaborator',
        ]);

        // 🏢 3. Création des Entreprises (Clients) et de leurs Utilisateurs
        // On crée 3 entreprises factices
        $entreprises = \App\Models\Client::factory(3)->create();

        // Pour chaque entreprise, on crée 2 comptes utilisateurs "clients" qu'on rattache à l'entreprise
        foreach ($entreprises as $entreprise) {
            \App\Models\User::factory(2)->create([
                'role' => 'client',
                'client_id' => $entreprise->id, // ✨ LA FAMEUSE LIAISON
            ]);
        }
    }
}