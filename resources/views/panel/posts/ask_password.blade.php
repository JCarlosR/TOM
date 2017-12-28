@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-lock"></i>
                Verifica tu acceso para la realización de publicaciones en <i class="fa fa-facebook-square"></i>
            </div>

            <div class="panel-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p><strong>Bienvenida al Grupo exclusivo de Momypreneurs</strong></p>

                <p>Por favor ingresa la contraseña para acceder a la programación de publicaciones:</p>

                <form action="" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="password" name="pass" class="form-control" placeholder="Contraseña de ingreso" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Validar contraseña <i class="fa fa-key"></i>
                    </button>
                </form>


                <hr>

                <p class="small">Esta contraseña sólo será solicitada una vez. TOM no te volverá a preguntar luego que haya confirmado la contraseña,</p>
            </div>
        </div>
    </div>
@endsection
