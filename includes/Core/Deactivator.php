<?php
/**
 * Se ejecuta cuando el plugin se DESACTIVA (no cuando se desinstala).
 *
 * Ideal para:
 *   - Cancelar tareas programadas (wp_clear_scheduled_hook).
 *   - Regenerar rewrite rules.
 *   - Liberar recursos temporales.
 *
 * NO eliminar datos del usuario aquí; eso corresponde a uninstall.php.
 *
 * Config::cron_hook('cron_event') → "mi_plugin_cron_event"
 */

namespace MiPlugin\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Deactivator {

	public static function deactivate(): void {
		// Ejemplo: cancelar tareas programadas si el plugin las usa.
		// wp_clear_scheduled_hook( Config::cron_hook( 'cron_event' ) );

		flush_rewrite_rules();
	}
}
