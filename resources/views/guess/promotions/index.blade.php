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
            max-height: 6em;
            overflow-y: auto;
        }
        .panel .panel-footer {
            max-height: 6em;
            overflow-y: auto;
        }

        .img-responsive {
            margin: 0 auto;
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

                <div class="row">
                @foreach ($promotions as $promotion)
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    {{ $promotion->fanPage->name }}
                                </h3>
                                <p>{{ $promotion->fanPage->category }}</p>
                            </div>
                            <div class="panel-body">
                                <img class="img-responsive" src="{{ asset('images/promotions/'.$promotion->image_path) }}" alt="{{ $promotion->description }}">
                                <p>{{ $promotion->description }}</p>
                            </div>
                            <div class="panel-footer">
                                <a href="//facebook.com/{{ $promotion->fanPage->fan_page_id }}" title="Visitar fan page" target="_blank">
                                    <span class="fa fa-facebook"></span>
                                </a>
                                <a href="{{ url('/facebook/promotion/'.$promotion->id) }}" title="Ir a la promoción" class="pull-right" target="_blank">
                                    <span class="fa fa-flash"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </section>
    </article>

    <!-- Footer -->
    @include('includes.footer')
</div>
@endsection
