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

        <div class="col-md-10">
            <div class="panel panel-info">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('message'))
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <p>{{ session('message') }}</p>
                        </div>
                    @endif

                    <p>Bienvenido {{ auth()->user()->name }} !</p>
                    <div class="well bs-component">
                        <form class="form-horizontal">
                            <fieldset>
                                <legend>Datos principales</legend>
                                <div class="form-group">
                                    <label for="inputEmail" class="col-lg-2 control-label">E-mail</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="inputEmail" value="{{ auth()->user()->email }}" readonly>
                                        <span class="help-block">Usaremos este e-mail solo para enviarle notificaciones importantes.</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="textArea" class="col-lg-2 control-label">Ciudad</label>
                                    <div class="col-lg-10">
                                        <textarea class="form-control" id="textArea" rows="2"readonly>{{ auth()->user()->location_name }}</textarea>
                                        <span class="help-block">Todos sus datos son totalmente privados.</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="select" class="col-lg-2 control-label">Fan pages</label>
                                    <div class="col-lg-10">
                                        <select multiple="" class="form-control" readonly>
                                            @foreach ($fanPages as $fanPage)
                                            <option value="{{ $fanPage->id }}">{{ $fanPage->name }} ({{ $fanPage->category }})</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block">Estas son las páginas que usted administra en facebook.</span>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
