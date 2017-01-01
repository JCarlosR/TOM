@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <style>
        @font-face {
            font-family: "PassionOne";
            src: url("../fonts/PassionOne-Regular.otf");
        }
        @font-face {
            font-family: "PassionOne-Bold";
            src: url("../fonts/PassionOne-Bold.otf");
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
            {{--<img src="{{ asset('/images/participation/welcome.jpg') }}" alt="Welcome" class="img-responsive">--}}
        </header>
        <section class="wrapper style5">
            <div class="inner text-center">

                <div id="promoData">
                    <h3>{{ $promotion->fanPage->name }}</h3>
                    <p id="pDescription">{{ $promotion->description }}</p>
                    <img id="imgPromo" src="{{ asset('/images/promotions/'.$promotion->image_path) }}" alt="TOM Promo" class="img-responsive">
                    <hr />
                </div>

                {{-- If the user still has not like the page, show this alert from the beginning --}}
                <div class="alert alert-info" id="alertLike" style="display: @if($pageIsLiked) none @else block @endif">
                    <a href="#" class="close" id="closeAlertLike">&times;</a>
                    <p>Recuerda dar like a la página para poder participar:</p>
                    <iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2F{{ $fanPageFbId }}&width=450&layout=button_count&action=like&size=small&show_faces=false&share=false&height=80&appId" width="105" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                </div>

                <div id="alertMessage"></div>
                <img id="imgWon" src="{{ asset('images/participation/won.jpg') }}" alt="Ganaste" class="img-responsive" style="display: none;">
                <img id="imgLost" src="{{ asset('images/participation/lost.jpg') }}" alt="Sigue intentando" class="img-responsive" style="display: none;">
                <button type="button" id="btnShare" class="btn btn-success" data-id="{{ $promotion->id }}" style="display: none;">
                    Compartir en Facebook
                </button>

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
    <script src="{{ asset('/assets/promotion/show-share.js') }}"></script>
@endsection