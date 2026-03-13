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
 * Config::cron_hook('cron_event') → "my_plugin_cron_event"
 */

declare( strict_types=1 );

namespace MyPlugin\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Deactivator {

	public static function deactivate(): void {
		flush_rewrite_rules();
	}
}
