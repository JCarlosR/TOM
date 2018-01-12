@extends('layouts.dashboard')

@section('styles')
    <style>
        .tab-content {
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 10px;
            border-radius: 0 0 4px 4px;
        }
    </style>
@endsection

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Publicar en grupos de <i class="fa fa-facebook-square"></i></div>

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

                @if (auth()->user()->is_admin)
                    @include('panel.posts.token-renewal')
                @endif

                <p><strong>Bienvenida al Grupo exclusivo de Momypreneurs</strong></p>

                <ul>
                    <li>Publicaremos tus productos y/o servicios en el grupo y fanpage del Club Momy, sin que esperes autorizaciones.</li>
                    <li>Al final de cada publicación estarán tus datos de contacto, para que te busquen de inmediato.</li>
                    <li>Difundiremos tu marca en facebook para que obtengas más clientes potenciales.</li>
                    <li>Agrega a tus publicaciones, precio, lugar de entrega, precio y forma de pago.</li>
                    <li>También puedes publicar en la cuponera virtual, accediendo a la <a href="/config">sección de promociones</a> sin pagar extras.</li>
                    <li>
                        <small>
                            Cancelaremos tu membresía si publicas armas de fuego, animales, tráfico de personas, fraudes,
                            inversiones milagrosas, inmuebles, bazares, piratería, cualquier solicitud de dinero
                            sin un producto o servicio y aquello que no cumpla con las reglas del grupo (ver descripción del grupo en facebook).
                        </small>
                    </li>
                    <li>Y lo mejor es que solo pagas por tu membresía una cuota de recuperación mínima de 99 pesos mensuales <small>(50% usados para cubrir el uso de la plataforma y el otro 50% en marketing digital)</small>.</li>
                </ul>

                <p>Solo sigue estos <strong>dos pasos</strong>:</p>
                <ul>
                    <li>Da clic en el botón para Pagar (tenemos 2 formas de pago),</li>
                    <li>y después da clic en Nueva publicación</li>
                </ul>

                <hr>

                <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3MKTQ6ZZEQYU2" target="_blank" class="btn btn-info btn-sm">
                    Pago mensual paypal <i class="fa fa-paypal"></i>
                </a>
                <a href="https://compropago.com/comprobante/?id=babe6e2c-0df0-4bd7-a5a7-9461d744f4c3" target="_blank" class="btn btn-info btn-sm">
                    Pago anual efectivo <i class="fa fa-money"></i>
                </a>

                <hr>

                <a href="{{ url('/facebook/posts/create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i>
                    Nueva publicación
                </a>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">Publicaciones programadas</div>

            <div class="panel-body">
                <ul class="nav nav-tabs nav-justified">
                    <li>
                        <a data-toggle="tab" href="#tab-finished">
                            Publicaciones realizadas
                        </a>
                    </li>
                    <li class="active">
                        <a data-toggle="tab" href="#tab-future">
                            Publicaciones programadas
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-finished" class="tab-pane fade">
                        @include('panel.posts.tab-finished')
                    </div>
                    <div id="tab-future" class="tab-pane fade in active">
                        @include('panel.posts.tab-future')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
