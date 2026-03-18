<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // On génère un vrai faux nom d'entreprise
            'name' => fake()->company(),
            
            // ⚠️ ATTENTION : J'ai mis 'email' et 'phone' ici car ce sont les standards. 
            // Si ta migration "create_clients_table" n'a que la colonne 'name', 
            // supprime les lignes en dessous pour ne pas faire planter la base !
            //'email' => fake()->unique()->companyEmail(),
            //'phone' => fake()->phoneNumber(),
            //'address' => fake()->address(),
        ];
    }
}