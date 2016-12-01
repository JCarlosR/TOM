@extends('layouts.home')

@section('dashboard_content')
    <div class="col-md-10">
            <div class="panel panel-info">
                <div class="panel-heading">Administrar fan page</div>

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

                    <p>Información de la página seleccionada.</p>
                        <div class="well bs-component">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{ $fanPage->picture_200 }}" alt="Picture - Fan page">
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

                                <div class="col-md-6">
                                    <h3>Opciones</h3>
                                    {{--<a href="{{ url('config/page/'.$fanPage->id.'/picture') }}" class="btn btn-sm btn-primary btn-block">--}}
                                        {{--Actualizar foto--}}
                                    {{--</a>--}}
                                    
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
@endsection
