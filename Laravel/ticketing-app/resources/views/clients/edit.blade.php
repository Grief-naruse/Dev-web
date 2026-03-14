@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px; max-width: 800px; margin: auto;">
    
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/clients') }}" style="color: #3498db; text-decoration: none; font-weight: bold;">← Annuler et retourner à la liste</a>
    </div>

    <div class="card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ecf0f1; padding-bottom: 15px; margin-bottom: 25px;">
            <h1 style="font-size: 1.8rem; color: #2c3e50; margin: 0;">
                Modifier la fiche : <span style="color: #3498db;">{{ $client->name }}</span>
            </h1>
            <span style="background-color: #ecf0f1; padding: 5px 10px; border-radius: 4px; font-size: 0.9rem; color: #7f8c8d; font-weight: bold;">
                ID: #{{ $client->id }}
            </span>
        </div>

        <form action="{{ url('/clients/' . $client->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Nom de l'entreprise *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                @error('name')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" style="width: 100%; background-color: #f39c12; color: white; padding: 12px 25px; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer;">
                Mettre à jour le client
            </button>
        </form>
    </div>
</div>
@endsection