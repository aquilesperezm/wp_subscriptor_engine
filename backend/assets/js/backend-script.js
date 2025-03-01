document.addEventListener('DOMContentLoaded', function () {
    const tabla = document.getElementById('tabla-suscriptores-ajax');
    const paginacion = document.getElementById('paginacion-suscriptores');
    let paginaActual = 1;

    // Función para cargar los usuarios
    function cargarUsuarios(pagina) {
        paginaActual = pagina;

        // Configurar datos para la solicitud AJAX
        const datos = new FormData();
        datos.append('action', 'caja_suscripcion_usuarios');
        datos.append('pagina', pagina);

        fetch(ajaxurl, {
            method: 'POST',
            body: datos
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                actualizarTabla(data.data.usuarios);
                actualizarPaginacion(data.data.total_paginas);
            } else {
                console.error('Error al cargar usuarios:', data);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Función para actualizar la tabla
    function actualizarTabla(usuarios) {
        const tbody = tabla.querySelector('tbody');
        tbody.innerHTML = ''; // Limpiar la tabla

        usuarios.forEach(usuario => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${usuario.ID}</td>
                <td>${usuario.display_name}</td>
                <td>${usuario.user_email}</td>
                <td>${usuario.user_registered}</td>
            `;
            tbody.appendChild(fila);
        });
    }

    // Función para actualizar los controles de paginación
    function actualizarPaginacion(totalPaginas) {
        paginacion.innerHTML = ''; // Limpiar los controles

        if (paginaActual > 1) {
            const botonAnterior = document.createElement('button');
            botonAnterior.textContent = 'Anterior';
            botonAnterior.classList.add('button');
            botonAnterior.addEventListener('click', () => cargarUsuarios(paginaActual - 1));
            paginacion.appendChild(botonAnterior);
        }

        if (paginaActual < totalPaginas) {
            const botonSiguiente = document.createElement('button');
            botonSiguiente.textContent = 'Siguiente';
            botonSiguiente.classList.add('button');
            botonSiguiente.addEventListener('click', () => cargarUsuarios(paginaActual + 1));
            paginacion.appendChild(botonSiguiente);
        }
    }

    // Cargar usuarios al iniciar
    cargarUsuarios(paginaActual);
});
