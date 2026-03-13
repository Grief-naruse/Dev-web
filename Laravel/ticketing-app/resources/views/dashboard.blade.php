@extends('layouts.app')

@section('content')
    <div class="dashboard-header">
        <h1>Tableau de Bord</h1>
        <p class="text-muted">Résumé visuel de l'activité de {{ $user['name'] ?? 'Ilan' }}.</p>
    </div>

    <div class="grid-3" style="margin-top: 20px;">
        <div class="card">
            <h3 style="color: #7f8c8d; font-size: 0.9rem;">PROJETS ACTIFS</h3>
            <p style="font-size: 2.5rem; font-weight: bold;">{{ $stats['total_projects'] }}</p>
        </div>
        <div class="card">
            <h3 style="color: #7f8c8d; font-size: 0.9rem;">TICKETS OUVERTS</h3>
            <p style="font-size: 2.5rem; font-weight: bold; color: var(--accent-color);">{{ $stats['active_tickets'] }}</p>
        </div>
        <div class="card">
            <h3 style="color: #7f8c8d; font-size: 0.9rem;">HEURES À FACTURER</h3>
            <p style="font-size: 2.5rem; font-weight: bold; color: var(--success-color);">{{ $stats['pending_hours'] }}h</p>
        </div>
    </div>

    <div class="grid-2" style="margin-top: 30px;">
        <div class="card">
            <h3>Répartition de la charge</h3>
            <div style="height: 300px;">
                <canvas id="myChart"></canvas>
            </div>
        </div>
        
        <div class="card">
            <h3>Dernières activités</h3>
            <ul style="list-style: none; padding-top: 10px;">
                <li style="padding: 10px 0; border-bottom: 1px solid #eee;">✅ Projet "ESIEA" mis à jour</li>
                <li style="padding: 10px 0; border-bottom: 1px solid #eee;">🎫 Nouveau ticket : "Bug Login"</li>
                <li style="padding: 10px 0;">⏱️ 2.5h ajoutées sur "Ticketing App"</li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Projets', 'Tickets', 'Heures (x10)', 'Tâches'],
                datasets: [{
                    label: 'Statistiques actuelles',
                    data: [
                        {{ $stats['total_projects'] }}, 
                        {{ $stats['active_tickets'] }}, 
                        {{ $stats['pending_hours'] }}, 
                        {{ $stats['completed_tasks'] }}
                    ],
                    backgroundColor: [
                        '#3498db',
                        '#e74c3c',
                        '#27ae60',
                        '#f1c40f'
                    ],
                    borderWidth: 0,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
@endsection