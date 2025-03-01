<?php

function caja_suscripcion_shortcode() {
    $boton_texto = get_option('suscripcion_boton_texto', 'Suscribirse');
    $mensaje_exito = get_option('suscripcion_mensaje_exito', '¡Registro completado exitosamente!');
    $css_personalizado = get_option('suscripcion_css', '');

    ob_start(); ?>
    <style>
        <?php echo $css_personalizado; ?>
    </style>
    <form id="suscripcion-form" action="" method="post">
        <label for="email">Correo Electrónico:</label><br>
        <input type="text" id="email" name="email" required placeholder="Ingresa tu correo">
        <button type="submit"><?php echo esc_html($boton_texto); ?></button>
        <p id="mensaje"></p>
    </form>
    <?php return ob_get_clean();
}
add_shortcode('caja_suscripcion', 'caja_suscripcion_shortcode');
