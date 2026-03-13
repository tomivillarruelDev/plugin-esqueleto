<?php
/**
 * Clase orquestadora del plugin.
 *
 * Responsabilidad única: registrar todos los hooks de WordPress.
 * No contiene lógica de negocio; conecta los módulos con los eventos de WP.
 * Todos los identificadores (text domain, tag del shortcode) vienen de Config.
 */

declare( strict_types=1 );

namespace MiPlugin\Core;

use MiPlugin\Admin\AdminPage;
use MiPlugin\Frontend\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	public function run(): void {
		$this->load_textdomain();
		$this->register_core_hooks();
		$this->register_admin_hooks();
		$this->register_frontend_hooks();
	}

	// ── i18n ──────────────────────────────────────────────────────────────────
	private function load_textdomain(): void {
		add_action( 'init', static function (): void {
			load_plugin_textdomain(
				Config::text_domain(),
				false,
				dirname( plugin_basename( Config::file() ) ) . '/languages'
			);
		} );
	}

	// ── Hooks ──────────────────────────────────────────────────────────────────
	private function register_core_hooks(): void {
		( new Ajax() )->register();

		if ( is_admin() ) {
			add_action( 'admin_init', [ new Settings(), 'register' ] );
		}
	}

	private function register_admin_hooks(): void {
		$admin = new AdminPage();
		add_action( 'admin_menu',            [ $admin, 'register_menu'  ] );
		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_assets' ] );
	}

	// ── Hooks del frontend (sitio público) ────────────────────────────────────
	// Config::shortcode() devuelve el tag del shortcode en snake_case.
	// Ej: "mi_plugin" → [mi_plugin titulo="Hola"]
	private function register_frontend_hooks(): void {
		$frontend = new Frontend();
		add_action( 'wp_enqueue_scripts', [ $frontend, 'register_assets' ] );

		// El registro de shortcodes es preferible hacerlo en 'init'.
		add_action( 'init', static function () use ( $frontend ): void {
			add_shortcode( Config::shortcode(), [ $frontend, 'render_shortcode' ] );
		} );
	}
}

