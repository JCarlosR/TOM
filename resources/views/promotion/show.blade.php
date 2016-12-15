@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <style>
        .img-responsive {
            margin: 0 auto;
        }
    </style>
@endsection

@section('content')
<!-- Page Wrapper -->
<div id="page-wrapper">

    <!-- Header -->
    <header id="header">
        <h1><a href="#">Tombo Fans</a></h1>
    </header>

    <!-- Main -->
    <article id="main">
        <header>
            <h2>Tombo Fans</h2>
            <p>Participa y gana (Cambiar por texto fijo)</p>
        </header>
        <section class="wrapper style5">
            <div class="inner text-center">

                <h3>{{ $promotion->fanPage->name }}</h3>
                <p>{{ $promotion->description }}</p>

                <img src="{{ asset('/images/promotions/'.$promotion->image_path) }}" alt="TOM Promo" class="img-responsive">

                <hr />

                {{-- This user has liked the page? --}}
                <button class="button facebook fit">
                    Haz click para participar !
                </button>

                <p class="small">Ya has participado 3 veces en este sorteo !</p>
            </div>
        </section>
    </article>

    <!-- Footer -->
    @include('includes.footer')
</div>
@endsection
