<?php

// Guardar configuraciones del plugin
function caja_suscripcion_guardar_configuracion() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['suscripcion_guardar'])) {
        update_option('suscripcion_boton_texto', sanitize_text_field($_POST['boton_texto']));
        update_option('suscripcion_mensaje_exito', sanitize_text_field($_POST['mensaje_exito']));
        update_option('suscripcion_css', wp_strip_all_tags($_POST['css_personalizado']));
        echo '<div class="updated"><p>Configuraciones guardadas.</p></div>';
    }
}

// Obtener configuraciones del plugin
function caja_suscripcion_obtener_configuraciones() {
    return array(
        'boton_texto' => get_option('suscripcion_boton_texto', 'Suscribirse'),
        'mensaje_exito' => get_option('suscripcion_mensaje_exito', '¡Registro completado exitosamente!'),
        'css_personalizado' => get_option('suscripcion_css', ''),
    );
}
