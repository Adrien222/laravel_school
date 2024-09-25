@extends('layouts.layout')

@section('title', 'Liste des Outils')

@section('content')
    <h1>Liste des Outils</h1>
    
    <ul>
        @foreach($tools as $tool)
            <li>
                <a href="{{ url('/tools/' . $tool->id) }}">
                    <strong>{{ $tool->name }}</strong>
                </a>
                - Prix : {{ $tool->price }} €
            </li>
        @endforeach
    </ul>
@endsection
