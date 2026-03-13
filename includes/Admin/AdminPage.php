<?php
/**
 * Panel de administración del plugin.
 *
 *  - register_menu()  : registra la entrada en el menú lateral del wp-admin.
 *  - enqueue_assets() : encola CSS y JS SÓLO en la página del plugin.
 *  - render_page()    : incluye la vista HTML separada de la lógica.
 *
 * Todos los identificadores (slug de menú, handles, nonce, objeto JS)
 * se obtienen de Config para evitar strings hardcodeados.
 */

namespace MiPlugin\Admin;

use MiPlugin\Core\Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AdminPage {

	/**
	 * Registra la página en el menú lateral del wp-admin.
	 * Hook: admin_menu
	 *
	 * Config::menu_slug() → "mi-plugin-settings"
	 * Config::name()      → "Mi Plugin"
	 */
	public function register_menu(): void {
		add_menu_page(
			Config::name(),            // Título de pestaña del navegador
			Config::name(),            // Texto en el menú lateral
			'manage_options',          // Capacidad mínima requerida
			Config::menu_slug(),       // Slug único de la página
			[ $this, 'render_page' ],  // Callback que dibuja el HTML
			'dashicons-admin-plugins', // Icono (Dashicon o URL de imagen)
			80                         // Posición en el menú
		);
	}

	/**
	 * Encola CSS y JS SÓLO cuando el usuario está en la página del plugin.
	 * Hook: admin_enqueue_scripts
	 *
	 * Config::asset('admin')  → handle "mi-plugin-admin"
	 * Config::js_object()     → nombre del objeto JS "MiPluginAdmin"
	 * Config::nonce('admin')  → action del nonce "mi_plugin_admin"
	 *
	 * @param string $hook Slug de la página actual del wp-admin.
	 */
	public function enqueue_assets( string $hook ): void {
		if ( 'toplevel_page_' . Config::menu_slug() !== $hook ) {
			return;
		}

		wp_enqueue_style(
			Config::asset( 'admin' ),
			Config::url() . 'assets/css/admin.css',
			[],
			Config::version()
		);

		wp_enqueue_script(
			Config::asset( 'admin' ),
			Config::url() . 'assets/js/admin.js',
			[ 'jquery' ],
			Config::version(),
			true // Cargar en el footer para no bloquear el render
		);

		// Inyecta variables PHP → JS de forma segura.
		// El nombre del objeto (Config::js_object()) debe coincidir
		// con la referencia en assets/js/admin.js.
		wp_localize_script(
			Config::asset( 'admin' ),
			Config::js_object(),
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( Config::nonce( 'admin' ) ),
			]
		);
	}

	/**
	 * Renderiza la vista HTML del panel.
	 * Verifica permisos antes de mostrar cualquier contenido (hardening).
	 */
	public function render_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'No tenés permisos para ver esta página.', Config::text_domain() ) );
		}

		include Config::dir() . 'views/admin/settings-page.php';
	}
}
