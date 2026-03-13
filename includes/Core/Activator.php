<?php
/**
 * Se ejecuta cuando el plugin se ACTIVA desde el panel de WordPress.
 *
 * Ideal para:
 *   - Crear tablas en la base de datos.
 *   - Registrar opciones por defecto con add_option().
 *   - Crear páginas o entradas iniciales.
 *   - Limpiar el caché de rewrite rules (flush_rewrite_rules).
 */

namespace MiPlugin\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Activator {

	public static function activate(): void {
		// Ejemplo: guardar versión en la base de datos.
		if ( ! get_option( 'mi_plugin_version' ) ) {
			add_option( 'mi_plugin_version', MI_PLUGIN_VERSION );
		}

		// Regenera las reglas de URL amigables para evitar errores 404
		// si el plugin registra Custom Post Types o taxonomías.
		flush_rewrite_rules();
	}
}
