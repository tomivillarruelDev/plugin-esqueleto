<?php
/**
 * Vista del shortcode [my_plugin]..
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// ── VISTA DEL SHORTCODE ───────────────────────────────────────────────────────
// Este archivo es el HTML que se renderiza cuando el shortcode se ejecuta.
// Podés modificar o reemplazar todo el contenido de abajo según tu necesidad.
//
// Variables disponibles (vienen de Frontend::render_shortcode()):
//   $atts['title'] → título del shortcode
//   $atts['color'] → color en formato hex
//   $content       → contenido encerrado entre las etiquetas del shortcode
//
// Ejemplos de uso:
//   - Mostrar un template de Elementor:
//       echo do_shortcode( '[elementor-template id="24637"]' );
//   - Mostrar HTML propio con los atributos del shortcode
//   - Combinar ambos según lógica condicional en Frontend.php
// ─────────────────────────────────────────────────────────────────────────────
?>
<div class="my-plugin-wrapper" style="color: <?php echo esc_attr( $atts['color'] ); ?>;">
	<h2><?php echo esc_html( $atts['title'] ); ?></h2>
	<?php if ( $content ) : ?>
		<div class="my-plugin-content">
			<?php echo wp_kses_post( $content ); ?>
		</div>
	<?php endif; ?>
</div>
