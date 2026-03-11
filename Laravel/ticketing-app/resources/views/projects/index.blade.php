@extends('layouts.app')

@section('content')
    <header>
        <h1>Projets Client</h1>
    </header>

    <div class="grid-2">
        @foreach($projects as $project)
            <article class="card">
                <h3>{{ $project['name'] }}</h3>
                <p>Client : <strong>{{ $project['client'] }}</strong></p>
                <hr>
                <p>Heures : {{ $project['used'] }}h / {{ $project['total'] }}h</p>
                <a href="#" class="btn">Détails</a>
            </article>
        @endforeach
    </div>
@endsection