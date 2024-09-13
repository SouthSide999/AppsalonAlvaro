let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

//objeto de la cita 
const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function () {
    iniciarApp();

});

function iniciarApp() {
    mostrarSeccion(); // Muestra y oculta las secciones
    tabs(); // Cambia la sección cuando se presionen los tabs
    botonesPaginador();//agrega  o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); //consulta la API en el back end de php

    idCliente(); // Añade el id del cliente al objeto de cita
    nombreCliente(); // Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); // Añade la fecha de la cita en el objeto
    seleccionarHora(); // Añade la hora de la cita en el objeto

    mostrarResumen(); // Muestra el resumen de la cita


}

function mostrarSeccion() {
    // Ocultar la sección que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }
    // Seleccionar la sección con el paso...
    const pasoSelector = `#paso-${paso}`;//para ids
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    // Quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    // Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`); //para atributos 
    tab.classList.add('actual');
}


function tabs() {//identifica los tabs 
    // Agrega y cambia la variable de paso según el tab seleccionado
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach(boton => {
        boton.addEventListener('click', function (e) {
            e.preventDefault();
            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
        });
    });
}

function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if (paso === 1) {
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if (paso === 3) {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }
    mostrarSeccion();
}
function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function () {
        if (paso <= pasoInicial) return;
        paso--;
        botonesPaginador();
    })

}
function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function () {
        if (paso >= pasoFinal) return;
        paso++;
        botonesPaginador();
    })
}
async function consultarAPI() {

    try {
        const url = '/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}
function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        // extraer y crear la variable 
        const { id, nombre, precio } = servicio;

        const nombreServicio = document.createElement('P');//elemento html
        nombreServicio.classList.add('nombre-servicio');//selector para css
        nombreServicio.textContent = nombre;//asignar a la variable 

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$ ${precio}`;//asignar a la variable y usar template string

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function () {
            seleccionarServicio(servicio);
        };
        //agregar elementos
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);//inyectar en class servicios 
    });
}

function seleccionarServicio(servicio) {
    const { id } = servicio;//extraer id
    const { servicios } = cita; //extraer arreglo de servicios de el objeto de citas 

    // Identificar el elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);//inyectar id
    // Comprobar si un servicio ya fue agregado 
    if (servicios.some(agregado => agregado.id === id)) {//revisar con some si en un arreglo ya esta el elemento seleccionado
        // Eliminarlo
        cita.servicios = servicios.filter(agregado => agregado.id !== id);//sacar el elementos de los elementos agregado
        divServicio.classList.remove('seleccionado');//eliminar el css
    } else {
        // Agregarlo
        cita.servicios = [...servicios, servicio];//hacer una copia de arreglo de servicios y se  agrega un nuevo servicio
        divServicio.classList.add('seleccionado');//inyectar css
    }
}
function idCliente() {
    cita.id = document.querySelector('#id').value;//toma el valor

}
function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;//toma el valor

}
function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function (e) {

        const dia = new Date(e.target.value).getUTCDay();//verificar que dia es

        if ([6, 0].includes(dia)) {//si en el array existe 0,6 
            e.target.value = '';//deja en blanco el campo de fecha
            mostrarAlerta('Fines de semana no permitidos', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }
    });
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');

    inputHora.addEventListener('input', function (e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];//separar un string
        if (hora < 10 || hora > 18) {//validar la hora
            e.target.value = '';
            mostrarAlerta('Hora No Válida Horario De Atención de 10 am A 18 pm', 'error', '.formulario');
        } else {
            cita.hora = e.target.value;
            // console.log(cita);
        }
    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    // Previene que se generen más de 1 alerta
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    }
    // Scripting para crear la alerta
    const alerta = document.createElement('DIV');//incrustar elemento de css
    alerta.textContent = mensaje; //contenido
    alerta.classList.add('alerta'); //selector de css
    alerta.classList.add(tipo); //segundo selector de css


    const referencia = document.querySelector(elemento); //selecciona el html
    referencia.appendChild(alerta); //incrusta la alerta 

    if (desaparece) {//si es false no desparece la alerta
        // Eliminar la alerta
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    // Limpiar el Contenido de Resumen
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if (Object.values(cita).includes('') || cita.servicios.length === 0) {//validar si esta vació los campos o no a seleccionado ningún servicio
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false);
        return;
    }

    // Formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita;

    // Heading para Servicios en Resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);

    // Iterando y mostrando los servicios
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;
        const contenedorServicio = document.createElement('DIV');//elemento
        contenedorServicio.classList.add('contenedor-servicio');//selector

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;//contenido

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);//introducir
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);//introducir a resumen
    });

    // Heading para Cita en Resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    //nombre
    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    //fecha
    // Formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();
    const fechaUTC = new Date(Date.UTC(year, mes, dia));//objeto de fecha
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' } //formato asignado
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);//fecha ya formateada
    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;//incrustar html

    //hora
    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora} horas`;

    // Boton para Crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;//evento para el boton 

    //agregar elementos al resumen de la cita
    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    //agregar boton al resumen de la cita
    resumen.appendChild(botonReservar);

}

async function reservarCita() {
    const { nombre, fecha, hora, servicios, id } = cita
    const idServicios = servicios.map(servicio => servicio.id);
    const datos = new FormData();
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);
    //console.log([...datos]);

    try {
        //petición hacia el api
        const url = '/api/citas'
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();
        console.log(resultado);
        if (resultado.resultado) {//alerta
            Swal.fire({
                icon: "success",
                title: "Cita Creada",
                text: "Tu cita fue creada correctamente",
                button: 'OK'
            }).then(() => {
                setTimeout(() => {
                    window.location.reload(); //recargar la pagina 
                }, 3000);
            })
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "No se guardo tu cita",
            text: "Sucedió un error intentar de nuevo",
            button: 'OK'
        }).then(() => {
            setTimeout(() => {
                window.location.reload(); //recargar la pagina 
            }, 3000);
        });
    }
}