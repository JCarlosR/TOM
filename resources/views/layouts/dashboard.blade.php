@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="panel panel-info">
                <div class="panel-heading">Navegación</div>
                <div class="panel-body">
                    <a class="list-group-item" href="{{ url('/home') }}">Datos</a>
                    <a class="list-group-item" href="{{ url('/config') }}">Configuración</a>
                </div>
            </div>

        </div>

        @yield('dashboard_content')
    </div>
</div>
@endsection
