<?php
/**
 * Vista HTML del panel de configuración.
 *
 * Este archivo es un template puro: sólo HTML + PHP de presentación.
 * La lógica está en AdminPage.php.
 *
 * WordPress recomienda usar la Settings API para formularios de opciones;
 * las funciones settings_fields() y do_settings_sections() generan
 * automáticamente el nonce y el botón de guardar.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form method="post" action="options.php">
		<?php
		// Genera campos ocultos de seguridad (nonce, action, option_page).
		settings_fields( 'mi_plugin_options_group' );

		// Dibuja las secciones y campos registrados con add_settings_section()
		// y add_settings_field().
		do_settings_sections( 'mi-plugin-settings' );

		// Botón guardar con estilo nativo de WordPress.
		submit_button( __( 'Guardar cambios', 'mi-plugin' ) );
		?>
	</form>
</div>
