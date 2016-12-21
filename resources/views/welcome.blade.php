@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
@endsection

@section('content')
<!-- Page Wrapper -->
<div id="page-wrapper">

    <!-- Header -->
    <header id="header" class="alt">
        <h1><a href="#">Tombo Fans</a></h1>
        <nav id="nav">
            <ul>
                <li class="special">
                    <a href="#menu" class="menuToggle"><span>Menu</span></a>
                    <div id="menu">
                        <ul>
                            <li><a href="{{ url('/') }}">Inicio</a></li>
                            <li><a href="{{ url('/facebook/login') }}">Ingresar</a></li>
                            <li><a href="{{ url('/contact') }}">Contacto</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Banner -->
    <section id="banner">
        <div class="inner">
            <h2>Tombo Fans</h2>
            <p>La forma más fácil de impulsar tus ventas<br />
                e interacciones en tu fanpage,<br />
                además de conseguir <a href="#">clientes más felices</a>.</p>
            <ul class="actions">
                <li><a href="{{ url('/facebook/login') }}" class="button special">Ingresar</a></li>
            </ul>
        </div>
        <a href="#one" class="more scrolly">Ver más</a>
    </section>

    <!-- One -->
    <section id="one" class="wrapper style1 special">
        <div class="inner">
            <header class="major">
                <h2>¿Qué es TomboFans?</h2>
                <p>TomboFans es una aplicación que desarrollamos para que uses en tu fanpage, así
                    tus fans, clientes anteriores y posibles prospectos, si interactuan dándote like o haciendo check in en tu fanpage, pueden ganar promociones y descuentos que tu configuras en TomboFans.</p>
            </header>
            <ul class="icons major">
                <li><span class="icon fa-diamond major style1"><span class="label">Diamong</span></span></li>
                <li><span class="icon fa-heart-o major style2"><span class="label">Heart</span></span></li>
                <li><span class="icon fa-code major style3"><span class="label">Code</span></span></li>
            </ul>
        </div>
    </section>

    <!-- Two -->
    <section id="two" class="wrapper alt style2">
        <section class="spotlight">
            <div class="image"><img src="{{ asset('images/pic01.jpg') }}" alt="" /></div><div class="content">
                <h3>Si eres emprendedora o emprendedor:</h3>
                <ul class="no-bullet">
                    <li><i class="fa fa-check"></i> Incrementar tus prospectos y  tus ventas cuando invitas a participar en TomboFans.</li>
                    <li><i class="fa fa-check"></i> Aumentas las interacciones de tus clientes en tu página de fans en facebook.</li>
                    <li><i class="fa fa-check"></i> Consigues testimonios de tus clientes en facebook que te generarán más ventas.</li>
                    <li><i class="fa fa-check"></i> Fidelizas a tus clientes, te compran más veces y los tienes cautivos en tu facebook.</li>
                </ul>
            </div>
        </section>
        <section class="spotlight">
            <div class="image"><img src="{{ asset('images/pic02.jpg') }}" alt="" /></div><div class="content">
                <h3>Si eres participante de TomboFans:</h3>
                <ul class="no-bullet">
                    <li><i class="fa fa-check"></i> Obtienes descuentos o promociones GRATIS cuando te invitan a participar.</li>
                    <li><i class="fa fa-check"></i> Te enteras en facebook de las novedades de los productos o servicios que compras.</li>
                    <li><i class="fa fa-check"></i> Tienes los datos de los emprendedores que venden productos o servicios.</li>
                </ul>
            </div>
        </section>
        {{--<section class="spotlight">--}}
            {{--<div class="image"><img src="{{ asset('images/pic03.jpg') }}" alt="" /></div><div class="content">--}}
                {{--<h2>Augue eleifend aliquet<br />--}}
                    {{--sed condimentum</h2>--}}
                {{--<p>Aliquam ut ex ut augue consectetur interdum. Donec hendrerit imperdiet. Mauris eleifend fringilla nullam aenean mi ligula.</p>--}}
            {{--</div>--}}
        {{--</section>--}}
    </section>

    <!-- Three -->
    <section id="three" class="wrapper style3 special">
        <div class="inner">
            <header class="major">
                <h2>¿Cómo funciona?</h2>
                <p>En menos de 3 minutos configuras tu TomboFan, siguiendo estos simples pasos:</p>
            </header>
            <ul class="features">
                <li class="icon fa-laptop">
                    <h3>Paso 1</h3>
                    <p>Escribe tu promoción y sube una imagen.</p>
                </li>
                <li class="icon fa-paper-plane-o">
                    <h3>Paso 2</h3>
                    <p>Invita a que participen en tu TomboFan (fans, amigos, clientes anteriores y prospectos).</p>
                </li>
                <li class="icon fa-code">
                    <h3>Paso 3</h3>
                    <p>Tus participantes, si tienen suerte, ganan tus promociones y/o descuentos.</p>
                </li>
                <li class="icon fa-flag-o">
                    <h3>Paso 4</h3>
                    <p>Ve como aumentan tus ventas y ademas los likes e interacciones en tu fanpage.</p>
                </li>
                <li class="icon fa-mail-forward">
                    <h3>Paso 5</h3>
                    <p>Recibes por correo los datos de tus participantes para que los contactes.</p>
                </li>
                <li class="icon fa-heart-o">
                    <h3>Finalmente</h3>
                    <p>Obtienes múltiples beneficios y tus clientes están más satisfechos.</p>
                </li>
            </ul>
        </div>
    </section>

    <!-- CTA -->
    <section id="cta" class="wrapper style4">
        <div class="inner">
            <header>
                <h2>¿Aún no te has convencido?</h2>
                <p>Mira nuestra sección de casos de éxito, de otros emprendedores que ya han usado TomboFans.</p>
            </header>
            <ul class="actions vertical">
                <li><a href="#" class="button fit special">Registrarme</a></li>
                <li><a href="#" class="button fit">Ver casos</a></li>
            </ul>
        </div>
    </section>

    <!-- Footer -->
    @include('includes.footer')
</div>
@endsection
