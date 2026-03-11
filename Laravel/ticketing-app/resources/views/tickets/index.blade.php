@extends('layouts.app')
@section('content')
    <h1>Tous les tickets</h1>
    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Projet</th>
                    <th>Type</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $t)
                <tr>
                    <td>#{{ $t['id'] }}</td>
                    <td>{{ $t['title'] }}</td>
                    <td>{{ $t['project_name'] }}</td>
                    <td><span class="badge">{{ $t['type'] }}</span></td>
                    <td><span class="badge">{{ $t['status'] }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection