<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EnterpriseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création du Client (L'entreprise qui paye)
        $client = Client::create([
            'name' => 'CyberDyne Systems',
            'email' => 'contact@cyberdyne.com',
        ]);

        // 2. Création de ton compte Admin (Le patron)
        $admin = User::create([
            'name' => 'Ilan Rubaud',
            'email' => 'ilan@erp.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 3. Création des Collaborateurs (La Dream Team)
        $collabs = collect([
            ['name' => 'Marc Tech', 'email' => 'marc@erp.com'],
            ['name' => 'Sophie Dev', 'email' => 'sophie@erp.com'],
            ['name' => 'Julie Design', 'email' => 'julie@erp.com'],
        ])->map(function ($data) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'collaborator',
            ]);
        });

        // 4. Création d'un Projet rattaché au Client
        $project = Project::create([
            'client_id' => $client->id,
            'name' => 'Refonte Infrastructure Cloud',
            'description' => 'Migration totale des serveurs vers AWS.',
            'status' => 'active', // 👈 ON CHANGE "in_progress" PAR "active" ICI
        ]);

        // 5. 🔗 LIAISON : On attache toute l'équipe au Projet (Table project_user)
        // C'est grâce à ça que ton menu AJAX affichera ces noms !
        $project->users()->attach($collabs->pluck('id'));

        // 6. Création d'un Ticket de test
        $ticket = Ticket::create([
            'project_id' => $project->id,
            'author_id' => $admin->id,
            'title' => 'Bug critique : Latence API',
            'description' => 'Les temps de réponse dépassent les 2 secondes sur la prod.',
            'status' => 'todo',
            'priority' => 'urgent',
            'type' => 'included',
            'estimated_hours' => 15,
        ]);

        // 7. 🔗 LIAISON : On assigne Marc et Sophie sur ce ticket (Table ticket_user)
        $ticket->assignees()->attach([$collabs[0]->id, $collabs[1]->id]);

        $this->command->info('✅ Environnement Enterprise Ready déployé avec succès !');
    }
}