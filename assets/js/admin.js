/**
 * JavaScript del panel de administración.
 *
 * El objeto `MiPluginAdmin` es inyectado desde PHP con wp_localize_script()
 * a través de AdminPage::enqueue_assets() → Config::js_object().
 *
 *   MiPluginAdmin.ajax_url  → URL del endpoint AJAX de WordPress
 *   MiPluginAdmin.nonce     → Token de seguridad para peticiones AJAX
 *
 * IMPORTANTE: el nombre `MiPluginAdmin` se sincroniza automáticamente con
 * Config::js_object() al correr bin/rename-plugin.php.
 */

( function ( $, config ) {
	'use strict';

	$( document ).ready( function () {

		// Ejemplo de petición AJAX al backend de WordPress.
		// $.post( config.ajax_url, {
		//     action: 'mi_plugin_accion',   // Debe coincidir con wp_ajax_{action}
		//     nonce:  config.nonce,
		//     dato:   'valor'
		// } ).done( function ( response ) {
		//     console.log( response );
		// } );

	} );

} )( jQuery, MiPluginAdmin );
