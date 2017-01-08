$(function () {
    if (RegExp('tutorial', 'gi').test(window.location.search)) {
        var intro = introJs();

        intro.setOptions({
            steps: [
                {
                    element: '#step-3',
                    number: 3,
                    intro: "En esta sección se encuentran tus fanpages. Debes seleccionar una, y a continuación podrás registrar tu promoción."
                }
            ]
        });

        intro.setOption('doneLabel', 'Entendido')
            .setOption('nextLabel', 'Siguiente')
            .setOption('prevLabel', 'Anterior')
            .setOption('skipLabel', 'Cerrar');

        intro.start();
        console.log('Just testing');
    }
});