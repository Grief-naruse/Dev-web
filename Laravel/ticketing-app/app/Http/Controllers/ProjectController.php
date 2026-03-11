<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        // Données avec les heures ajoutées
        $projects = [
            [
                'id' => 1, 
                'name' => 'noot 1', 
                'client' => 'noot 2', 
                'type' => 'Régie',
                'used' => 12,       // Ajout des heures utilisées
                'total' => 50       // Ajout des heures totales
            ],
            [
                'id' => 4, 
                'name' => 'qsdF', 
                'client' => 'dqsdd', 
                'type' => 'Forfait',
                'used' => 0,        // Ajout des heures utilisées
                'total' => 520      // Ajout des heures totales
            ]
        ];

        return view('projects.index', compact('projects'));
    }
}