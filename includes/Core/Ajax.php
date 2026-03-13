<?php
/**
 * Clase para gestionar peticiones AJAX.
 */

declare( strict_types=1 );

namespace MyPlugin\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ajax {

	/**
	 * Registra los hooks de AJAX.
	 */
	public function register(): void {
		// Ejemplo de acción AJAX: my_plugin_save_data
		// Config::prefix() . '_save_data'
		$action = Config::prefix() . '_example_action';

		add_action( 'wp_ajax_' . $action,        [ $this, 'handle_example' ] );
		add_action( 'wp_ajax_nopriv_' . $action, [ $this, 'handle_example' ] );
	}

	/**
	 * Handler de ejemplo para una petición AJAX.
	 */
	public function handle_example(): void {
		// 1. Verificación de seguridad (Nonce)
		check_ajax_referer( Config::nonce( 'admin' ), 'nonce' );

		// 2. Verificación de permisos (opcional, según la acción)
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => 'Sin permisos' ], 403 );
		}

		// 3. Procesamiento de datos
		$data = isset( $_POST['data'] ) ? sanitize_text_field( wp_unslash( $_POST['data'] ) ) : '';

		// 4. Respuesta
		wp_send_json_success( [
			'message' => 'Datos recibidos correctamente',
			'echo'    => $data,
		] );
	}
}
