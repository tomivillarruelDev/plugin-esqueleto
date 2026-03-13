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
 * Config::option('version') → "mi_plugin_version"  (derivado del slug)
 * Config::version()         → "1.0.0"               (desde plugin.config.php)
 */

namespace MiPlugin\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Activator {

	public static function activate(): void {
		if ( ! get_option( Config::option( 'version' ) ) ) {
			add_option( Config::option( 'version' ), Config::version() );
		}

		// Regenera las reglas de URL amigables para evitar errores 404
		// si el plugin registra Custom Post Types o taxonomías.
		flush_rewrite_rules();
	}
}
