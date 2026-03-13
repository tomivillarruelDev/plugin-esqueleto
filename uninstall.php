<?php
/**
 * Se ejecuta cuando el usuario ELIMINA el plugin desde el panel de WP
 * (Plugins → Eliminar), no cuando lo desactiva.
 *
 * Aquí sí corresponde borrar datos permanentes:
 *   - Tablas propias en la base de datos.
 *   - Opciones guardadas con add_option / update_option.
 *   - Archivos subidos por el plugin.
 *
 * WordPress sólo ejecuta este archivo si:
 *   1. El plugin está desactivado.
 *   2. Se definió WP_UNINSTALL_PLUGIN (lo hace WP automáticamente).
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit; // Impide ejecución directa.
}

// Elimina las opciones del plugin de la tabla wp_options.
delete_option( 'mi_plugin_version' );
delete_option( 'mi_plugin_settings' );

// Si el plugin usara tablas propias, aquí iría el DROP TABLE.
// global $wpdb;
// $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mi_plugin_datos" );
