@extends('layouts.app')

@section('content')
    @include('includes.nav-top')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-info">
                    <div class="panel-heading">Navegación</div>
                    <div class="panel-body">
                        <a class="list-group-item" href="{{ url('/home') }}" id="step-1">Datos principales</a>
                        <a class="list-group-item" href="{{ url('/config') }}" id="step-2">Configurar promociones</a>
                    </div>
                </div>

            </div>

            @yield('dashboard_content')
        </div>
    </div>
@endsection
