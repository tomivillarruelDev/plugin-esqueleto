<?php
/**
 * Plugin Name:       My Plugin
 * Plugin URI:        https://mysitioweb.com/my-plugin
 * Description:       Descripción breve de lo que hace el plugin.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Your Name
 * Author URI:        https://mysitioweb.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-plugin
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

// ── 1. Carga de Config ────────────────────────────────────────────────────────
require_once __DIR__ . '/includes/Core/Config.php';

\MyPlugin\Core\Config::init(
	require __DIR__ . '/plugin.config.php',
	__FILE__
);

// ── 2. Autoloader PSR-4 ────────────────────────────────────────────────────────
spl_autoload_register( static function ( string $class ): void {
	$namespace_root = 'MyPlugin\\';
	$base_dir       = \MyPlugin\Core\Config::dir() . 'includes/';
	$len            = strlen( $namespace_root );

	if ( strncmp( $namespace_root, $class, $len ) !== 0 ) {
		return;
	}

	$file = $base_dir . str_replace( '\\', '/', substr( $class, $len ) ) . '.php';

	if ( file_exists( $file ) ) {
		try {
			require $file;
		} catch ( \Throwable $e ) {
			error_log( sprintf( '[MyPlugin Autoloader] Error cargando %s: %s', $file, $e->getMessage() ) );
		}
	}
} );

// ── 3. Lifecycle Hooks ─────────────────────────────────────────────────────────
register_activation_hook(   __FILE__, [ \MyPlugin\Core\Activator::class,   'activate'   ] );
register_deactivation_hook( __FILE__, [ \MyPlugin\Core\Deactivator::class, 'deactivate' ] );

// ── 4. Boot ────────────────────────────────────────────────────────────────────
add_action( 'plugins_loaded', static function (): void {
	( new \MyPlugin\Core\Plugin() )->run();
} );
