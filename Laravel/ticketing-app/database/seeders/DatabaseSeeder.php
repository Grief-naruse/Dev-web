<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TimeEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création de l'Administrateur (Toi pour les tests)
        $admin = User::create([
            'name'     => 'Administrateur Système',
            'email'    => 'admin@ticket.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // 2. Création d'un Client (L'entreprise)
        $client = Client::create(['name' => 'ESN Solutions Corp']);

        // 3. Création de Collaborateurs
        $dev = User::create([
            'name'     => 'Jean Développeur',
            'email'    => 'dev@ticket.com',
            'password' => Hash::make('password'),
            'role'     => 'collaborator',
        ]);

        // 4. Création d'un Projet avec budget
        $project = Project::create([
            'client_id'      => $client->id,
            'name'           => 'Refonte Application Mobile',
            'description'    => 'Migration vers une architecture micro-services.',
            'included_hours' => 100.00,
            'hourly_rate'    => 85.00,
            'status'         => 'active',
        ]);

        // On lie le développeur au projet via la table pivot
        $project->users()->attach($dev->id);

        // 5. Création de Tickets avec différents statuts et types
        $ticket1 = Ticket::create([
            'project_id'      => $project->id,
            'author_id'       => $admin->id,
            'assigned_to'     => $dev->id,
            'title'           => 'Bug critique sur l\'authentification',
            'description'     => 'Les tokens JWT expirent trop tôt.',
            'status'          => 'progress',
            'priority'        => 'urgent',
            'type'            => 'included',
            'estimated_hours' => 10.00,
        ]);

        // 6. Ajout de temps passé pour tester les calculs automatiques du Modèle
        TimeEntry::create([
            'ticket_id' => $ticket1->id,
            'user_id'   => $dev->id,
            'duration'  => 4.5,
            'work_date' => now()->subDay(),
            'comment'   => 'Analyse des logs et correction du middleware.',
        ]);
    }
}