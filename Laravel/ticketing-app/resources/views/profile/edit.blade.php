@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 1200px; margin: auto;">

    <div style="margin-bottom: 20px;">
        <h1 class="page-title">Mon Profil</h1>
        <p class="text-muted" style="margin-bottom: 0;">Gérez vos informations personnelles et les paramètres de sécurité.</p>
    </div>

    <div class="grid-2" style="align-items: start;">
        
        <div class="card" style="margin-bottom: 0;">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div style="display: flex; flex-direction: column; gap: 20px;">
            
            <div class="card" style="margin-bottom: 0;">
                @include('profile.partials.update-password-form')
            </div>

            <div class="card" style="border: 1px solid var(--danger-color); background-color: rgba(231, 76, 60, 0.05); margin-bottom: 0;">
                @include('profile.partials.delete-user-form')
            </div>

        </div>

    </div>

</div>
@endsection