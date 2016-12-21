@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
@endsection

@section('content')
<div id="page-wrapper">

    <!-- Header -->
    <header id="header">
        <h1><a href="#">Tombo Fans</a></h1>
    </header>

    <!-- Main -->
    <article id="main">
        <header>
            <h2>Bienvenido a Tombo Fans</h2>
            <p>Seccion de contacto</p>
        </header>
        <section class="wrapper style5">
            <div class="inner">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('notification'))
                    <div class="alert alert-success">
                        {{ session('notification') }}
                    </div>
                @endif

                <form method="post" action="">
                    {{ csrf_field() }}
                    <div class="row uniform">
                        <div class="6u 12u$(xsmall)">
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Ingresa tu nombre">
                        </div>
                        <div class="6u$ 12u$(xsmall)">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="E-mail">
                        </div>
                        <div class="6u 12u$(xsmall)">
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Teléfono">
                        </div>
                        <div class="6u$ 12u$(xsmall)">
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="Código postal">
                        </div>
                        <div class="12u$">
                            <textarea name="content" placeholder="Ingresa aquí tu mensaje" rows="4">{{ old('content') }}</textarea>
                        </div>
                        {{--<div class="g-recaptcha" data-sitekey="6LfZag8UAAAAANklvgMCK3F-30c-_4a4tA8SIyej"></div>--}}
                        <div class="12u$">
                            <ul class="actions">
                                <li><input type="submit" value="Enviar mensaje" class="special"></li>
                                <li><input type="reset" value="Limpiar campos"></li>
                            </ul>
                        </div>
                    </div>
                </form>

            </div>
        </section>
    </article>

    <!-- Footer -->
    @include('includes.footer')
</div>
@endsection

@section('scripts')
    {{--<script src='https://www.google.com/recaptcha/api.js'></script>--}}
    <script src="{{ asset('/assets/guess/contact.js') }}"></script>
@endsection