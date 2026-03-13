/**
 * JavaScript del panel de administración.
 *
 * La variable global `MiPluginAdmin` es inyectada desde PHP con wp_localize_script()
 * y contiene:
 *   - MiPluginAdmin.ajax_url : URL del endpoint AJAX de WordPress
 *   - MiPluginAdmin.nonce    : Token de seguridad para validar peticiones AJAX
 */

(function ($, config) {
  "use strict";

  $(document).ready(function () {
    console.log("Mi Plugin Admin listo.");

    // Ejemplo de petición AJAX al backend de WordPress.
    // $.post( config.ajax_url, {
    //     action: 'mi_plugin_accion',   // Debe coincidir con el hook wp_ajax_*
    //     nonce:  config.nonce,
    //     dato:   'valor'
    // }, function ( response ) {
    //     console.log( response );
    // } );
  });
})(jQuery, MiPluginAdmin);
