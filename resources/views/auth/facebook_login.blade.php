@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Iniciar sesión</div>
                <div class="panel-body">

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <a href="{{ $login_url }}" class="btn btn-primary">
                                <i class="fa fa-btn fa-sign-in"></i> Ingresar con facebook
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
