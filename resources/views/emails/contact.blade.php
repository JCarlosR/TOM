<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contacto</title>
</head>
<body>
    <p>Hola, un usuario ha usado el formulario de contacto en TomboFans. Estos son sus datos:</p>
    <ul>
        <li>
            <b>Nombre:</b> {{ $name }}
        </li>
        <li>
            <b>E-mail:</b> {{ $email }}
        </li>
        <li>
            <b>Teléfono:</b> {{ $phone }}
        </li>
        <li>
            <b>Código postal:</b> {{ $postal_code }}
        </li>
    </ul>
    <p>Y este es el mensaje que ha escrito:</p>
    <p>{{ $content }}</p>
</body>
</html>