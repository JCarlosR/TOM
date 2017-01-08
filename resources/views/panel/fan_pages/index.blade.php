@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('/vendor/introjs/introjs.min.css') }}">
@endsection

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading"
                 data-step="3"
                 data-info="En esta sección se encuentran tus fanpages. Debes seleccionar una, y a continuación podrás registrar tu promoción.">
                Configuración
            </div>
            <div class="panel-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('warning') }}</p>
                    </div>
                @endif

                <p>
                    Selecciona la fanpage en la que quieres aplicar tu promoción con TomboFans.
                </p>
                @foreach ($fanPages as $fanPage)
                    <div class="well bs-component">
                        <a href="{{ url('config/page/'.$fanPage->id) }}" class="btn btn-primary pull-right">
                            Administrar
                        </a>
                        <h2>{{ $fanPage->name }}</h2>
                        <p>
                            <b>ID: </b>
                            {{ $fanPage->fan_page_id }}
                        </p>
                        <p>
                            <b>Categoría: </b>
                            {{ $fanPage->category }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/vendor/introjs/intro.min.js') }}"></script>
    <script src="{{ asset('/assets/config/step-by-step/config.js') }}"></script>
@endsection
