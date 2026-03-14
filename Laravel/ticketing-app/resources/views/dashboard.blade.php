@extends('layouts.app')

@section('content')

<style>
    .dashboard-header { margin-bottom: 30px; }
    .dashboard-header h1 { color: #2c3e50; font-size: 2.2rem; margin: 0 0 5px 0; }
    .text-muted { color: #7f8c8d; font-size: 1.1rem; margin: 0; }
    
    .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
    
    .card { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    
    :root { --accent-color: #e67e22; --success-color: #27ae60; }
    
    @media (max-width: 768px) { .grid-2 { grid-template-columns: 1fr; } }
</style>

<div class="container-fluid" style="padding: 20px;">

    <div class="dashboard-header">
        <h1>Tableau de Bord</h1>
        <p class="text-muted">Résumé visuel de l'activité de {{ Auth::user()->name }}.</p>
    </div>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 5px solid #28a745;">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid-3" style="margin-top: 20px;">
        <div class="card">
            <h3 style="color: #7f8c8d; font-size: 0.9rem; margin-top: 0;">PROJETS ACTIFS</h3>
            <p style="font-size: 2.5rem; font-weight: bold; margin: 10px 0 0 0;">{{ $stats['total_projects'] }}</p>
        </div>
        <div class="card">
            <h3 style="color: #7f8c8d; font-size: 0.9rem; margin-top: 0;">TICKETS OUVERTS</h3>
            <p style="font-size: 2.5rem; font-weight: bold; color: var(--accent-color); margin: 10px 0 0 0;">{{ $stats['active_tickets'] }}</p>
        </div>
        <div class="card">
            <h3 style="color: #7f8c8d; font-size: 0.9rem; margin-top: 0;">HEURES TOTALES SAISIES</h3>
            <p style="font-size: 2.5rem; font-weight: bold; color: var(--success-color); margin: 10px 0 0 0;">{{ $stats['pending_hours'] }}h</p>
        </div>
    </div>

    <div class="grid-2" style="margin-top: 30px;">
        
        <div class="card">
            <h3 style="color: #34495e; margin-top: 0; margin-bottom: 20px;">Répartition de la charge</h3>
            <div style="height: 300px; position: relative;">
                <canvas id="myChart"></canvas>
            </div>
        </div>
        
        <div class="card">
            <h3 style="color: #34495e; margin-top: 0; margin-bottom: 15px;">Dernières activités</h3>
            <div class="card">
            <h3 style="color: #34495e; margin-top: 0; margin-bottom: 15px;">Dernières activités</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                
                @forelse($recentActivities as $activity)
                <li style="padding: 12px 0; border-bottom: 1px solid #ecf0f1; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="margin-right: 8px;">
                            @if($activity->status === 'completed') ✅ 
                            @elseif($activity->priority === 'urgent') 🔴 
                            @else 🎫 
                            @endif
                        </span>
                        
                        <a href="{{ url('/tickets/' . $activity->id) }}" style="color: #2c3e50; font-weight: bold; text-decoration: none;">
                            {{ $activity->title }}
                        </a>
                        
                        <span style="color: #7f8c8d; font-size: 0.85rem; margin-left: 5px;">
                            ({{ $activity->project ? $activity->project->name : 'Projet orphelin' }})
                        </span>
                    </div>
                    
                    <span style="color: #bdc3c7; font-size: 0.85rem; font-weight: bold;">
                        {{ $activity->updated_at->diffForHumans() }}
                    </span>
                </li>
                @empty
                <li style="padding: 20px 0; color: #7f8c8d; text-align: center; font-style: italic;">
                    Aucune activité récente sur la plateforme.
                </li>
                @endforelse
                
            </ul>
        </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Projets', 'Tickets Ouverts', 'Heures Saisies', 'Tickets Terminés'],
            datasets: [{
                label: 'Nos statistiques clés',
                // Injection dynamique des données Laravel dans le Javascript !
                data: [
                    {{ $stats['total_projects'] }}, 
                    {{ $stats['active_tickets'] }}, 
                    {{ $stats['pending_hours'] }}, 
                    {{ $stats['completed_tasks'] }}
                ],
                backgroundColor: [
                    '#3498db',
                    '#e67e22',
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