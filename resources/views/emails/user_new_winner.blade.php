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
    <p>Te escribimos este mensaje porque hay un nuevo ganador en tu promo.</p>
    <p><b>Promoción:</b> {{ $promotion->description }}</p>
    <img src="{{ asset('/images/promotions/'.$promotion->image_path) }}" alt="Imagen de {{ $promotion->description }}" class="img-responsive">
    <hr>
    <p>Por favor ponte en contacto con el ganador de tu promoción. A continuación sus datos:</p>
    <ul>
        <li><b>Nombre:</b> {{ $winner->name }}</li>
        <li><b>E-mail:</b> {{ $winner->email }}</li>
        {{--<li><b>Teléfono:</b> {{ $winner->phone }}</li>--}}
    </ul>
</body>
</html>