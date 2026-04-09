@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="padding: 25px; max-width: 1200px; margin: auto;">

        @if(session('success'))
            <div
                style="background-color: #e9f7ef; color: #27ae60; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;">
                ✓ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div
                style="background-color: #fdedec; color: #e74c3c; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;">
                ❌ {{ session('error') }}
            </div>
        @endif

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h1 style="font-size: 1.8rem; color: #2c3e50; margin: 0; font-weight: 800;">Équipe & Accès Clients</h1>
            <a href="{{ route('users.create') }}"
                style="background-color: #3498db; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">
                + Nouvel Accès
            </a>
        </div>

        <div class="card"
            style="background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #ecf0f1;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background-color: #f8f9fa; border-bottom: 2px solid #ecf0f1;">
                    <tr>
                        <th style="padding: 15px; color: #34495e;">Utilisateur</th>
                        <th style="padding: 15px; color: #34495e;">Contact</th>
                        <th style="padding: 15px; color: #34495e;">Rôle / Accès</th>
                        <th style="padding: 15px; color: #34495e; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr style="border-bottom: 1px solid #ecf0f1; transition: 0.2s;"
                            onmouseover="this.style.backgroundColor='#fdfdfd';"
                            onmouseout="this.style.backgroundColor='transparent';">
                            <td style="padding: 15px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div
                                        style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; background: #2c3e50; display: flex; align-items: center; justify-content: center; border: 2px solid #edf2f7;">
                                        @if($u->avatar)
                                            <img src="{{ asset('storage/avatars/' . $u->avatar) }}"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <span style="color: white; font-weight: bold;">{{ substr($u->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <strong style="color: #2c3e50; font-size: 1.05rem;">{{ $u->name }}</strong>
                                </div>
                            </td>
                            <td style="padding: 15px; color: #7f8c8d; font-size: 0.95rem;">
                                {{ $u->email }}
                            </td>
                            <td style="padding: 15px;">
                                @if($u->role === 'admin')
                                    <span
                                        style="background-color: #fef9e7; color: #f39c12; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">👑
                                        Administrateur (Manager)</span>
                                @elseif($u->role === 'collaborator')
                                    <span
                                        style="background-color: #e8f8f5; color: #27ae60; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">🧑‍💻
                                        Collaborateur (Équipe)</span>
                                @else
                                    <span
                                        style="background-color: #ebf5fb; color: #3498db; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">🏢
                                        Client</span>
                                    <div style="font-size: 0.8rem; color: #7f8c8d; margin-top: 5px; font-weight: bold;">
                                        ↳ {{ $u->clientEnterprise->name ?? 'Erreur: Sans entreprise' }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 15px;">
                                    <a href="{{ route('users.show', $u) }}"
                                        style="color: #3498db; text-decoration: none; font-weight: bold; font-size: 0.9rem;">Détails</a>
                                    <a href="{{ route('users.edit', $u) }}"
                                        style="color: #f39c12; text-decoration: none; font-weight: bold; font-size: 0.9rem;">Modifier</a>
                                    @if(Auth::id() !== $u->id)
                                        <form action="{{ route('users.destroy', $u) }}" method="POST"
                                            onsubmit="return confirm('Supprimer cet accès ?');" style="margin: 0;">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                style="background: none; border: none; color: #e74c3c; font-weight: bold; cursor: pointer; padding: 0; font-size: 0.9rem;">Supprimer</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection