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
            <h2>Bienvenido a Tombo Fans</h2>
            <p>Mucha suerte porque hoy puedes ganar ...</p>
        </header>
        <section class="wrapper style5">
            <div class="inner text-center">

                <h3>{{ $promotion->fanPage->name }}</h3>
                <p>{{ $promotion->description }}</p>

                <img src="{{ asset('/images/promotions/'.$promotion->image_path) }}" alt="TOM Promo" class="img-responsive">

                <hr />

                <div class="alert alert-info" id="alertLike" style="display: @if($pageIsLiked) none @else block @endif">
                    <a href="#" class="close" id="closeAlertLike">&times;</a>
                    <p>Recuerda dar like a la página para poder participar:</p>
                    <iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2F{{ $fanPageFbId }}&width=450&layout=button_count&action=like&size=small&show_faces=false&share=false&height=80&appId" width="105" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                </div>

                <div id="alertMessage"></div>
                <p>Solo da clic en el botón azul para participar.</p>

                {{-- This user has liked the page? --}}
                <button class="button facebook fit" id="btnGo" data-token="{{ csrf_token() }}" data-action="{{ url("/promotion/$promotion->id/go") }}">
                    Haz click para participar !
                </button>

                <p class="text-muted">Cantidad de veces que has participado en esta promoción: {{ $participationsCount }}</p>
            </div>
        </section>
    </article>

    <!-- Footer -->
    @include('includes.footer')
</div>
@endsection

@section('scripts')
    <script src="{{ asset('/promotion/show.js') }}"></script>
@endsection