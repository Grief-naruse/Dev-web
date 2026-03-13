@extends('layouts.app')

@section('content')
    <header style="margin-bottom: 20px;">
        <h1>👤 Mon Profil</h1>
    </header>

    <div class="card" style="max-width: 600px;">
        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px;">
            <div style="width: 80px; height: 80px; background: var(--accent-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold;">
                {{ substr($user['name'], 0, 1) }}
            </div>
            <div>
                <h2 style="margin: 0;">{{ $user['name'] }}</h2>
                <span class="badge">{{ $user['role'] }}</span>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-color); padding-top: 20px;">
            <p><strong>📧 Email :</strong> {{ $user['email'] }}</p>
            <p><strong>📅 Membre depuis :</strong> {{ $user['created_at'] }}</p>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 10px;">
            <a href="{{ url('/settings') }}" class="btn">Modifier mes préférences</a>
        </div>
    </div>
@endsection