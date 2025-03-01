document.addEventListener('DOMContentLoaded', function () {
    const tabla = document.getElementById('tabla-suscriptores-ajax');
    const paginacion = document.getElementById('paginacion-suscriptores');
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const refreshButton = document.getElementById('refresh-grid');
    const exportButton = document.getElementById('export-csv');

    const modal = document.getElementById('loading-modal');

    let paginaActual = 1;

    // Función para mostrar el modal
    function mostrarModal() {
        modal.style.display = 'flex';
    }

    // Función para ocultar el modal
    function ocultarModal() {
        modal.style.display = 'none';
    }

    // Función para refrescar el grid
    function refreshGrid() {
        mostrarModal(); // Mostrar el modal
        cargarUsuarios(1); // Cargar usuarios desde la primera página
    }


    // Función para cargar usuarios
    function cargarUsuarios(pagina, search = '') {
        paginaActual = pagina;

        const datos = new FormData();
        datos.append('action', 'caja_suscripcion_usuarios');
        datos.append('pagina', pagina);
        datos.append('search', search);

        fetch(ajaxurl, {
            method: 'POST',
            body: datos
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                actualizarTabla(data.data.usuarios);
                actualizarPaginacion(data.data.total_paginas);
                ocultarModal();
            } else {
                console.error('Error al cargar usuarios:', data);
                ocultarModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            ocultarModal(); // Ocultar el modal incluso si hay error
        });
    }

    // Función para actualizar la tabla
    function actualizarTabla(usuarios) {
        const tbody = tabla.querySelector('tbody');
        tbody.innerHTML = '';

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
        paginacion.innerHTML = '';

        if (paginaActual > 1) {
            const botonAnterior = document.createElement('button');
            botonAnterior.textContent = 'Anterior';
            botonAnterior.classList.add('button');
            botonAnterior.addEventListener('click', () => cargarUsuarios(paginaActual - 1, searchInput.value));
            paginacion.appendChild(botonAnterior);
        }

        if (paginaActual < totalPaginas) {
            const botonSiguiente = document.createElement('button');
            botonSiguiente.textContent = 'Siguiente';
            botonSiguiente.classList.add('button');
            botonSiguiente.addEventListener('click', () => cargarUsuarios(paginaActual + 1, searchInput.value));
            paginacion.appendChild(botonSiguiente);
        }
    }

    // Refrescar el grid
    /*refreshButton.addEventListener('click', function () {
        cargarUsuarios(1);
        searchInput.value = ''; // Limpiar el campo de búsqueda
    });*/
    // Escuchar clic en el botón Refrescar
    refreshButton.addEventListener('click', refreshGrid);

    // Buscar usuarios
    searchButton.addEventListener('click', function () {
        cargarUsuarios(1, searchInput.value);
    });

    // Exportar datos a CSV
    exportButton.addEventListener('click', function () {
        const filas = tabla.querySelectorAll('tr');
        let csvContent = "data:text/csv;charset=utf-8,ID,Nombre,Email,Fecha de Registro\n";

        filas.forEach(fila => {
            const celdas = fila.querySelectorAll('td');
            if (celdas.length) {
                const filaCsv = Array.from(celdas).map(celda => `"${celda.textContent}"`).join(',');
                csvContent += filaCsv + '\n';
            }
        });

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'usuarios_suscriptores.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // Cargar usuarios al iniciar
    cargarUsuarios(paginaActual);
});
