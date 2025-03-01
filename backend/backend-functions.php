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

// Endpoint para obtener usuarios suscriptores mediante AJAX
function caja_suscripcion_usuarios_ajax() {
    // Verificar permisos
    if (!current_user_can('manage_options')) {
        wp_send_json_error('No tienes permisos suficientes para realizar esta acción.');
        return;
    }

    // Obtener parámetros de la solicitud
    $pagina_actual = isset($_POST['pagina']) ? absint($_POST['pagina']) : 1;
    $usuarios_por_pagina = 5; // Número de usuarios por página

    // Obtener usuarios suscriptores con paginación
    $args = array(
        'role' => 'subscriber',
        'orderby' => 'user_registered',
        'order' => 'DESC',
        'number' => $usuarios_por_pagina,
        'offset' => ($pagina_actual - 1) * $usuarios_por_pagina,
    );
    $usuarios = get_users($args);

    // Preparar respuesta
    $data = array();
    foreach ($usuarios as $usuario) {
        $data[] = array(
            'ID' => $usuario->ID,
            'display_name' => $usuario->display_name,
            'user_email' => $usuario->user_email,
            'user_registered' => $usuario->user_registered,
        );
    }

    // Calcular total de páginas
    $total_usuarios = count(get_users(array('role' => 'subscriber')));
    $total_paginas = ceil($total_usuarios / $usuarios_por_pagina);

    // Responder con datos
    wp_send_json_success(array(
        'usuarios' => $data,
        'total_paginas' => $total_paginas,
    ));
}
add_action('wp_ajax_caja_suscripcion_usuarios', 'caja_suscripcion_usuarios_ajax');