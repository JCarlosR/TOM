$(function () {
    if (RegExp('tutorial', 'gi').test(window.location.search)) {
        var intro = introJs();

        intro.setOption('doneLabel', 'Entendido')
            .setOption('nextLabel', 'Siguiente')
            .setOption('prevLabel', 'Anterior')
            .setOption('skipLabel', 'Cerrar');

        intro.start();
    }
});