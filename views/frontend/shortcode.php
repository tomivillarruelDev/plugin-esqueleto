<?php
/**
 * Vista del shortcode [mi_plugin].
 *
 * Las variables disponibles son las que vienen de $atts en Frontend.php:
 *   $atts['titulo']
 *   $atts['color']
 *
 * Siempre escapar los valores al imprimir para evitar XSS.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="my-plugin-wrapper" style="color: <?php echo esc_attr( $atts['color'] ); ?>;">
	<h2><?php echo esc_html( $atts['title'] ); ?></h2>
	<?php if ( $content ) : ?>
		<div class="my-plugin-content">
			<?php echo wp_kses_post( $content ); ?>
		</div>
	<?php endif; ?>
</div>
