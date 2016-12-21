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

                {{-- If the user still has not like the page, show this alert from the beginning --}}
                <div class="alert alert-info" id="alertLike" style="display: @if($pageIsLiked) none @else block @endif">
                    <a href="#" class="close" id="closeAlertLike">&times;</a>
                    <p>Recuerda dar like a la página para poder participar:</p>
                    <iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2F{{ $fanPageFbId }}&width=450&layout=button_count&action=like&size=small&show_faces=false&share=false&height=80&appId" width="105" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                </div>

                <div id="alertMessage"></div>

                <p id="pBackLink" style="display: none">
                    <a href="https://fb.com/{{ $fanPageFbId }}">Haz clic aquí para volver a la fanpage.</a>
                </p>

                <p id="pInstructions">Solo da clic en el botón azul para participar.</p>
                <button class="button facebook fit" id="btnGo" data-token="{{ csrf_token() }}" data-action="{{ url("/promotion/$promotion->id/go") }}">
                    Haz click para participar !
                </button>

                <p class="text-muted" id="pCount">Cantidad de veces que has participado en esta promoción: {{ $participationsCount }}</p>
            </div>
        </section>
    </article>

    <!-- Footer -->
    @include('includes.footer')
</div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/promotion/show.js') }}"></script>
@endsection