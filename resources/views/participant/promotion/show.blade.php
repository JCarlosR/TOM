@extends('layouts.app')

@section('og-image')
    <meta property="og:image" content="{{ asset('/images/promotions/'.$promotion->image_path) }}" />
@overwrite

@section('styles')
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <style>
        @font-face {
            font-family: "PassionOne";
            src: url("{{ asset('/fonts/PassionOne-Regular.otf') }}");
        }
        @font-face {
            font-family: "PassionOne-Bold";
            src: url("{{ asset('/fonts/PassionOne-Bold.otf') }}");
            font-weight: bold;
        }
        .img-responsive {
            margin: 0 auto;
        }
        #header-welcome {
            font-family: "PassionOne", serif;
            padding: 4em 0 1em 0 !important;
        }
        #header-welcome p {
            color: white;
            font-size: 1.6em;
            margin: 1em 0;
        }
        #header-welcome p.first-bold {
            font-size: 2em;
            margin-bottom: 1.4em;
        }
        #header-welcome p.title {
            font-size: 4.5em;
            font-family: "PassionOne-Bold", serif;
            max-width: 100%;
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
<div id="page-wrapper">

    <!-- Header -->
    <header id="header">
        <h1><a href="#">Tombo Fans</a></h1>
    </header>

    <!-- Main -->
    <article id="main-welcome">
        <header id="header-welcome">
            <p class="first-bold">Bienvenid@ a tu</p>
            <p class="title">TomboFans</p>
            <p>mucha suerte, porque hoy puedes ganar</p>
            <img src="{{ asset('/images/participation/hand.png') }}" alt="Hand" height="85">
        </header>
        <section class="wrapper style5">
            <div class="inner text-center">

                <div id="promoData">
                    <h3 id="titleFanPage">{{ $fanPageName }}</h3>
                    <p id="pDescription">{{ $promotion->description }}</p>
                    @if ($promotion->end_date)
                        <p><b>Fecha de vencimiento:</b> {{ $promotion->end_date }}</p>
                    @endif
                    <p class="text-muted">{{ $promotion->city }}</p>

                    <img id="imgPromo" src="{{ asset('/images/promotions/'.$promotion->image_path) }}" alt="TOM Promo" class="img-responsive">
                    <hr />
                </div>

                <div class="panel panel-default" id="participantData">
                    <div class="panel-body">
                        <p class="">Hola {{ $participantName }}.</p>
                        <img src="{{ $participantPicture }}" alt="{{ $participantName }}" class="img-responsive">
                    </div>
                </div>

                {{-- If the user has not liked the page, suggests --}}
                <div class="alert alert-info" id="alertLike">
                    <a href="#" class="close" id="closeAlertLike">&times;</a>
                    <p>Te recomendamos dar like a nuestra fanpage para estar al tanto de nuestras novedades!</p>
                    <iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2F{{ $fanPageFbId }}&width=450&layout=button_count&action=like&size=small&show_faces=false&share=false&height=80&appId" width="105" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                </div>

                <div id="alertMessage"></div>

                <p id="pInstructions">Solo da clic en el botón azul para participar.</p>
                <button class="button facebook fit" id="btnGo" data-token="{{ csrf_token() }}" data-action="{{ url("/promotion/$promotion->id/go") }}" data-location="{{ $fanPageFbId }}">
                    <span class="icon fa-facebook"></span> Clic para participar y compartir !
                </button>
                <p class="text-muted" id="pCount">Cantidad de veces que has participado en esta promoción: {{ $participationsCount }}</p>

                <img id="imgWon" src="{{ asset('images/participation/won.jpg') }}" alt="Ganaste" class="img-responsive" style="display: none;">
                <img id="imgLost" src="{{ asset('images/participation/lost.jpg') }}" alt="Sigue intentando" class="img-responsive" style="display: none;">


                <button type="button" id="btnShare" class="button small" data-id="{{ $promotion->id }}" style="display: none; margin-top: 1em;">
                    Compartir en <span class="icon fa-facebook"></span>
                </button>

                <p id="pBackLink" style="display: none">
                    <a href="https://facebook.com/{{ $fanPageFbId }}">Clic aquí para volver a la fanpage.</a>
                </p>
                <p id="pLogout" style="display: none;"> {{-- It is always hidden (Miguel wants to remove it) --}}
                    <em class="small">Si esta no es tu cuenta, <a href="{{ url("/facebook/promotion/$promotion->id") }}">haz clic aquí para cerrar sesión</a> e ingresar con tu facebook.</em>
                </p>
            </div>
        </section>
    </article>

</div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/promotion/show.js') }}"></script>
    <script src="{{ asset('/assets/promotion/show-share.js') }}"></script>
@endsection