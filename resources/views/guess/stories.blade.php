@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <style>
        .img-responsive {
            padding-bottom: 2em;
        }
    </style>
@endsection

@section('content')
<div id="page-wrapper">

    <!-- Header -->
    <header id="header">
        <h1><a href="{{ url('/') }}">Tombo Fans</a></h1>
    </header>

    <!-- Main -->
    <article id="main">
        <header>
            <h2>Bienvenido a Tombo Fans</h2>
            <p>Casos de éxito</p>
        </header>
        <section class="container">
            <img src="{{ asset('/images/stories/7.jpg') }}" alt="Casos de éxito - Fernanda" class="img-responsive img-rounded">
            <img src="{{ asset('/images/stories/8.jpg') }}" alt="Casos de éxito - Nadia" class="img-responsive img-rounded">
            <img src="{{ asset('/images/stories/9.jpg') }}" alt="Casos de éxito - Romina" class="img-responsive img-rounded">
            <img src="{{ asset('/images/stories/10.jpg') }}" alt="Casos de éxito - Karina" class="img-responsive img-rounded">
        </section>
    </article>

    <!-- Footer -->
    @include('includes.footer')
</div>
@endsection
