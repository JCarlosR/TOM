<!DOCTYPE html>
<html>
    <head>
        <title>No se ha encontrado.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                text-align: center;
            }

            .container {
                vertical-align: middle;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato', serif;
            }
            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="title">No se ha encontrado la página a la que intentaste acceder.</div>
        </div>
        <p>Haz <a href="{{ url('/') }}">clic aquí</a> para volver a la página de inicio.</p>
    </body>
</html>
