@extends('layouts.layout')

@section('title', 'Détails de l\'Outil')

@section('content')
    <h1>{{ $tool->name }}</h1>
    <p>Description : {{ $tool->description }}</p>
@endsection
