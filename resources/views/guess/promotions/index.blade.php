@extends('layouts.app')

@section('og-image')
    <meta property="og:image" content="https://res.cloudinary.com/tombofans/image/upload/v1514136374/club-momy-banner-3.jpg">
@endsection

@section('styles')
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <style>
        .wrapper.style1 {
            background-color: #9E69AD;
        }

        .panel .panel-footer {
            background: #2c3e50;
        }
        .panel p {
            margin: 0;
            padding: 0;
        }
        .panel .panel-body p {
            color: #000;
        }
        .panel .panel-heading {
            height: 5em;
            overflow-y: auto;
        }
        .panel .panel-footer {
            max-height: 6em;
            overflow-y: auto;
        }

        .img-responsive {
            margin: 0 auto;
        }

        .panel .panel-content-image {
            height: 10em;
        }
        .panel .panel-content-image img {
            max-height: 100%;
            margin: 0 auto;
        }

        .panel .panel-content-description {
            height: 7em;
            position: relative;
        }
        .panel .panel-content-description p {
            text-align: center;
            position: absolute;
            top: 50%;
            left:50%;
            max-height: 100%;
            width: 100%;
            overflow: auto;
            transform: translate(-50%,-50%);
            /*border: 1px dashed deeppink;*/
        }

        #ulCategories {
            list-style-type: none;
        }
        #ulCategories li {
            margin-bottom: 1em;
        }
        
        #header h1 {
            position: initial;
        }

        .btn.special.btn-block {
            white-space: normal;
            background: #9E69AD;
        }
    </style>
@endsection

@section('content')
<div id="page-wrapper">
    <header id="header" class="text-center">
        <h1>
            <a href="{{ url('/clubmomy/cuponera') }}">Cuponera Club Momy</a>
        </h1>
    </header>

    <article id="main">

        <section class="wrapper style2">
            <div class="container-fluid">
                {{-- Fix, because header is floating --}}
                <br class="visible-xs">

                <img src="https://res.cloudinary.com/tombofans/image/upload/v1514136374/club-momy-banner-3.jpg" alt="Club Momy" class="img-responsive">
                
                <form action="" class="form-horizontal" method="GET">
                    <div class="input-group">
                        <input type="text" name="query" class="form-control" placeholder="¿Qué estás buscando?"
                               value="{{ $query }}">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-sm">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </div>
                    </div>
                </form>

                <section>
                    @if (session('notification'))
                    <div class="alert alert-danger alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('notification') }}</p>
                    </div>
                    @endif
                    <div class="row uniform">
                        <div class="col-md-6">
                            <h2>¿Cómo funciona la cuponera?</h2>
                            <ol>
                                <li>Da clic en la imagen del producto o servicio que te interesó.</li>
                                <li>Sigue las instrucciones de la promoción o el cupón de descuento.</li>
                                <li>Recibes un mail con los datos de contacto del anunciante.</li>
                                <li>Haz valida tu promoción o cupón de descuento.</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            {{--<a href="{{ $loginUrl }}" class="btn special btn-block btn-small">--}}
                            {{--Promueve tu negocio aquí--}}
                            {{--</a>--}}
                            <p>Da clic para más información:</p>
                            <a href="{{ $loginUrl }}" class="btn special btn-block btn-small">
                                ¿Eres mamá y quieres anunciar tus productos o servicios?
                            </a>
                            <a href="https://m.me/ClubMomy" class="btn special btn-block btn-small" target="_blank">
                                ¿Quieres anunciar productos o servicios para mamás?
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </section>

        <section class="wrapper style1">
            <div class="container-fluid">

                <div class="col-md-3 hidden-xs">
                    <ul id="ulCategories">
                        <li data-filter="All">
                            <a>Todas las categorías</a>
                        </li>
                        @foreach ($categories as $category)
                            <li data-filter="{{ $category->en }}">
                                <a>{{ $category->es }} ({{ $category->count }})</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <form action="" class="visible-xs">
                    <div class="form-group">
                        <select id="select-filter">
                            <option value="All">Todas las categorías</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->en }}">
                                    {{ $category->es }}
                                    {{--({{ $category->count }})--}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="col-md-9 col-xs-12">
                    <div class="row">
                        @foreach ($promotions as $promotion)
                            <div class="col-md-4 col-sm-6 col-xs-12" data-status="{{ $promotion['fanPageCategory'] }}">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            {{ $promotion['fanPageName'] }}
                                        </h3>
                                        <p>{{ $promotion['fanPageCategory'] }}</p>
                                    </div>
                                    <div class="panel-body">
                                        <div class="panel-content-image">
                                            <a href="{{ url('/facebook/promotion/'.$promotion['id']) }}" title="Ir a la promoción" class="pull-right" target="_blank">
                                                <img class="img-responsive" src="{{ asset('images/promotions/'.$promotion['imagePath']) }}" alt="{{ $promotion['description'] }}">
                                            </a>
                                        </div>
                                        <div class="panel-content-description">
                                            <p>{{ $promotion['descriptionShort'] }}</p>
                                        </div>

                                        <p class="text-center small">
                                            @if ($promotion['participationsCount'] == 0)
                                                Sé el primero en participar
                                            @elseif ($promotion['participationsCount'] == 1)
                                                1 persona participó
                                            @else
                                                {{ $promotion['participationsCount'] }} personas participaron
                                            @endif
                                        </p>
                                    </div>
                                    <div class="panel-footer">
                                        <p class="text-center small" style="margin-bottom: 0.5em;">
                                            <a href="//facebook.com/{{ $promotion['fanPageId'] }}" title="Visitar fan page" target="_blank">
                                                Visitar fanpage
                                            </a>
                                            <iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2F{{ $promotion['fanPageId'] }}&width=450&layout=button_count&action=like&size=small&show_faces=false&share=false&height=80&appId" width="105" height="20" style="pborder:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                                        </p>

                                        <p class="text-center">
                                            <a href="https://twitter.com/intent/tweet?text={{ $promotion['description'] }}&url={{ url('/facebook/promotion/'.$promotion['id']) }}" rel="nofollow" target="_blank" title="Compartir en Twitter" class="btn btn-success btn-sm">
                                                <i class="fa fa-twitter"></i>
                                            </a> &nbsp;
                                            <a href="https://facebook.com/sharer.php?u={{ url('/facebook/promotion/'.$promotion['id']) }}" rel="nofollow" target="_blank" title="Compartir en Facebook" class="btn btn-success btn-sm">
                                                <i class="fa fa-facebook"></i>
                                            </a> &nbsp;
                                            <a href="https://plus.google.com/share?url={{ url('/facebook/promotion/'.$promotion['id']) }}" rel="nofollow" target="_blank" title="Compartir en Google+" class="btn btn-success btn-sm">
                                                <i class="fa fa-google-plus"></i>
                                            </a> &nbsp;
                                            <a href="mailto:?subject=Mira esta promoción&amp;body=Ingresa a {{ url('/facebook/promotion/'.$promotion['id']) }}, y mira lo que puedes ganar: {{ $promotion['description'] }}"
                                               title="Compartir vía mail" class="btn btn-success btn-sm">
                                                <i class="fa fa-envelope"></i>
                                            </a> &nbsp;
                                            <a href="whatsapp://send?text=Ingresa a {{ url('/facebook/promotion/'.$promotion['id']) }}, y mira lo que puedes ganar: {{ $promotion['description'] }}" data-action="share/whatsapp/share" class="btn btn-success btn-sm">
                                                <i class="fa fa-whatsapp"></i>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        {{ $promotions->links() }}
                    </div>
                </div>
            </div>
        </section>
    </article>

    <!-- Footer -->
    @include('includes.footer')
</div>
@endsection

@section('scripts')
    {{-- CLIENGO Chat --}}
    <script>(function(){var ldk=document.createElement('script'); ldk.type='text/javascript'; ldk.async=true; ldk.src='https://s.cliengo.com/weboptimizer/58cc915ae4b07d521ea6895f/58c72ab9e4b0d88d06cfb695.js' ; var s=document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ldk, s);})();</script>

    {{-- ManyChat --}}
    <script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = “//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.11&appId=532160876956612”;fjs.parentNode.insertBefore(js, fjs);}(document, ‘script’, ‘facebook­jssdk’));</script>
    <script src="//widget.manychat.com/1567109470249042.js" async="async"></script>

    <script>
        $('[data-filter]').on('click', function () {
            var target = $(this).data('filter');
            applyFilter(target);
        });

        $('#select-filter').on('change', function () {
            var target = $(this).val();
            applyFilter(target);
        });

        function applyFilter(target) {
            if (target != 'All') {
                $('[data-status]').css('display', 'none');
                $('[data-status="' + target + '"]').fadeIn('slow');
            } else {
                $('[data-status]').css('display', 'none').fadeIn('slow');
            }
        }
    </script>
@endsection
