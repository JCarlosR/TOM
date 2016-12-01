@extends('layouts.home')

@section('dashboard_content')
    <div class="col-md-10">
            <div class="panel panel-info">
                <div class="panel-heading">Configuración</div>

                <div class="panel-body">
                    @if (session('message'))
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <p>{{ session('message') }}</p>
                        </div>
                    @endif

                    <p>Seleccione la página que desea administrar.</p>
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
