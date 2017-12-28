<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TomboFans | Impulsa tus ventas y consigue clientes más felices</title>

    {{-- Fonts --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    {{-- Styles --}}
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="TomboFans" />
    <meta property="og:description" content="Impulsa tus ventas y consigue clientes más felices" />
    @section('og-image')
        <meta property="og:image" content="{{ asset('images/welcome.png') }}">
    @show

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('styles')
</head>
<body class="landing">
    @yield('content')

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/jquery.scrollex.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.scrolly.min.js') }}"></script>
    <script src="{{ asset('assets/js/skel.min.js') }}"></script>
    <script src="{{ asset('assets/js/util.js') }}"></script>
    <!--[if lte IE 8]><script src="{{ asset('assets/js/ie/respond.min.js') }}"></script><![endif]-->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- ManyChat --}}
    <script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = “//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.11&appId=532160876956612”;fjs.parentNode.insertBefore(js, fjs);}(document, ‘script’, ‘facebook­jssdk’));</script>
    <script src="//widget.manychat.com/1567109470249042.js" async="async"></script>

    {{-- Support type date for Firefox, Safari & IE --}}
    <script>
        jQuery.swap = function( elem, options, callback, args ) {
            var ret, name, old = {};
            for ( name in options ) {
                old[ name ] = elem.style[ name ];
                elem.style[ name ] = options[ name ];
            }
            ret = callback.apply( elem, args || [] );
            for ( name in options ) {
                elem.style[ name ] = old[ name ];
            }
            return ret;
        };
    </script>
    <script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
    <script>
        webshims.setOptions('forms-ext', {types: 'date'});
        webshims.polyfill('forms forms-ext');
    </script>

    {{-- Google Analytics --}}
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-91013578-1', 'auto');
        ga('send', 'pageview');
    </script>

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '419857545054647');
        fbq('track', 'PageView');
        @yield('track-fb')
    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=1635345243397630&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->

    @yield('scripts')
</body>
</html>
