@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <style>
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
    </style>
@endsection

@section('content')
<div id="page-wrapper">
    <header id="header">
        <h1><a href="#">Tombo Fans</a></h1>
    </header>

    <article id="main">

        <section class="wrapper style1">
            <div class="inner">
                {{-- Fix, because header is floating --}}
                <br class="visible-xs">

                <div class="btn-group hidden-xs">
                    <button class="btn btn-primary btn-sm" data-filter="All">
                        All
                    </button>
                    @foreach ($categories as $category)
                        <button class="btn btn-primary btn-sm" data-filter="{{ $category->name }}">
                            {{ $category->name }} ({{ $category->count }})
                        </button>
                    @endforeach
                </div>

                <form action="">
                    <select id="select-filter" class="visible-xs">
                        <option value="All">Todas las categorías</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->name }}">
                                {{ $category->name }} ({{ $category->count }})
                            </option>
                        @endforeach
                    </select>
                </form>

                <div class="row" style="margin-top: 1em;">
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
                                    <img class="img-responsive" src="{{ asset('images/promotions/'.$promotion['imagePath']) }}" alt="{{ $promotion['description'] }}">
                                </div>
                                <div class="panel-content-description">
                                    <p>{{ $promotion['description'] }}</p>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <a href="//facebook.com/{{ $promotion['fanPageId'] }}" title="Visitar fan page" target="_blank">
                                    <span class="fa fa-facebook"></span>
                                </a>
                                <a href="{{ url('/facebook/promotion/'.$promotion['id']) }}" title="Ir a la promoción" class="pull-right" target="_blank">
                                    <span class="fa fa-flash"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>

                {{ $promotions->links() }}
            </div>
        </section>
    </article>

    <!-- Footer -->
    @include('includes.footer')
</div>
@endsection

@section('scripts')
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
