$(function () {
    var intro = introJs();

    intro.setOptions({
        steps: [
            // {
            //     intro: "Hello world!"
            // },
            {
                element: '#step-1',
                intro: "Bienvenido! Ahora mismo te encuentras en esta página. Los datos que aparecen aquí son obtenidos a partir de tu facebook, y solo tú los puedes ver.",
                position: 'right'
            },
            {
                element: '#step-2',
                intro: "Lo primero que debes hacer es ingresar a esta opción para configurar tus promociones.",
                position: 'right'
            }
        ]
    });

    intro.setOption('doneLabel', 'Continuar configuración')
        .setOption('nextLabel', 'Siguiente')
        .setOption('prevLabel', 'Anterior')
        .setOption('skipLabel', 'Cerrar');

    intro.start().oncomplete(function() {
        window.location.href = '/config?tutorial=true';
    });
});