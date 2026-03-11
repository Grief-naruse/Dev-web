<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = [
            ['id' => 4, 'title' => 'qsfCQSsf', 'project_name' => 'qsdF', 'type' => 'Inclus', 'status' => 'Nouveau'],
            ['id' => 1, 'title' => 'Erreur 500 au paiement', 'project_name' => 'noot 1', 'type' => 'Inclus', 'status' => 'Nouveau']
        ];
        return view('tickets.index', compact('tickets'));
    }
}