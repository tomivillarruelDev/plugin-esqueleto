<?php
/**
 * Frontend del plugin (sitio público).
 *
 *  - register_assets()  : registra CSS/JS pero NO los encola todavía.
 *  - render_shortcode() : procesa el shortcode y encola los assets sólo si se usa.
 */

declare( strict_types=1 );

namespace MiPlugin\Frontend;

use MiPlugin\Core\Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Frontend {

	/**
	 * Flag para evitar encolar assets múltiples veces si hay varios shortcodes.
	 */
	private static bool $assets_enqueued = false;

	/**
	 * Registra CSS y JS en el frontend sin cargarlos aún.
	 * Hook: wp_enqueue_scripts
	 */
	public function register_assets(): void {
		wp_register_style(
			Config::asset( 'public' ),
			Config::url() . 'assets/css/public.css',
			[],
			Config::version()
		);

		wp_register_script(
			Config::asset( 'public' ),
			Config::url() . 'assets/js/public.js',
			[ 'jquery' ],
			Config::version(),
			true
		);
	}

	/**
	 * Procesa el shortcode y encola los assets bajo demanda.
	 *
	 * @param array|string $atts    Atributos del shortcode.
	 * @param string       $content Contenido encerrado.
	 * @return string               HTML resultante.
	 */
	public function render_shortcode( $atts, string $content = '' ): string {
		// Encolar assets registrados sólo cuando el shortcode está presente en la página.
		if ( ! self::$assets_enqueued ) {
			wp_enqueue_style( Config::asset( 'public' ) );
			wp_enqueue_script( Config::asset( 'public' ) );
			self::$assets_enqueued = true;
		}

		$atts = shortcode_atts(
			[
				'titulo' => __( 'Hola desde Mi Plugin', Config::text_domain() ),
				'color'  => '#000000',
			],
			$atts,
			Config::shortcode()
		);

		// Sanitización de atributos
		$atts['titulo'] = sanitize_text_field( $atts['titulo'] );
		$atts['color']  = sanitize_hex_color( $atts['color'] ) ?: '#000000';

		ob_start();
		include Config::dir() . 'views/frontend/shortcode.php';
		return ob_get_clean();
	}
}
