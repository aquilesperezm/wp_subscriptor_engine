document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('suscripcion-form');
    const mensaje = document.getElementById('mensaje');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const emailField = document.getElementById('email');
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value)) {
            mensaje.textContent = "Por favor, ingresa un correo válido.";
            mensaje.style.color = "red";
            return;
        }

        // Simulación de éxito
        mensaje.textContent = mensajeExito; // Usar el mensaje pasado desde PHP
        mensaje.style.color = "green";
    });
});
