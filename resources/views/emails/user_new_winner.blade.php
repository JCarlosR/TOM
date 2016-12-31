<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nuevo ganador</title>
</head>
<body>
    <h1>Hola {{ $owner->name }}!</h1>
    <p>Tienes un nuevo ganador de tu promoción, contáctalo y conviértelo en tu próximo cliente:</p>
    <ul>
        <li><b>Nombre:</b> {{ $winner->name }}</li>
        <li><b>E-mail:</b> {{ $winner->email }}</li>
        {{--<li><b>Teléfono:</b> {{ $winner->phone }}</li>--}}
    </ul>
    <hr>
    <p>La promoción que debes hacerle válida al ganador es la siguiente:</p>
    <p><b>Nombre:</b> {{ $promotion->description }}</p>
    <p>
        <b>Imagen:</b>
        <img src="{{ asset('/images/promotions/'.$promotion->image_path) }}" alt="Imagen de {{ $promotion->description }}" class="img-responsive">
    </p>
    @if ($promotion->end_date)
        <p><b>Fecha de vencimiento:</b> {{ $promotion->end_date }}</p>
    @endif
    <hr>
    <p>
        <em>Sigue usando TomboFans y convierte a los ganadores en clientes.</em>
    </p>
    <p>TomboFans</p>
    <p>www.tombofans.com</p>
</body>
</html>