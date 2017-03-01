@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Paypal</div>
            <div class="panel-body">
                @if (session('notification'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('notification') }}</p>
                    </div>
                @endif

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('earnings/paypal') }}" method="POST">
                    <div class="form-group">
                        <label for="paypal_account">Por favor ingresa a continuación tu cuenta de Paypal:</label>
                        <input type="email" name="paypal_account" id="paypal_account"
                               placeholder="Paypal account"
                               value="{{ old('paypal_account', auth()->user()->paypal_account) }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar cuenta de Paypal</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Mis ganancias</div>
            <div class="panel-body">
                <div class="text-right">
                    <a href="#" class="btn btn-success btn-sm">
                        <span class="glyphicon glyphicon-export"></span>
                        Exportar a Excel
                    </a>
                </div>
                <p>
                    Desde esta página podrás ver un resumen de tus ganancias por obtención de referidos.
                </p>
                <p>
                    <strong>Nota:</strong>
                    Estamos trabajando para mostrarte estadísticas más detalladas.
                </p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
