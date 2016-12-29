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
    <p>Te escribimos este mensaje porque has ganado la siguiente promoción.</p>
    <p><b>Promoción:</b> {{ $promotion->description }}</p>
    <img src="{{ asset('/images/promotions/'.$promotion->image_path) }}" alt="Imagen de {{ $promotion->description }}" class="img-responsive">
    <hr>
    <p>Por favor ponte en contacto con el administrador de esta promoción. A continuación sus datos:</p>
    <ul>
        <li><b>Nombre:</b> {{ $owner->name }}</li>
        <li><b>E-mail:</b> {{ $owner->email }}</li>
        {{--<li><b>Teléfono:</b> {{ $owner->phone }}</li>--}}
    </ul>
</body>
</html>