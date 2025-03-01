<?php

// Agregar el menú principal al administrador
function caja_suscripcion_agregar_menu_principal()
{
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

// Generar la página de configuración
function caja_suscripcion_pagina_configuracion()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    // Guardar configuraciones si se envían datos
    caja_suscripcion_guardar_configuracion();

    // Obtener configuraciones actuales
    $config = caja_suscripcion_obtener_configuraciones();
?>
    <div class="wrap">
        <h1>Configuración de Suscripción</h1>
        <h2 class="nav-tab-wrapper">
            <a href="#tab1" class="nav-tab nav-tab-active" onclick="mostrarPestaña(event, 'tab1')">General</a>
            <a href="#tab2" class="nav-tab" onclick="mostrarPestaña(event, 'tab2')">Avanzado</a>
            <a href="#tab4" class="nav-tab" onclick="mostrarPestaña(event, 'tab4')">Tabla</a>
        </h2>
        <div id="tab1" class="tab-content" style="display: block;">
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th><label for="boton_texto">Texto del Botón</label></th>
                        <td><input type="text" id="boton_texto" name="boton_texto" value="<?php echo esc_attr($config['boton_texto']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="mensaje_exito">Mensaje de Éxito</label></th>
                        <td><input type="text" id="mensaje_exito" name="mensaje_exito" value="<?php echo esc_attr($config['mensaje_exito']); ?>" class="regular-text"></td>
                    </tr>
                </table>
                <?php submit_button('Guardar Configuraciones', 'primary', 'suscripcion_guardar'); ?>
            </form>
        </div>
        <div id="tab2" class="tab-content" style="display: none;">
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th><label for="css_personalizado">CSS Personalizado</label></th>
                        <td><textarea id="css_personalizado" name="css_personalizado" rows="10" cols="50" class="large-text"><?php echo esc_textarea($config['css_personalizado']); ?></textarea></td>
                    </tr>
                </table>
                <?php submit_button('Guardar CSS', 'primary', 'suscripcion_guardar'); ?>
            </form>
        </div>
        <div id="tab4" class="tab-content" style="display: none;">
            <h2>Usuarios con el rol "Suscriptor"</h2>

            <!-- Contenedor del grid -->
            <table class="widefat" id="tabla-suscriptores-ajax">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Fecha de Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" style="text-align: center;">Cargando usuarios...</td>
                    </tr>
                </tbody>
            </table>

            <!-- Controles de paginación -->
            <div class="pagination" id="paginacion-suscriptores">
                <!-- Los botones de paginación se generan dinámicamente -->
            </div>
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
