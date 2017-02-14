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
                        <a class="list-group-item" href="{{ url('/referrals') }}" id="step-2">
                            <span class="glyphicon glyphicon-stats"></span>
                            Mis referidos
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
                        </div>
                    </div>
                @endif

            </div>

            @yield('dashboard_content')
        </div>
    </div>
@endsection
