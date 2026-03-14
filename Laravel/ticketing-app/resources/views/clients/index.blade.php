@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    
    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 5px solid #28a745;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="color: #2c3e50; margin: 0; font-size: 2rem;">Portefeuille Clients</h1>
        <a href="{{ url('/clients/create') }}" style="background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; box-shadow: 0 2px 4px rgba(52, 152, 219, 0.3);">
            + Nouveau Client
        </a>
    </div>

    <div style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: #2c3e50; color: white;">
                <tr>
                    <th style="padding: 15px; width: 10%;">ID</th>
                    <th style="padding: 15px; width: 40%;">Nom de l'entreprise</th>
                    <th style="padding: 15px; width: 20%;">Projets</th>
                    <th style="padding: 15px; width: 30%; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr style="border-bottom: 1px solid #ecf0f1; transition: background-color 0.2s;">
                    <td style="padding: 15px; color: #7f8c8d; font-weight: bold;">#{{ $client->id }}</td>
                    
                    <td style="padding: 15px; font-weight: bold; color: #2c3e50; font-size: 1.1rem;">
                        🏢 {{ $client->name }}
                    </td>
                    
                    <td style="padding: 15px; color: #7f8c8d; font-weight: bold;">
                        📁 {{ $client->projects ? $client->projects->count() : 0 }} projet(s)
                    </td>
                    
                    <td style="padding: 15px; text-align: right;">
                        <a href="{{ url('/clients/' . $client->id) }}" style="color: #3498db; text-decoration: none; margin-right: 15px; font-weight: bold;">Voir</a>
                        <a href="{{ url('/clients/' . $client->id . '/edit') }}" style="color: #f39c12; text-decoration: none; margin-right: 15px; font-weight: bold;">Éditer</a>
                        
                        <form action="{{ url('/clients/' . $client->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce client et tous ses projets ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-weight: bold; padding: 0;">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 40px; text-align: center; color: #7f8c8d; font-size: 1.1rem;">
                        <div style="font-size: 3rem; margin-bottom: 10px;">🏢</div>
                        Votre portefeuille client est vide pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection