@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px; max-width: 900px; margin: auto;">

    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('users.index') }}" style="color: #7f8c8d; text-decoration: none; font-weight: bold;">← Retour aux accès</a>
        <a href="{{ route('users.edit', $user) }}" style="background-color: #f39c12; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">
            Modifier l'utilisateur
        </a>
    </div>

    <div style="background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 30px; margin-bottom: 30px; display: flex; gap: 25px; align-items: center; border: 1px solid #ecf0f1;">
        <div style="width: 100px; height: 100px; border-radius: 50%; background-color: #2c3e50; color: white; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold; overflow: hidden; border: 4px solid #edf2f7; flex-shrink: 0;">
            @if($user->avatar)
                <img src="{{ asset('storage/avatars/' . $user->avatar) }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                {{ substr($user->name, 0, 1) }}
            @endif
        </div>
        <div>
            <h1 style="font-size: 2rem; color: #2c3e50; margin: 0 0 5px 0; font-weight: 800;">{{ $user->name }}</h1>
            <p style="color: #7f8c8d; font-size: 1.1rem; margin: 0 0 15px 0;">📧 {{ $user->email }}</p>
            
            <div style="display: flex; gap: 10px;">
                @if($user->role === 'admin')
                    <span style="background-color: #fef9e7; color: #f39c12; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9rem;">👑 Administrateur</span>
                @elseif($user->role === 'collaborator')
                    <span style="background-color: #e8f8f5; color: #27ae60; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9rem;">🧑‍💻 Collaborateur ERP</span>
                @else
                    <span style="background-color: #ebf5fb; color: #3498db; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9rem;">🏢 Compte Client</span>
                @endif
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
        
        @if($user->isClient())
            <div style="background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #ecf0f1; border-left: 4px solid #3498db;">
                <h3 style="color: #2c3e50; margin-top: 0; font-size: 1.2rem;">🏢 Entreprise rattachée</h3>
                @if($user->clientEnterprise)
                    <p style="font-size: 1.1rem; color: #34495e;"><strong>{{ $user->clientEnterprise->name }}</strong></p>
                    <a href="{{ route('clients.show', $user->clientEnterprise) }}" style="color: #3498db; text-decoration: none; font-weight: bold;">→ Voir le dossier de l'entreprise</a>
                @else
                    <p style="color: #e74c3c; font-style: italic;">Aucune entreprise liée. Veuillez corriger ce compte.</p>
                @endif
            </div>
        @endif

        @if($user->isCollaborator())
            <div style="background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #ecf0f1; border-left: 4px solid #27ae60;">
                <h3 style="color: #2c3e50; margin-top: 0; font-size: 1.2rem;">📈 Activité du collaborateur</h3>
                <p style="color: #7f8c8d; line-height: 1.6;">
                    Cet utilisateur est habilité à intervenir sur les projets techniques, enregistrer ses heures et répondre aux clients.
                </p>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #f1f5f9;">
                    <a href="{{ route('tickets.index') }}" style="color: #27ae60; text-decoration: none; font-weight: bold;">→ Consulter le panneau des tickets</a>
                </div>
            </div>
        @endif

        <div style="background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #ecf0f1;">
            <h3 style="color: #2c3e50; margin-top: 0; font-size: 1.2rem;">🛡️ Historique Sécurité</h3>
            <p style="color: #7f8c8d; margin-bottom: 5px;"><strong>Compte créé le :</strong> {{ $user->created_at->format('d/m/Y à H:i') }}</p>
            <p style="color: #7f8c8d; margin-bottom: 0;"><strong>Dernière modification :</strong> {{ $user->updated_at->diffForHumans() }}</p>
        </div>
    </div>
</div>
@endsection