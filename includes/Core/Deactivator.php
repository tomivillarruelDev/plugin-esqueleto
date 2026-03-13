<?php
/**
 * Se ejecuta cuando el plugin se DESACTIVA (no desinstala).
 *
 * Ideal para:
 *   - Limpiar tareas programadas (wp_clear_scheduled_hook).
 *   - Regenerar rewrite rules.
 *   - Liberar recursos temporales.
 *
 * NO elimines datos del usuario aquí; eso va en uninstall.php.
 */

namespace MiPlugin\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Deactivator {

	public static function deactivate(): void {
		// Ejemplo: cancelar un cron event programado por el plugin.
		wp_clear_scheduled_hook( 'mi_plugin_cron_event' );

		flush_rewrite_rules();
	}
}
