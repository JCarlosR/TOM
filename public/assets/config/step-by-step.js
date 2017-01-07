$(function () {
    var intro = introJs();
    intro.setOption('doneLabel', 'Continuar configuración')
        .setOption('nextLabel', 'Siguiente')
        .setOption('prevLabel', 'Anterior')
        .setOption('skipLabel', 'Cerrar');

    intro.start().oncomplete(function() {
        window.location.href = '/config';
    });
});