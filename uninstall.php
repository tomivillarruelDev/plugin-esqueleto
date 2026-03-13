<?php
/**
 * Se ejecuta cuando el usuario ELIMINA el plugin (Plugins → Eliminar).
 * NO se ejecuta al desactivar.
 *
 * Aquí corresponde borrar datos permanentes:
 *   - Opciones en wp_options.
 *   - Tablas propias creadas por el plugin.
 *   - Archivos subidos por el plugin.
 *
 * WordPress ejecuta este archivo sólo si WP_UNINSTALL_PLUGIN está definido.
 *
 * NOTA: Este archivo corre en AISLAMIENTO (sin pasar por plugin.php),
 * por eso se cargan Config y plugin.config.php con require_once manual.
 */

declare( strict_types=1 );

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Carga de configuración
require_once __DIR__ . '/includes/Core/Config.php';
\MyPlugin\Core\Config::init(
	require __DIR__ . '/plugin.config.php',
	__FILE__
);

// Eliminar opciones del plugin
delete_option( \MyPlugin\Core\Config::option( 'version' ) );
delete_option( \MyPlugin\Core\Config::option( 'settings' ) );

// Si el plugin creó tablas propias, eliminarlas aquí:
// global $wpdb;
// $wpdb->query(
//     'DROP TABLE IF EXISTS ' . $wpdb->prefix . \MyPlugin\Core\Config::prefix() . '_datos'
// );
