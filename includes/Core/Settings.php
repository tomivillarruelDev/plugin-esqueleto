<?php
/**
 * Clase para gestionar ajustes del plugin usando la Settings API.
 */

declare( strict_types=1 );

namespace MyPlugin\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings {

	/**
	 * Registra los ajustes, secciones y campos.
	 * Hook: admin_init
	 */
	public function register(): void {
		// 1. Registrar ajuste central
		register_setting(
			Config::options_group(),
			Config::option( 'settings' ),
			[
				'type'              => 'array',
				'sanitize_callback' => [ $this, 'sanitize_settings' ],
				'default'           => [],
			]
		);

		// 2. Agregar sección
		add_settings_section(
			Config::prefix() . '_section_general',
			__( 'Ajustes Generales', Config::text_domain() ),
			[ $this, 'render_section_info' ],
			Config::menu_slug()
		);

		// 3. Agregar campo
		add_settings_field(
			'example_field',
			__( 'Campo de Ejemplo', Config::text_domain() ),
			[ $this, 'render_example_field' ],
			Config::menu_slug(),
			Config::prefix() . '_section_general'
		);
	}

	/**
	 * Callback para sanitizar los ajustes.
	 *
	 * @param array $input Valores enviados desde el formulario.
	 * @return array       Valores sanitizados.
	 */
	public function sanitize_settings( array $input ): array {
		$sanitized = [];

		if ( isset( $input['example_field'] ) ) {
			$sanitized['example_field'] = sanitize_text_field( $input['example_field'] );
		}

		return $sanitized;
	}

	/**
	 * Info descriptiva de la sección.
	 */
	public function render_section_info(): void {
		echo '<p>' . esc_html__( 'Configurá los aspectos básicos del plugin acá.', Config::text_domain() ) . '</p>';
	}

	/**
	 * Dibuja el input del campo de ejemplo.
	 */
	public function render_example_field(): void {
		$options = get_option( Config::option( 'settings' ) );
		$val     = $options['example_field'] ?? '';

		printf(
			'<input type="text" name="%s[example_field]" value="%s" class="regular-text" />',
			esc_attr( Config::option( 'settings' ) ),
			esc_attr( $val )
		);
	}
}
