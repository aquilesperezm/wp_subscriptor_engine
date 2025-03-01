document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('suscripcion-form');
    const mensaje = document.getElementById('mensaje');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

            alert('test')

        const emailField = document.getElementById('email');
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value)) {
            mensaje.textContent = "Por favor, ingresa un correo válido.";
            mensaje.style.color = "red";
            return;
        }

        mensaje.textContent = "¡Registro completado exitosamente!";
        mensaje.style.color = "green";
    });
});
