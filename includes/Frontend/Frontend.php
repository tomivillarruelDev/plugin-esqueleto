<?php
/**
 * Lógica del frontend (parte pública del sitio).
 *   - Encola los assets del tema/visita.
 *   - Procesa y devuelve el HTML del shortcode.
 */

namespace MiPlugin\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Frontend {

	/**
	 * Encola CSS y JS en el frontend.
	 * Hook: wp_enqueue_scripts
	 */
	public function enqueue_assets(): void {
		wp_enqueue_style(
			'mi-plugin-public',
			MI_PLUGIN_URL . 'assets/css/public.css',
			[],
			MI_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'mi-plugin-public',
			MI_PLUGIN_URL . 'assets/js/public.js',
			[ 'jquery' ],
			MI_PLUGIN_VERSION,
			true
		);
	}

	/**
	 * Procesa el shortcode [mi_plugin atributo="valor"].
	 *
	 * WordPress reemplaza el shortcode en el contenido del post
	 * por lo que devuelva esta función.
	 *
	 * @param array  $atts    Atributos pasados en el shortcode.
	 * @param string $content Contenido encerrado (si el shortcode es de bloque).
	 * @return string         HTML resultante.
	 */
	public function render_shortcode( $atts, string $content = '' ): string {
		// shortcode_atts combina los atributos recibidos con los valores por defecto.
		$atts = shortcode_atts(
			[
				'titulo' => __( 'Hola desde Mi Plugin', 'mi-plugin' ),
				'color'  => '#000000',
			],
			$atts,
			'mi_plugin'
		);

		ob_start(); // Captura el HTML en lugar de imprimirlo directamente.
		include MI_PLUGIN_DIR . 'views/frontend/shortcode.php';
		return ob_get_clean();
	}
}
