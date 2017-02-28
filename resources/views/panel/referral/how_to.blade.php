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

                <hr>

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

                <p>
                    <span>O comparte directamente tu enlace en las redes sociales! &rarr;</span>
                    <a href="https://twitter.com/intent/tweet?text=Está muy buena esta app para generar leads, clientes potenciales y más interacciones en tu página de fans&url={{ url(auth()->user()->referral_link) }}" rel="nofollow" target="_blank" title="Compartir en Twitter" class="btn btn-success btn-sm">
                        <i class="fa fa-twitter"></i>
                    </a> &nbsp;
                    <a href="https://facebook.com/sharer.php?u={{ url(auth()->user()->referral_link) }}" rel="nofollow" target="_blank" title="Compartir en Facebook" class="btn btn-success btn-sm">
                        <i class="fa fa-facebook"></i>
                    </a> &nbsp;
                    <a href="https://plus.google.com/share?url={{ url(auth()->user()->referral_link) }}" rel="nofollow" target="_blank" title="Compartir en Google+" class="btn btn-success btn-sm">
                        <i class="fa fa-google-plus"></i>
                    </a> &nbsp;
                    <a href="mailto:?subject=Mira esta promoción&amp;body=Está muy buena esta app para generar leads, clientes potenciales y más interacciones en tu página de fans. Regístrate en: {{ url(auth()->user()->referral_link) }}"
                       title="Compartir vía mail" class="btn btn-success btn-sm">
                        <i class="fa fa-envelope"></i>
                    </a> &nbsp;
                    <a href="whatsapp://send?text=Está muy buena esta app para generar leads, clientes potenciales y más interacciones en tu página de fans. Regístrate en: {{ url(auth()->user()->referral_link) }}" data-action="share/whatsapp/share" class="btn btn-success btn-sm">
                        <i class="fa fa-whatsapp"></i>
                    </a>
                </p>

                <hr>

                    <p>En las siguientes ligas encontrarás contenido que te puede ayudar para recomendar a TomboFans:</p>
                    <ul>
                        <li>
                            <a href="https://www.youtube.com/watch?v=TLfDkhq8HVQ" target="_blank">
                                Video promocional
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/watch?v=daMaXaGANBU" target="_blank">
                                Video de funcionamiento
                            </a>
                        </li>
                        <li>
                            <a href="https://tombofans.com/stories" target="_blank">
                                Casos de éxito
                            </a>
                        </li>
                    </ul>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
