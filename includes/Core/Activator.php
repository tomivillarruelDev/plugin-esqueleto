<?php
/**
 * Se ejecuta cuando el plugin se ACTIVA desde el panel de WordPress.
 *
 * Ideal para:
 *   - Crear tablas en la BD (ver $wpdb->query con CREATE TABLE).
 *   - Registrar opciones por defecto con add_option().
 *   - Crear páginas o entradas iniciales.
 *   - Limpiar el caché de rewrite rules (flush_rewrite_rules).
 *
 * Config::option('version') → "my_plugin_version"  (derivado del slug)
 * Config::version()         → "1.0.0"               (desde plugin.config.php)
 */

declare( strict_types=1 );

namespace MyPlugin\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Activator {

	public static function activate(): void {
		$installed_version = get_option( Config::option( 'version' ) );
		$current_version   = Config::version();

		if ( ! $installed_version ) {
			add_option( Config::option( 'version' ), $current_version );
		} elseif ( version_compare( (string) $installed_version, $current_version, '<' ) ) {
			update_option( Config::option( 'version' ), $current_version );
		}

		flush_rewrite_rules();
	}
}
