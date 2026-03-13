<?php
/**
 * Clase orquestadora del plugin.
 *
 * Responsabilidad única: registrar todos los hooks de WordPress.
 * No contiene lógica de negocio; conecta los módulos con los eventos de WP.
 * Todos los identificadores (text domain, tag del shortcode) vienen de Config.
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
	// Config::text_domain() devuelve el slug (= text domain por convención WP).
	// Config::file() provee la ruta al archivo principal para plugin_basename().
	private function load_textdomain(): void {
		add_action( 'init', static function (): void {
			load_plugin_textdomain(
				Config::text_domain(),
				false,
				dirname( plugin_basename( Config::file() ) ) . '/languages'
			);
		} );
	}

	// ── Hooks del panel de administración ─────────────────────────────────────
	private function register_admin_hooks(): void {
		if ( ! is_admin() ) {
			return;
		}

		$admin = new AdminPage();
		add_action( 'admin_menu',            [ $admin, 'register_menu'  ] );
		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_assets' ] );
	}

	// ── Hooks del frontend (sitio público) ────────────────────────────────────
	// Config::shortcode() devuelve el tag del shortcode en snake_case.
	// Ej: "mi_plugin" → [mi_plugin titulo="Hola"]
	private function register_frontend_hooks(): void {
		$frontend = new Frontend();
		add_action( 'wp_enqueue_scripts', [ $frontend, 'enqueue_assets'   ] );
		add_shortcode( Config::shortcode(), [ $frontend, 'render_shortcode' ] );
	}
}

