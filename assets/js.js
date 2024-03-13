var mainDiv = document.getElementById('mainDiv');
var divTareas = document.createElement('div');
divTareas.classList.add('container', 'row', 'm-auto');
var divMisTareas = document.createElement('div');
divMisTareas.classList.add('col-12', 'text-center', 'my-3');
var textoMisTareas = document.createElement('h1');
textoMisTareas.textContent = 'Tareas de tu usuario';
divMisTareas.appendChild(textoMisTareas);
divTareas.appendChild(divMisTareas);

document.addEventListener('DOMContentLoaded', obtenerTareasDeUsuario)

function obtenerTareasDeUsuario() {
    fetch('/api/tarea/show_by_usuario/' + window.location.href.substring(window.location.href.lastIndexOf('/') + 1))
        .then(response => response.json())
        .then(data => {
            if (data.length !== 0) {
                data.forEach(tarea => {
                    var divContenedorTarea = document.createElement('div');
                    divContenedorTarea.classList.add('row', 'container', 'm-auto', 'p-0');
                    var divTarea = document.createElement('div');
                    divTarea.classList.add('col-9', 'd-flex', 'bg-dark', 'text-white', 'rounded', 'rounded-4', 'm-2', 'p-3', 'border', 'border-2', 'border-success');
                    var pTarea = document.createElement('p');
                    pTarea.classList.add('m-auto');
                    pTarea.textContent = tarea.nombre;
                    var botonEliminarTarea = document.createElement('button');
                    botonEliminarTarea.id = tarea.id;
                    botonEliminarTarea.classList.add('col-2', 'btn', 'btn-danger', 'rounded', 'rounded-4', 'm-2', 'p-3', 'border', 'border-2', 'border-black', 'm-auto');
                    botonEliminarTarea.textContent = 'Eliminar tarea';
                    botonEliminarTarea.addEventListener('click', function () {
                        eliminarTarea(tarea.id);
                    })
                    divTarea.appendChild(pTarea);
                    divContenedorTarea.appendChild(divTarea);
                    divContenedorTarea.appendChild(botonEliminarTarea);
                    divTareas.appendChild(divContenedorTarea);
                })
            } else {
                var divNoTarea = document.createElement('div');
                divNoTarea.classList.add('col-12', 'd-flex', 'bg-dark', 'text-white', 'rounded', 'rounded-4', 'm-2', 'p-3', 'border', 'border-2', 'border-success');
                var pNoTarea = document.createElement('p');
                pNoTarea.classList.add('m-auto');
                pNoTarea.textContent = 'Tu usuario no tiene tareas todavía';
                divNoTarea.appendChild(pNoTarea);
                divTareas.appendChild(divNoTarea);
            }

            var divInputBotonNuevaTarea = document.createElement('div');
            divInputBotonNuevaTarea.classList.add('container', 'row', 'm-auto');
            var inputNuevaTarea = document.createElement('input');
            inputNuevaTarea.type = 'text';
            inputNuevaTarea.name = 'nombre';
            inputNuevaTarea.classList.add('col-9', 'rounded', 'rounded-4', 'm-2', 'p-3', 'border', 'border-2', 'border-success')
            var botonNuevaTarea = document.createElement('button');
            botonNuevaTarea.classList.add('col-2', 'btn', 'btn-success', 'rounded', 'rounded-4', 'm-2', 'p-3', 'border', 'border-2', 'border-white', 'm-auto');
            botonNuevaTarea.textContent = 'Añadir tarea';
            botonNuevaTarea.addEventListener('click', function () {
                if (data.length === 0) {
                    divNoTarea.remove();
                }
                nuevaTarea(window.location.href.substring(window.location.href.lastIndexOf('/') + 1), inputNuevaTarea.value);
            });

            var divLogout = document.createElement('div');
            divLogout.classList.add('container', 'row', 'm-auto');
            var botonLogout = document.createElement('button');
            botonLogout.classList.add('btn', 'btn-warning', 'rounded', 'rounded-4', 'm-2', 'p-3', 'border', 'border-2', 'border-black', 'm-auto', 'text-black', 'w-25');
            botonLogout.textContent = 'Cerrar sesión';

            var url = window.location.href;
            var nuevaRuta = url.split('/').slice(0, 3).join('/');

            botonLogout.addEventListener('click', function () {
                window.location.href = nuevaRuta + '/logout';
            });

            divLogout.appendChild(botonLogout);

            divInputBotonNuevaTarea.appendChild(inputNuevaTarea);
            divInputBotonNuevaTarea.appendChild(botonNuevaTarea);

            mainDiv.appendChild(divTareas);
            mainDiv.appendChild(divInputBotonNuevaTarea);
            mainDiv.appendChild(divLogout);
            console.log(data)
        })
        .catch(error => console.log(error))
}

function nuevaTarea(email, nombreTarea) {
    if (nombreTarea === "") {
        alert('No puedes insertar una tarea vacía');
    } else {
        const tarea = new FormData();
        tarea.append('email', email);
        tarea.append('nombreTarea', nombreTarea);

        const options = {
            method: "POST",
            body: tarea
        };

        fetch('/api/tarea/add', options)
            .then(response => response.json())
            .then(tarea => {
                var divContenedorTarea = document.createElement('div');
                divContenedorTarea.classList.add('row', 'container', 'm-auto', 'p-0');
                var divTarea = document.createElement('div');
                divTarea.classList.add('col-9', 'd-flex', 'bg-dark', 'text-white', 'rounded', 'rounded-4', 'm-2', 'p-3', 'border', 'border-2', 'border-success');
                var pTarea = document.createElement('p');
                pTarea.classList.add('m-auto');
                pTarea.textContent = tarea.nombre;
                var botonEliminarTarea = document.createElement('button');
                botonEliminarTarea.id = tarea.id;
                botonEliminarTarea.classList.add('col-2', 'btn', 'btn-danger', 'rounded', 'rounded-4', 'm-2', 'p-3', 'border', 'border-2', 'border-black', 'm-auto');
                botonEliminarTarea.textContent = 'Eliminar tarea';
                botonEliminarTarea.addEventListener('click', function () {
                    eliminarTarea(tarea.id);
                })
                divTarea.appendChild(pTarea);
                divContenedorTarea.appendChild(divTarea);
                divContenedorTarea.appendChild(botonEliminarTarea);
                divTareas.appendChild(divContenedorTarea);
            })
            .catch(error => console.log(error))
    }
}

function eliminarTarea(id) {
    fetch('/api/tarea/delete?id=' + id)
        .then(response => response.json())
        .then(resultado => {
            if (resultado.resultado === 'ok') {
                var tarea = document.getElementById(id).parentElement;
                tarea.remove();
            } else {
                alert('Error al eliminar la tarea');
            }
        })
        .catch(error => console.log(error))
}