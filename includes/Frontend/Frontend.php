<?php
/**
 * Frontend del plugin (sitio público).
 *
 *  - enqueue_assets()   : encola CSS y JS en todas las páginas públicas.
 *  - render_shortcode() : procesa el shortcode y devuelve HTML.
 *
 * Para cargar assets sólo en páginas específicas, condicioná enqueue_assets()
 * con is_page(), is_singular(), has_shortcode(), etc.
 */

namespace MiPlugin\Frontend;

use MiPlugin\Core\Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Frontend {

	/**
	 * Encola CSS y JS en el frontend.
	 * Hook: wp_enqueue_scripts
	 *
	 * Config::asset('public') → handle "mi-plugin-public"
	 */
	public function enqueue_assets(): void {
		wp_enqueue_style(
			Config::asset( 'public' ),
			Config::url() . 'assets/css/public.css',
			[],
			Config::version()
		);

		wp_enqueue_script(
			Config::asset( 'public' ),
			Config::url() . 'assets/js/public.js',
			[ 'jquery' ],
			Config::version(),
			true
		);
	}

	/**
	 * Procesa el shortcode registrado en Plugin::register_frontend_hooks().
	 *
	 * Tag del shortcode: Config::shortcode() → "mi_plugin"
	 * Uso:               [mi_plugin titulo="Hola" color="#e44d26"]
	 *
	 * WordPress reemplaza el shortcode en el contenido del post
	 * por el string que retorna esta función (nunca echo directo).
	 *
	 * @param array|string $atts    Atributos del shortcode (WP puede pasar string vacío).
	 * @param string       $content Contenido encerrado (shortcode de bloque).
	 * @return string               HTML resultante.
	 */
	public function render_shortcode( $atts, string $content = '' ): string {
		$atts = shortcode_atts(
			[
				'titulo' => __( 'Hola desde Mi Plugin', Config::text_domain() ),
				'color'  => '#000000',
			],
			$atts,
			Config::shortcode()
		);

		ob_start();
		include Config::dir() . 'views/frontend/shortcode.php';
		return ob_get_clean();
	}
}
