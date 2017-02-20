@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <style>
        .panel .panel-footer {
            background: #2c3e50;
        }

        .panel .panel-footer p {
            margin: 0;
            padding: 0;
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
                <div class="row">
                @foreach ($promotions as $promotion)
                    <div class="col-md-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">{{ $promotion->description }}</h3>
                            </div>
                            <div class="panel-body">
                                <img class="img-responsive" src="{{ asset('images/promotions/'.$promotion->image_path) }}" alt="{{ $promotion->description }}">
                            </div>
                            <div class="panel-footer">
                                <p>{{ $promotion->fanPage->name }}</p>
                                <p>{{ $promotion->fanPage->category }}</p>
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
