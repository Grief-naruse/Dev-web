@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 400px; margin: 50px auto;">
    <h2>Inscription</h2>
    <form action="{{ url('/') }}" method="GET">
        <div style="margin-bottom: 15px;">
            <label>Nom complet</label>
            <input type="text" style="width: 100%; padding: 8px;" required>
        </div>
        <div style="margin-bottom: 15px;">
            <label>Email</label>
            <input type="email" style="width: 100%; padding: 8px;" required>
        </div>
        <div style="margin-bottom: 15px;">
            <label>Mot de passe</label>
            <input type="password" style="width: 100%; padding: 8px;" required>
        </div>
        <button type="submit" class="btn" style="width: 100%; background-color: #2ecc71;">S'inscrire</button>
    </form>
</div>
@endsection