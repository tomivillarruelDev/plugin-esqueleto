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
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * PLANTILLA DE PLUGIN — EON
 * ─────────────────────────────────────────────────────────────────────────────
 * Para crear un nuevo plugin a partir de esta plantilla:
 *
 *   1. Editá plugin.config.php (slug, name, version).
 *   2. Ejecutá desde la raíz:
 *        php bin/rename-plugin.php <slug> <Namespace> "<Nombre>"
 *      Ej: php bin/rename-plugin.php eon-reservas EonReservas "EON Reservas"
 *   3. Copiá la carpeta a wp-content/plugins/<slug>/ y activá el plugin.
 * ─────────────────────────────────────────────────────────────────────────────
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── 1. Cargar Config manualmente (antes del autoloader) ───────────────────────
// Config.php se requiere con require_once porque el autoloader aún no está
// registrado. Es el único archivo que necesita carga manual explícita.
require_once __DIR__ . '/includes/Core/Config.php';

\MiPlugin\Core\Config::init(
	require __DIR__ . '/plugin.config.php', // Array de configuración
	__FILE__                                 // Ruta del archivo principal
);

// ── 2. Autoloader PSR-4 ────────────────────────────────────────────────────────
// Mapea MiPlugin\Algo\Clase → includes/Algo/Clase.php
//
// Al crear un nuevo plugin, bin/rename-plugin.php actualiza 'MiPlugin\\'
// y \MiPlugin\Core\Config por el nuevo namespace automáticamente.
spl_autoload_register( static function ( string $class ): void {
	$namespace_root = 'MiPlugin\\';
	$base_dir       = \MiPlugin\Core\Config::dir() . 'includes/';
	$len            = strlen( $namespace_root );

	if ( strncmp( $namespace_root, $class, $len ) !== 0 ) {
		return;
	}

	$file = $base_dir . str_replace( '\\', '/', substr( $class, $len ) ) . '.php';

	if ( file_exists( $file ) ) {
		try {
			require $file;
		} catch ( \Throwable $e ) {
			error_log( sprintf( '[MiPlugin Autoloader] Error cargando %s: %s', $file, $e->getMessage() ) );
		}
	}
} );

// ── 3. Hooks del ciclo de vida ─────────────────────────────────────────────────
// Se usa ::class en lugar de strings para que bin/rename-plugin.php actualice
// los nombres de clase automáticamente al reemplazar el namespace.
register_activation_hook(   __FILE__, [ \MiPlugin\Core\Activator::class,   'activate'   ] );
register_deactivation_hook( __FILE__, [ \MiPlugin\Core\Deactivator::class, 'deactivate' ] );
// La desinstalación permanente se gestiona en uninstall.php.

// ── 4. Arranque ────────────────────────────────────────────────────────────────
// plugins_loaded garantiza que WP y todos los plugins estén listos.
add_action( 'plugins_loaded', static function (): void {
	( new \MiPlugin\Core\Plugin() )->run();
} );
