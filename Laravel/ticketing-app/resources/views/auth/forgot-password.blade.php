@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 400px; margin: 50px auto;">
    <h2>Récupération</h2>
    <p>Saisissez votre email pour recevoir un lien de réinitialisation.</p>
    <form action="{{ url('/login') }}" method="GET">
        <div style="margin-bottom: 15px;">
            <label>Email</label>
            <input type="email" style="width: 100%; padding: 8px;" required>
        </div>
        <button type="submit" class="btn" style="width: 100%; background-color: #e67e22;">Envoyer le lien</button>
    </form>
</div>
@endsection