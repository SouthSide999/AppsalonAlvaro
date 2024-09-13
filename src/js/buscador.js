
document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
    buscarPorFecha();
}

function buscarPorFecha() {
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input', function(e) {
        const fechaSeleccionada = e.target.value;
        console.log(fechaSeleccionada);
        window.location = `?fecha=${fechaSeleccionada}`;//manda con el método get la fecha seleccionada
    });
}