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

// Endpoint para obtener usuarios suscriptores con AJAX
function caja_suscripcion_usuarios_ajax() {
    // Verificar permisos
    if (!current_user_can('manage_options')) {
        wp_send_json_error('No tienes permisos suficientes para realizar esta acción.');
        return;
    }

    // Obtener parámetros de la solicitud
    $pagina_actual = isset($_POST['pagina']) ? absint($_POST['pagina']) : 1;
    $usuarios_por_pagina = 10; // Número de usuarios por página
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : ''; // Búsqueda

    // Configurar los argumentos de la consulta
    $args = array(
        'role' => 'subscriber',
        'orderby' => 'user_registered',
        'order' => 'DESC',
        'number' => $usuarios_por_pagina,
        'offset' => ($pagina_actual - 1) * $usuarios_por_pagina,
        'search' => '*' . $search . '*', // Buscar usuarios por nombre (si se especifica)
        'search_columns' => array('display_name', 'user_email'), // Buscar en nombre y email
    );

    // Obtener los usuarios
    $usuarios = get_users($args);

    // Preparar los datos para la respuesta
    $data = array();
    foreach ($usuarios as $usuario) {
        $data[] = array(
            'ID' => $usuario->ID,
            'display_name' => $usuario->display_name,
            'user_email' => $usuario->user_email,
            'user_registered' => $usuario->user_registered,
        );
    }

    // Calcular el total de usuarios y páginas
    $total_usuarios = count(get_users(array('role' => 'subscriber', 'search' => '*' . $search . '*', 'search_columns' => array('display_name', 'user_email'))));
    $total_paginas = ceil($total_usuarios / $usuarios_por_pagina);

    // Enviar la respuesta en formato JSON
    wp_send_json_success(array(
        'usuarios' => $data,
        'total_paginas' => $total_paginas,
    ));
}
add_action('wp_ajax_caja_suscripcion_usuarios', 'caja_suscripcion_usuarios_ajax');