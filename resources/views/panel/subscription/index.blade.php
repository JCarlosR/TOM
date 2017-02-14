@extends('layouts.app')

@section('content')
    @include('includes.nav-top')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">Suscripción a TomboFans</div>
                    <div class="panel-body text-center">

                        <p class="text-muted">Bienvenido a la sección de suscripciones, {{ auth()->user()->name }}.</p>
                        <p>Por favor seleccione la opción de su preferencia:</p>

                        <h3>Suscripción ilimitada</h3>
                        <div class="form-group">
                            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6GPWRE6HARWUU" class="btn btn-info">
                                <span class="fa fa-btn fa-paypal"></span>
                                Tarjeta de débito o crédito
                            </a>
                            <a href="https://compropago.com/comprobante/?id=e490a587-edec-44fe-81df-09208c309a55" class="btn btn-success">
                                <span class="fa fa-btn fa-usd"></span>
                                Efectivo
                            </a>
                        </div>


                        <h3>Suscripción ilimitada con convenio <em>(ClubMomy y grupos de facebook)</em></h3>
                        <div class="form-group">
                            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R4ZA62ASSQJZE" class="btn btn-info">
                                <span class="fa fa-btn fa-paypal"></span>
                                Tarjeta de débito o crédito
                            </a>
                            <a href="https://compropago.com/comprobante/?id=b2954834-3979-4e5f-90e7-736b112347e1" class="btn btn-success">
                                <span class="fa fa-btn fa-usd"></span>
                                Efectivo
                            </a>
                        </div>

                        <div class="form-group">
                            <a href="{{ url('/home') }}" class="btn btn-primary">
                                <span class="glyphicon glyphicon-step-backward"></span>
                                Volver al panel de usuario
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
