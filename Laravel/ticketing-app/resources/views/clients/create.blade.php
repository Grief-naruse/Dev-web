@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px; max-width: 800px; margin: auto;">
    
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/clients') }}" style="color: #3498db; text-decoration: none; font-weight: bold;">← Retour au portefeuille</a>
    </div>

    <div class="card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h1 style="font-size: 1.8rem; color: #2c3e50; margin-bottom: 25px; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;">
            Ajouter un nouveau Client
        </h1>

        <form action="{{ url('/clients') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Nom de l'entreprise (ou du client) *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Ex: Acme Corp" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                @error('name')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" style="background-color: #3498db; color: white; padding: 12px 25px; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer; width: 100%;">
                Enregistrer le client
            </button>
        </form>
    </div>
</div>
@endsection