<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TOM</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

    @yield('styles')
</head>
<body class="landing">
    @yield('content')

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/jquery.scrollex.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.scrolly.min.js') }}"></script>
    <script src="{{ asset('assets/js/skel.min.js') }}"></script>
    <script src="{{ asset('assets/js/util.js') }}"></script>
    <!--[if lte IE 8]><script src="{{ asset('assets/js/ie/respond.min.js') }}"></script><![endif]-->
    <script src="{{ asset('assets/js/main.js') }}"></script>

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

    @yield('scripts')
</body>
</html>
