@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('/vendor/introjs/introjs.min.css') }}">
@endsection

@section('content')
    @include('includes.nav-top')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">Guía de inicio rápido</div>

                    <div class="panel-body text-center">
                        <p>Bienvenido {{ auth()->user()->name }} !</p>
                        <iframe width="480" height="360" src="//www.youtube.com/embed/daMaXaGANBU?vq=hd720&autoplay=1" frameborder="0" allowfullscreen></iframe>

                        <div class="form-group">
                            <a href="{{ url('/tutorial/disable') }}" class="btn btn-default">
                                <span class="glyphicon glyphicon-check"></span>
                                No volver a mostrar el tutorial
                            </a>
                            <a href="{{ url('/home') }}" class="btn btn-primary">
                                <span class="glyphicon glyphicon-console"></span>
                                Continuar al panel de usuario
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
