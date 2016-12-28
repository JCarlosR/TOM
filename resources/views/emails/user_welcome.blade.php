<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bienvenido</title>
</head>
<body>
    <p>Hola, bienvenido a TomboFans. Estos son sus datos:</p>
    <ul>
        <li>
            <b>Nombre:</b> {{ $user->name }}
        </li>
        <li>
            <b>E-mail:</b> {{ $user->email }}
        </li>
    </ul>
    <p>Recuerde iniciar sesión usando siempre su cuenta de facebook.</p>
    <hr>
    <p>Puede responder a este mismo correo (<em>hola@tombofans.com</em>) si tiene alguna duda.</p>
</body>
</html>