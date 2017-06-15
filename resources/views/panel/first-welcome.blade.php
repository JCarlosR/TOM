@extends('layouts.app')

@section('content')
    @include('includes.nav-top')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">Mensaje de bienvenida</div>

                    <div class="panel-body text-center">
                        <p>Hola {{ auth()->user()->name }} !</p>
                        <p>Bienvenid@ a Tombofans. La forma más fácil de impulsar tus ventas.</p>

                        <iframe width="480" height="360" src="//www.youtube.com/embed/daMaXaGANBU?vq=hd720&autoplay=1" frameborder="0" allowfullscreen></iframe>

                        <div class="form-group">
                            <a href="{{ url('/home') }}" class="btn btn-primary">
                                <span class="glyphicon glyphicon-console"></span>
                                Continuar al panel de usuario
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '419857545054647'); // Insert your pixel ID here.
        fbq('track', 'PageView');
        fbq('track', 'Lead');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=419857545054647&ev=PageView&noscript=1"
        /></noscript>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->
@endsection
