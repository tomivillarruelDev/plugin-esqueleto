<?php
/**
 * Clase orquestadora del plugin.
 *
 * Aquí se registran TODOS los hooks (acciones y filtros).
 * No contiene lógica de negocio propia; su único trabajo es conectar
 * los distintos módulos con los eventos de WordPress.
 */

namespace MiPlugin\Core;

use MiPlugin\Admin\AdminPage;
use MiPlugin\Frontend\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	public function run(): void {
		$this->load_textdomain();
		$this->register_admin_hooks();
		$this->register_frontend_hooks();
	}

	// ── i18n ──────────────────────────────────────────────────────────────────
	// Carga las traducciones del plugin desde la carpeta /languages.
	// El Text Domain debe coincidir con el declarado en la cabecera del plugin.
	private function load_textdomain(): void {
		add_action( 'init', function () {
			load_plugin_textdomain(
				'mi-plugin',
				false,
				dirname( plugin_basename( MI_PLUGIN_FILE ) ) . '/languages'
			);
		} );
	}

	// ── Hooks del panel de administración ────────────────────────────────────
	private function register_admin_hooks(): void {
		if ( ! is_admin() ) {
			return;
		}

		$admin = new AdminPage();

		// Añade un elemento al menú lateral del panel.
		add_action( 'admin_menu',    [ $admin, 'register_menu' ] );

		// Encola CSS y JS sólo en las páginas del plugin (no en todo el admin).
		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_assets' ] );
	}

	// ── Hooks del frontend (sitio público) ───────────────────────────────────
	private function register_frontend_hooks(): void {
		$frontend = new Frontend();

		add_action( 'wp_enqueue_scripts', [ $frontend, 'enqueue_assets' ] );

		// Ejemplo: shortcode [mi_plugin]
		add_shortcode( 'mi_plugin', [ $frontend, 'render_shortcode' ] );
	}
}
