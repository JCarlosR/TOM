<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ganaste</title>
</head>
<body>
    <h1>Felicidades {{ $winner->name }}!</h1>
    <p>Ganaste la siguiente promoción:</p>
    <p><b>Nombre:</b> {{ $promotion->description }}</p>
    <p>
        <b>Imagen:</b>
        <img src="{{ asset('/images/promotions/'.$promotion->image_path) }}" alt="Imagen de {{ $promotion->description }}" class="img-responsive">
    </p>
    @if ($promotion->end_date)
        <p><b>Fecha de vencimiento:</b> {{ $promotion->end_date }}</p>
    @endif
    <hr>
    <p>Para hacerla válida solamente debes contactar al administrador de esta promoción. A continuación sus datos:</p>
    <ul>
        <li><b>Nombre:</b> {{ $owner->name }}</li>
        <li><b>Correo:</b> {{ $owner->email }}</li>
        <li><b>Fanpage:</b> {{ $promotion->fanPage->name }}</li>
        {{--<li><b>Teléfono:</b> {{ $owner->phone }}</li>--}}
    </ul>
    <hr>
    <p>
        <em>Si tu también quieres organizar promociones para aumentar tus prospectos, clientes y además interacciones en tu facebook, regístrate en www.tombofans.com</em>
    </p>
    <p>TomboFans</p>
</body>
</html>