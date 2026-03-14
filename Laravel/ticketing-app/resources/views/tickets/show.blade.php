@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    
    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 5px solid #28a745;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="{{ url('/tickets') }}" style="color: #3498db; text-decoration: none; font-weight: bold;">← Retour aux tickets</a>
        <div>
            <a href="{{ url('/tickets/' . $ticket->id . '/edit') }}" style="background-color: #f39c12; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-right: 10px;">✏️ Modifier</a>
        </div>
    </div>

    <div class="card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1 style="font-size: 2rem; color: #2c3e50; margin: 0 0 10px 0;">{{ $ticket->title }}</h1>
                <p style="color: #7f8c8d; font-size: 1.1rem; margin: 0;">
                    Projet : 
                    @if($ticket->project)
                        <a href="{{ url('/projects/' . $ticket->project_id) }}" style="color: #34495e; font-weight: bold; text-decoration: none;">📁 {{ $ticket->project->name }}</a>
                    @else
                        <span style="color: #e74c3c;">⚠️ Projet supprimé</span>
                    @endif
                </p>
            </div>
            <div style="text-align: right; display: flex; gap: 10px;">
                <span style="display: inline-block; padding: 8px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9rem; background-color: #f2f4f4; color: #2c3e50;">
                    🎯 {{ str_replace('_', ' ', strtoupper($ticket->status)) }}
                </span>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #ecf0f1; margin: 20px 0;">

        @php
            $totalHours = $ticket->timeEntries->sum('duration');
            $estimated = $ticket->estimated_hours > 0 ? $ticket->estimated_hours : 1; // Evite la division par zéro
            $progressPercentage = min(($totalHours / $estimated) * 100, 100);
        @endphp

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <div>
                <h3 style="color: #34495e; margin-top: 0;">Description détaillée</h3>
                <div style="background-color: #f9f9f9; padding: 20px; border-radius: 6px; border: 1px solid #ecf0f1; min-height: 150px; white-space: pre-wrap;">{{ $ticket->description ?: 'Aucune description fournie.' }}</div>
            </div>

            <div>
                <h3 style="color: #34495e; margin-top: 0;">Consommation du budget</h3>
                <div style="background-color: #f9f9f9; padding: 20px; border-radius: 6px; border: 1px solid #ecf0f1;">
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #7f8c8d; font-weight: bold;">Temps passé :</span>
                        <span style="color: {{ $totalHours > $ticket->estimated_hours ? '#e74c3c' : '#27ae60' }}; font-weight: bold; font-size: 1.2rem;">
                            {{ $totalHours }}h / {{ $ticket->estimated_hours }}h
                        </span>
                    </div>

                    <div style="background-color: #ecf0f1; border-radius: 10px; height: 10px; width: 100%; overflow: hidden; margin-bottom: 20px;">
                        <div style="background-color: {{ $totalHours > $ticket->estimated_hours ? '#e74c3c' : '#3498db' }}; width: {{ $progressPercentage }}%; height: 100%;"></div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #ecf0f1; margin: 15px 0;">
                    
                    <div>
                        <span style="color: #7f8c8d; font-size: 0.9rem; display: block;">Type de facturation</span>
                        <span style="color: #2c3e50; font-weight: bold;">{{ $ticket->type === 'included' ? '📦 Inclus dans le forfait' : '💸 Hors forfait' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        
        <div class="card" style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="color: #2c3e50; margin-top: 0; margin-bottom: 20px;">⏱️ Ajouter du temps</h3>
            
            <form action="{{ route('time-entries.store') }}" method="POST">
                @csrf
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                <div style="margin-bottom: 15px;">
                    <label for="date" style="display: block; font-weight: bold; margin-bottom: 5px; color: #34495e;">Date de l'intervention *</label>
                    <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                    @error('date') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="duration" style="display: block; font-weight: bold; margin-bottom: 5px; color: #34495e;">Durée (en heures) *</label>
                    <input type="number" step="0.1" min="0.1" max="24" name="duration" id="duration" value="{{ old('duration') }}" placeholder="Ex: 1.5 pour 1h30" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                    @error('duration') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="description" style="display: block; font-weight: bold; margin-bottom: 5px; color: #34495e;">Ce qui a été fait *</label>
                    <textarea name="description" id="description" rows="3" placeholder="Recherche de bug, correction..." style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">{{ old('description') }}</textarea>
                    @error('description') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <button type="submit" style="background-color: #27ae60; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%;">
                    Enregistrer le temps
                </button>
            </form>
        </div>

        <div class="card" style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="color: #2c3e50; margin-top: 0; margin-bottom: 20px;">📋 Historique des interventions</h3>
            
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background-color: #ecf0f1; color: #2c3e50;">
                    <tr>
                        <th style="padding: 10px;">Date</th>
                        <th style="padding: 10px;">Durée</th>
                        <th style="padding: 10px;">Description</th>
                        <th style="padding: 10px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ticket->timeEntries()->orderByDesc('date')->get() as $entry)
                    <tr style="border-bottom: 1px solid #ecf0f1;">
                        <td style="padding: 10px; font-weight: bold; color: #34495e;">{{ $entry->date->format('d/m/Y') }}</td>
                        <td style="padding: 10px; color: #27ae60; font-weight: bold;">{{ $entry->duration }}h</td>
                        <td style="padding: 10px; color: #7f8c8d; font-size: 0.95rem;">{{ $entry->description }}</td>
                        <td style="padding: 10px; text-align: right;">
                            <form action="{{ route('time-entries.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('Supprimer cette saisie ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 1.2rem;" title="Supprimer">×</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 20px; text-align: center; color: #bdc3c7;">Aucun temps n'a encore été saisi sur ce ticket.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection