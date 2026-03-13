<?php
/**
 * Plugin Name:       Mi Plugin
 * Plugin URI:        https://misitioweb.com/mi-plugin
 * Description:       Descripción breve de lo que hace el plugin.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Tu Nombre
 * Author URI:        https://misitioweb.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mi-plugin
 * Domain Path:       /languages
 */

// Bloquea el acceso directo al archivo fuera de WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ─── Constantes del plugin ───────────────────────────────────────────────────
// Facilitan reutilizar rutas y URLs en todo el código sin hardcodearlas.

define( 'MI_PLUGIN_VERSION', '1.0.0' );
define( 'MI_PLUGIN_FILE',    __FILE__ );
define( 'MI_PLUGIN_DIR',     plugin_dir_path( __FILE__ ) );   // Ruta absoluta en el servidor
define( 'MI_PLUGIN_URL',     plugin_dir_url( __FILE__ ) );    // URL pública del plugin

// ─── Autoloader ──────────────────────────────────────────────────────────────
// Carga automáticamente las clases PHP según el estándar PSR-4 sin necesidad
// de escribir un require/include por cada archivo.

spl_autoload_register( function ( $class ) {
	$prefix    = 'MiPlugin\\';
	$base_dir  = MI_PLUGIN_DIR . 'includes/';
	$len       = strlen( $prefix );

	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return; // La clase no pertenece a este plugin, se ignora.
	}

	$relative_class = substr( $class, $len );
	$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}
} );

// ─── Hooks de activación / desactivación / desinstalación ────────────────────
// WordPress los dispara en momentos clave del ciclo de vida del plugin.

register_activation_hook(   __FILE__, [ 'MiPlugin\\Core\\Activator',   'activate'   ] );
register_deactivation_hook( __FILE__, [ 'MiPlugin\\Core\\Deactivator', 'deactivate' ] );
// La desinstalación se gestiona en uninstall.php (ver ese archivo).

// ─── Arranque del plugin ─────────────────────────────────────────────────────
// Se usa el hook "plugins_loaded" para asegurarse de que WordPress y todos los
// demás plugins están listos antes de inicializar el nuestro.

add_action( 'plugins_loaded', function () {
	$plugin = new MiPlugin\Core\Plugin();
	$plugin->run();
} );
