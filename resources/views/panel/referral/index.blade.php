@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Obtener referidos</div>

            <div class="panel-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                <p>Bienvenido a tu sección de referidos, {{ auth()->user()->name }}.</p>

                <p>
                    Aquí podrás ganar dinero de manera mensual y constante (sí, como una renta),
                    simplemente por recomendar o referir tombofans con tus amigos, clientes o cualquier persona
                    que conozcas que tenga una página de fans, y quiera aumentar sus likes (me gusta),
                    checkins (estoy aquí), y shares (que otros compartan publicaciones en sus muros de facebook);
                    además de generar leads, aumentar prospectos y tener más clientes usando su página de fans.
                </p>

                <blockquote>
                    Tu ganas el 15% de las suscripciones mensuales de aquellos que se registraron
                    por medio de tu link (ubicado en la parte inferior), y los ganarás de manera recurrente,
                    siempre y cuando los usuarios que referiste o afiliaste estén pagando mes a mes su suscripción.
                </blockquote>

                <p class="small">Todas tus ganancias las recibirás en tu cuenta de paypal.</p>

                <p>
                    Obtener referidos es muy fácil, porque tombofans por ahora no tiene competencia
                    y tú puedes explicarles los beneficios de tener una aplicación muy sencilla
                    desde su página de fans o fanpage a un costo bajo, comparado con otros mecanismos
                    de publicidad. Solamente debes compartir el siguiente enlace con todas las personas que tengan una página de fans:
                </p>
                <input type="text" class="form-control" value="{{ url(auth()->user()->referral_link) }}">

                <p>Si lo deseas también puedes incluir el siguiente código HTML en tu sitio web para incluir un banner y aumentar tu número de afiliados:</p>
                <textarea class="form-control"><a href="{{ url(auth()->user()->referral_link) }}">Regístrate en TomboFans, aumenta tus ventas y consigue clientes más felices!</a></textarea>

                <hr>

                    <p>En las siguientes ligas encontrarás contenido que te puede ayudar para recomendar a TomboFans:</p>
                    <ul>
                        <li>
                            <a href="https://www.youtube.com/watch?v=TLfDkhq8HVQ">
                                Video promocional
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/watch?v=daMaXaGANBU">
                                Video de funcionamiento
                            </a>
                        </li>
                        <li>
                            <a href="https://tombofans.com/stories">
                                Casos de éxito
                            </a>
                        </li>
                    </ul>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">Mis referidos</div>

            <div class="panel-body">

                <div class="text-right">
                    <a href="{{ url('/referrals/excel') }}" class="btn btn-success btn-sm">
                        <span class="glyphicon glyphicon-export"></span>
                        Exportar a Excel
                    </a>
                </div>

                <p>Desde esta página podrás ver un listado de los usuarios que se han registrado gracias a tu invitación.</p>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Fanpages</th>
                            <th>Fecha de registro</th>
                            <th>Participaciones restantes</th>
                            <th>Última participación</th>
                            <th>Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($creators as $creator)
                            <tr>
                                <td>
                                    <a href="//facebook.com/{{ $creator->facebook_user_id }}" target="_blank" title="{{ $creator->facebook_user_id }}">
                                        {{ $creator->name }}
                                    </a>
                                </td>
                                <td>{{ $creator->email }}</td>
                                <td>{{ $creator->fanPagesCount }}</td>
                                <td>{{ $creator->created_at }}</td>
                                <td>{{ $creator->remaining_participations }}</td>
                                <td>{{ $creator->updated_at }}</td>
                                <td>
                                    <a href="{{ url('referral/'.$creator->id.'/fan-pages') }}" class="btn btn-info btn-sm" title="Ver fan pages">
                                        <span class="fa fa-eye"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
