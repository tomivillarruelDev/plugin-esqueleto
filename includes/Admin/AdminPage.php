<?php
/**
 * Todo lo relacionado con el panel de administración:
 *   - Registro de páginas de opciones en el menú.
 *   - Carga de scripts y estilos del admin.
 *   - Renderizado de la vista HTML del panel.
 */

namespace MiPlugin\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AdminPage {

	// Slug único que identifica la página dentro del panel de WP.
	private const MENU_SLUG = 'mi-plugin-settings';

	/**
	 * Registra la página en el menú lateral.
	 * Hook: admin_menu
	 */
	public function register_menu(): void {
		add_menu_page(
			__( 'Mi Plugin', 'mi-plugin' ),          // Título de la pestaña del navegador
			__( 'Mi Plugin', 'mi-plugin' ),          // Texto en el menú lateral
			'manage_options',                         // Capacidad mínima para ver la página
			self::MENU_SLUG,                          // Slug único de la página
			[ $this, 'render_page' ],                 // Función que dibuja el HTML
			'dashicons-admin-plugins',                // Icono del menú (Dashicon o URL)
			80                                        // Posición en el menú
		);
	}

	/**
	 * Encola los assets sólo cuando estamos en la página del plugin.
	 * Hook: admin_enqueue_scripts
	 *
	 * @param string $hook Slug de la página actual del admin.
	 */
	public function enqueue_assets( string $hook ): void {
		// Evita cargar assets en páginas ajenas al plugin.
		if ( 'toplevel_page_' . self::MENU_SLUG !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'mi-plugin-admin',
			MI_PLUGIN_URL . 'assets/css/admin.css',
			[],
			MI_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'mi-plugin-admin',
			MI_PLUGIN_URL . 'assets/js/admin.js',
			[ 'jquery' ],          // Dependencias
			MI_PLUGIN_VERSION,
			true                   // Cargar en el footer para mejor rendimiento
		);

		// Pasa variables de PHP a JavaScript de forma segura.
		wp_localize_script( 'mi-plugin-admin', 'MiPluginAdmin', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'mi_plugin_nonce' ),
		] );
	}

	/**
	 * Renderiza la vista HTML del panel de administración.
	 * Usa include para separar la lógica del HTML.
	 */
	public function render_page(): void {
		// Verifica permisos antes de mostrar cualquier contenido.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'No tenés permisos para ver esta página.', 'mi-plugin' ) );
		}

		include MI_PLUGIN_DIR . 'views/admin/settings-page.php';
	}
}
