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
                            <li><a href="{{ url('/stories') }}">Casos de éxito</a></li>
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
            <div class="image"><img src="{{ asset('images/1.jpg') }}" alt="" /></div>
            <div class="content">
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
            <div class="image"><img src="{{ asset('images/2.jpg') }}" alt="" /></div><div class="content">
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
                <li>
                    <img src="{{ asset('images/steps/1.png') }}" alt="Paso 1" width="320">
                </li>
                <li>
                    <img src="{{ asset('images/steps/2.png') }}" alt="Paso 2" width="320">
                </li>
                <li>
                    <img src="{{ asset('images/steps/3.png') }}" alt="Paso 3" width="320">
                </li>
                <li>
                    <img src="{{ asset('images/steps/4.png') }}" alt="Paso 4" width="320">
                </li>
                <li>
                    <img src="{{ asset('images/steps/5.png') }}" alt="Paso 5" width="320">
                </li>
                <li>
                    <img src="{{ asset('images/steps/6.png') }}" alt="Paso 6" width="320">
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
                <li><a href="{{ url('/facebook/login') }}" class="button fit special">Registrarme</a></li>
                <li><a href="{{ url('/stories') }}" class="button fit">Ver casos</a></li>
            </ul>
        </div>
    </section>

    <!-- Footer -->
    @include('includes.footer')
</div>
@endsection
