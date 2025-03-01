<?php
/**
* Plugin Name: WP Suscriptor Engine OLD
* Description: A short description of the plugin.
* Version: 1.0.0
* Requires at least: 5.3
* Requires PHP: 5.6
* Author: Aquiles Pérez Miranda
*/

// Función para el shortcode
function caja_suscripcion_shortcode() {
    $boton_texto = get_option('suscripcion_boton_texto', 'Suscribirse');
    $mensaje_exito = get_option('suscripcion_mensaje_exito', '¡Registro completado exitosamente!');
    $css_personalizado = get_option('suscripcion_css', ''); // CSS personalizado

    ob_start(); ?>
    <style>
        <?php echo $css_personalizado; ?>
    </style>
    <form id="suscripcion-form" action="" method="post">
        <label for="email">Correo Electrónico:</label><br>
        <input type="email" id="email" name="email" required placeholder="Ingresa tu correo">
        <button type="submit"><?php echo esc_html($boton_texto); ?></button>
        <p id="mensaje"></p>
    </form>
    <script>
        document.getElementById('suscripcion-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const emailField = document.getElementById('email');
            const mensaje = document.getElementById('mensaje');
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value)) {
                mensaje.textContent = "Por favor, ingresa un correo válido.";
                mensaje.style.color = "red";
                return;
            }
            mensaje.textContent = "<?php echo esc_js($mensaje_exito); ?>";
            mensaje.style.color = "green";
        });
    </script>
    <?php return ob_get_clean();
}
add_shortcode('caja_suscripcion', 'caja_suscripcion_shortcode');

// Agrega un menú principal
function caja_suscripcion_agregar_menu_principal() {
    add_menu_page(
        'Configuración de Suscripción', 
        'Suscripción', 
        'manage_options', 
        'caja-suscripcion', 
        'caja_suscripcion_pagina_configuracion', 
        'dashicons-email-alt', 
        20
    );
}
add_action('admin_menu', 'caja_suscripcion_agregar_menu_principal');

// Genera la página de configuración con pestañas y CSS personalizado
function caja_suscripcion_pagina_configuracion() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['suscripcion_guardar'])) {
        update_option('suscripcion_boton_texto', sanitize_text_field($_POST['boton_texto']));
        update_option('suscripcion_mensaje_exito', sanitize_text_field($_POST['mensaje_exito']));
        update_option('suscripcion_css', wp_strip_all_tags($_POST['css_personalizado'])); // Almacena el CSS
        echo '<div class="updated"><p>Configuraciones guardadas.</p></div>';
    }

    $boton_texto = get_option('suscripcion_boton_texto', 'Suscribirse');
    $mensaje_exito = get_option('suscripcion_mensaje_exito', '¡Registro completado exitosamente!');
    $css_personalizado = get_option('suscripcion_css', '');

    ?>
    <div class="wrap">
        <h1>Configuración de Suscripción</h1>
        <h2 class="nav-tab-wrapper">
            <a href="#tab1" class="nav-tab nav-tab-active" onclick="mostrarPestaña(event, 'tab1')">General</a>
            <a href="#tab2" class="nav-tab" onclick="mostrarPestaña(event, 'tab2')">Avanzado</a>
        </h2>
        <div id="tab1" class="tab-content" style="display: block;">
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="boton_texto">Texto del Botón</label></th>
                        <td><input type="text" id="boton_texto" name="boton_texto" value="<?php echo esc_attr($boton_texto); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="mensaje_exito">Mensaje de Éxito</label></th>
                        <td><input type="text" id="mensaje_exito" name="mensaje_exito" value="<?php echo esc_attr($mensaje_exito); ?>" class="regular-text"></td>
                    </tr>
                </table>
                <?php submit_button('Guardar Configuraciones', 'primary', 'suscripcion_guardar'); ?>
            </form>
        </div>
        <div id="tab2" class="tab-content" style="display: none;">
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="css_personalizado">CSS Personalizado</label></th>
                        <td>
                            <textarea id="css_personalizado" name="css_personalizado" rows="10" cols="50" class="large-text"><?php echo esc_textarea($css_personalizado); ?></textarea>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Guardar CSS', 'primary', 'suscripcion_guardar'); ?>
            </form>
        </div>
    </div>
    <script>
        function mostrarPestaña(event, tabId) {
            event.preventDefault();

            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.style.display = 'none');

            const tabs = document.querySelectorAll('.nav-tab');
            tabs.forEach(tab => tab.classList.remove('nav-tab-active'));

            document.getElementById(tabId).style.display = 'block';
            event.target.classList.add('nav-tab-active');
        }
    </script>
    <?php
}
