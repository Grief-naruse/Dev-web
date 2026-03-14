@extends('layouts.app') @section('content') <div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success border-left-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Portefeuille Clients</h1>
        <a href="{{ route('clients.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> + Nouveau Client
        </a>
    </div>

    <div class="card shadow mb-4 border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-top-0 px-4">ID</th>
                            <th class="border-top-0">Nom de l'entreprise</th>
                            <th class="border-top-0 text-center">Projets</th>
                            <th class="border-top-0 text-right px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td class="px-4 text-muted">#{{ $client->id }}</td>
                            <td class="font-weight-bold">{{ $client->name }}</td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-info px-3">
                                    {{ $client->projects_count }} projet(s)
                                </span>
                            </td>
                            <td class="text-right px-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-primary mr-2">Voir</a>
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-warning mr-2">Éditer</a>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection