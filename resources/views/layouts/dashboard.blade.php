@extends('layouts.app')

@section('content')
    @include('includes.nav-top')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <div class="panel panel-info">
                    <div class="panel-heading">Navegación</div>
                    <div class="panel-body">
                        <a class="list-group-item" href="{{ url('/home') }}" id="step-1">
                            <span class="glyphicon glyphicon-home"></span>
                            Datos principales
                        </a>
                        <a class="list-group-item" href="{{ url('/config') }}" id="step-2">
                            <span class="glyphicon glyphicon-cog"></span>
                            Configurar promociones
                        </a>
                        @if (auth()->user()->is_admin || env('APP_DEBUG'))
                            <a class="list-group-item" href="{{ url('/facebook/posts') }}" id="step-2">
                                <span class="fa fa-facebook-official"></span>
                                Publicaciones Club <img src="/images/logos/club-momy.png" alt="Logo Club Momy" height="26">
                            </a>
                        @endif
                        <a class="list-group-item" href="{{ url('/clientes') }}" id="step-3">
                            <span class="fa fa-users"></span>
                            Clientes potenciales
                        </a>
                        <a class="list-group-item" href="{{ url('/tutorial') }}">
                            <span class="glyphicon glyphicon-book"></span>
                            Tutoriales
                        </a>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        Gana <span class="glyphicon glyphicon-usd"></span>
                        afiliando
                    </div>
                    <div class="panel-body">
                        <a class="list-group-item" href="{{ url('/referrals/how-to') }}">
                            <span class="glyphicon glyphicon-paperclip"></span>
                            Cómo obtener referidos
                        </a>
                        <a class="list-group-item" href="{{ url('/referrals') }}">
                            <span class="glyphicon glyphicon-stats"></span>
                            Usuarios referidos
                        </a>
                        <a class="list-group-item" href="{{ url('/earnings') }}">
                            <span class="glyphicon glyphicon-fire"></span>
                            Mis ganancias
                        </a>
                    </div>
                </div>

                @if (auth()->user()->is_admin)
                    <div class="panel panel-info">
                        <div class="panel-heading">Administrador</div>
                        <div class="panel-body">
                            <a class="list-group-item" href="{{ url('/admin/creators') }}">
                                <span class="glyphicon glyphicon-user"></span>
                                Usuarios creadores
                            </a>
                            <a class="list-group-item" href="{{ url('/admin/referrers') }}">
                                <span class="glyphicon glyphicon-user"></span>
                                Usuarios reclutadores
                            </a>
                        </div>
                    </div>
                @endif

            </div>

            @yield('dashboard_content')
        </div>
    </div>
@endsection

@section('track-fb')
    fbq('track', 'Lead');
@endsection
