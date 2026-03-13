<?php
/**
 * Vista HTML del panel de configuración.
 *
 * Template puro: sólo HTML + PHP de presentación. La lógica en AdminPage.php.
 *
 * Config::options_group() → "mi_plugin_options_group"
 * Config::menu_slug()     → "mi-plugin-settings"
 * Config::text_domain()   → "mi-plugin"
 */

use MiPlugin\Core\Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form method="post" action="options.php">
		<?php
		// Genera los campos ocultos de seguridad (nonce, action, option_page).
		settings_fields( Config::options_group() );

		// Dibuja las secciones y campos registrados con add_settings_section()
		// y add_settings_field() (típicamente en una clase Settings separada).
		do_settings_sections( Config::menu_slug() );

		// Botón "Guardar cambios" con estilo nativo del wp-admin.
		submit_button( __( 'Guardar cambios', Config::text_domain() ) );
		?>
	</form>
</div>
