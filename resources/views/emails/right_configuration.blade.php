<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Configuración correcta</title>
</head>
<body>
    <h1>Hola {{ $creator_name }}!</h1>
    <p>Te enviamos este mail para recordarte que has creado correctamente tu promoción {{ $promotion->name }}!</p>
    <p>Solo hace falta que sigas un par de pasos para terminar la configuración:</p>
    <ul>
        <li>Copiar la dirección URL de tu promoción.</li>
        <li>Crear un botón de <em>Call to action</em> en facebook.</li>
        <li>Asociar el enlace de tu promoción con el botón que has creado.</li>
    </ul>
    <p>Por favor <a href="{{ url("/promotion/$promotion->id/instructions") }}">haz clic aquí</a> para ver las instrucciones con mayor detalle.</p>
    <hr>
    <p>
        <em>Sigue usando TomboFans y convierte a los ganadores en clientes.</em>
    </p>
    <p>TomboFans</p>
    <p>www.tombofans.com</p>
</body>
</html>