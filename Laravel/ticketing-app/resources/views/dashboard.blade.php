@extends('layouts.app')

@section('content')
    <div class="container-fluid"
        style="padding: 25px; max-width: 1400px; margin: auto; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 1.8rem; color: #2c3e50; font-weight: 800; margin: 0;">Tableau de Bord</h1>
            <p style="color: #7f8c8d; margin-top: 5px;">Bienvenue, {{ Auth::user()->name }}. Voici l'état actuel de votre
                infrastructure.</p>
        </div>

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">

            <div
                style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #edf2f7;">
                <div
                    style="color: #7f8c8d; font-size: 0.85rem; font-weight: bold; text-transform: uppercase; margin-bottom: 10px;">
                    Projets Actifs</div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 2rem; font-weight: 800; color: #2c3e50;">{{ $stats['total_projects'] }}</span>
                    <span style="background: #ebf8ff; color: #3182ce; padding: 8px; border-radius: 8px;">📁</span>
                </div>
            </div>

            <div
                style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #edf2f7;">
                <div
                    style="color: #7f8c8d; font-size: 0.85rem; font-weight: bold; text-transform: uppercase; margin-bottom: 10px;">
                    Tickets Ouverts</div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 2rem; font-weight: 800; color: #e53e3e;">{{ $stats['active_tickets'] }}</span>
                    <span style="background: #fff5f5; color: #e53e3e; padding: 8px; border-radius: 8px;">🎫</span>
                </div>
            </div>

            <div
                style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #edf2f7;">
                <div
                    style="color: #7f8c8d; font-size: 0.85rem; font-weight: bold; text-transform: uppercase; margin-bottom: 10px;">
                    Temps Facturé (Total)</div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span
                        style="font-size: 2rem; font-weight: 800; color: #38a169;">{{ number_format($stats['total_hours'], 1) }}h</span>
                    <span style="background: #f0fff4; color: #38a169; padding: 8px; border-radius: 8px;">⏱️</span>
                </div>
            </div>

            <div
                style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #edf2f7;">
                <div
                    style="color: #7f8c8d; font-size: 0.85rem; font-weight: bold; text-transform: uppercase; margin-bottom: 10px;">
                    Taux de résolution</div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 2rem; font-weight: 800; color: #2c3e50;">{{ $stats['completion_rate'] }}%</span>
                    <div style="width: 60px; height: 8px; background: #edf2f7; border-radius: 4px; overflow: hidden;">
                        <div style="width: {{ $stats['completion_rate'] }}%; height: 100%; background: #3182ce;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">

            <div
                style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #edf2f7; overflow: hidden;">
                <div
                    style="padding: 20px; border-bottom: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 1.1rem; color: #2c3e50;">Activités Récentes</h3>
                    <a href="{{ route('tickets.index') }}"
                        style="font-size: 0.85rem; color: #3182ce; text-decoration: none; font-weight: bold;">Voir tout</a>
                </div>
                <div style="padding: 0;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; table-layout: fixed;">
                        <thead>
                            <tr style="background: #f7fafc; border-bottom: 2px solid #edf2f7;">
                                <th
                                    style="padding: 12px 20px; font-size: 0.75rem; color: #7f8c8d; text-transform: uppercase; width: 22%;">
                                    Client / Projet</th>
                                <th
                                    style="padding: 12px 20px; font-size: 0.75rem; color: #7f8c8d; text-transform: uppercase; width: 38%;">
                                    Ticket & Priorité</th>
                                <th
                                    style="padding: 12px 20px; font-size: 0.75rem; color: #7f8c8d; text-transform: uppercase; width: 15%;">
                                    Équipe</th>
                                <th
                                    style="padding: 12px 20px; font-size: 0.75rem; color: #7f8c8d; text-transform: uppercase; width: 13%;">
                                    Budget</th>
                                <th
                                    style="padding: 12px 20px; font-size: 0.75rem; color: #7f8c8d; text-transform: uppercase; text-align: right; width: 12%;">
                                    Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities as $ticket)
                                <tr style="border-bottom: 1px solid #f7fafc; transition: background 0.2s;"
                                    onmouseover="this.style.backgroundColor='#fcfcfc'"
                                    onmouseout="this.style.backgroundColor='transparent'">

                                    <td style="padding: 15px 20px; vertical-align: top;">
                                        <div style="font-weight: bold; color: #2c3e50; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                            title="{{ $ticket->project?->client?->name ?? 'Client interne' }}">
                                            {{ $ticket->project?->client?->name ?? 'Client interne' }}
                                        </div>
                                        <div style="font-size: 0.8rem; color: #3182ce; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                            title="{{ $ticket->project->name ?? 'N/A' }}">
                                            📁 {{ $ticket->project->name ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <td style="padding: 15px 20px; vertical-align: top;">
                                        <div style="display: flex; flex-direction: column; gap: 6px;">
                                            <a href="{{ route('tickets.show', $ticket) }}"
                                                style="font-weight: bold; color: #2d3748; font-size: 0.95rem; text-decoration: none; line-height: 1.3;">
                                                {{ $ticket->title }}
                                            </a>
                                            <div style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem;">
                                                {!! $ticket->priority_label !!}
                                                <span style="color: #cbd5e0;">•</span>
                                                <span style="color: #a0aec0;">MAJ
                                                    {{ $ticket->updated_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td style="padding: 15px 20px; vertical-align: top;">
                                        <div style="display: flex; gap: -5px;">
                                            @forelse($ticket->assignees->take(3) as $assignee)
                                                <div title="{{ $assignee->name }}"
                                                    style="width: 28px; height: 28px; border-radius: 50%; border: 2px solid white; margin-left: -10px; overflow: hidden; background: #2c3e50; display: flex; align-items: center; justify-content: center;">
                                                    @if($assignee->avatar)
                                                        <img src="{{ asset('storage/avatars/' . $assignee->avatar) }}"
                                                            alt="{{ $assignee->name }}"
                                                            style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <span
                                                            style="color: white; font-size: 0.65rem; font-weight: bold;">{{ substr($assignee->name, 0, 1) }}</span>
                                                    @endif
                                                </div>
                                            @empty
                                                <span style="font-size: 0.8rem; color: #cbd5e0; font-style: italic;">Libre</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <td style="padding: 15px 20px; vertical-align: top;">
                                        @php
                                            $consumed = $ticket->timeEntries->sum('duration');
                                            $percent = $ticket->estimated_hours > 0 ? min(($consumed / $ticket->estimated_hours) * 100, 100) : 0;
                                            $barColor = $consumed > $ticket->estimated_hours ? '#e53e3e' : '#38a169';
                                        @endphp
                                        <div style="font-size: 0.8rem; color: #4a5568; margin-bottom: 4px;">
                                            <strong>{{ $consumed }}h</strong> / {{ $ticket->estimated_hours }}h
                                        </div>
                                        <div
                                            style="width: 100%; height: 5px; background: #edf2f7; border-radius: 3px; overflow: hidden;">
                                            <div style="width: {{ $percent }}%; height: 100%; background: {{ $barColor }};">
                                            </div>
                                        </div>
                                    </td>

                                    <td style="padding: 15px 20px; text-align: right; vertical-align: top;">
                                        {!! $ticket->status_badge !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div style="background: #fffaf0; border-radius: 12px; border: 1px solid #feebc8; padding: 20px;">
                    <h3 style="margin: 0 0 15px 0; font-size: 1rem; color: #7b341e;">🔥 Projets Critiques</h3>
                    @forelse($criticalProjects as $proj)
                        <div
                            style="background: white; padding: 12px; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid #e53e3e; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div style="font-weight: bold; font-size: 0.9rem; color: #2d3748;">{{ $proj->name }}</div>
                            <div style="font-size: 0.8rem; color: #e53e3e; font-weight: bold;">{{ $proj->tickets_count }}
                                tickets urgents</div>
                        </div>
                    @empty
                        <p style="font-size: 0.85rem; color: #7f8c8d; font-style: italic;">Aucune urgence détectée.</p>
                    @endforelse
                </div>
                <div style="background: white; border-radius: 12px; border: 1px solid #edf2f7; padding: 20px;">
                    <h3 style="margin: 0 0 15px 0; font-size: 1rem; color: #2c3e50;">⚡ Actions Rapides</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <a href="{{ route('tickets.create') }}"
                            style="text-align: center; background: #3182ce; color: white; padding: 10px; border-radius: 8px; font-size: 0.8rem; font-weight: bold; text-decoration: none;">+
                            Ticket</a>
                        <a href="{{ route('projects.create') }}"
                            style="text-align: center; background: #edf2f7; color: #2c3e50; padding: 10px; border-radius: 8px; font-size: 0.8rem; font-weight: bold; text-decoration: none;">+
                            Projet</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection