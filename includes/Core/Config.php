<?php
/**
 * Clase de configuración centralizada del plugin.
 *
 * Responsabilidad única: derivar todos los identificadores del plugin
 * (handles de assets, claves de BD, slugs de menú, text domain, nonces,
 * cron hooks, objeto JS) a partir del slug definido en plugin.config.php.
 *
 * Esta clase se carga MANUALMENTE (require_once) en plugin.php y en
 * uninstall.php antes de que el autoloader esté activo.
 *
 * ── TABLA DE DERIVACIONES ────────────────────────────────────────────────────
 *  slug       → "my-plugin"      (configurado en plugin.config.php)
 *  prefix     → "my_plugin"      (slug con guiones reemplazados por _)
 *  text_domain→ "my-plugin"      (= slug, convención de WP)
 *  asset()    → "my-plugin-{n}"  (slug + nombre del asset)
 *  option()   → "my_plugin_{k}"  (prefix + clave)
 *  nonce()    → "my_plugin_{n}"  (prefix + nombre)
 *  cron_hook()→ "my_plugin_{n}"  (prefix + nombre)
 *  menu_slug()→ "my-plugin-{p}"  (slug + página)
 *  options_group() → "my_plugin_options_group"
 *  js_object()     → "MyPluginAdmin"   (namespace_root + "Admin")
 *  shortcode()     → "my_plugin"       (= prefix)
 * ─────────────────────────────────────────────────────────────────────────────
 */

declare( strict_types=1 );

namespace MyPlugin\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Config {

	/** @var array<string,string> Datos cargados desde plugin.config.php */
	private static array $cfg = [];

	private static string $file = '';

	// ── Inicialización ────────────────────────────────────────────────────────


	public static function init( array $cfg, string $plugin_file = '' ): void {
		if ( ! empty( self::$cfg ) ) {
			return; // Evitar doble inicialización
		}

		if ( empty( $cfg['slug'] ) ) {
			throw new \RuntimeException( '[Config] El "slug" es obligatorio en plugin.config.php.' );
		}

		self::$cfg  = $cfg;
		self::$file = $plugin_file;
	}

	public static function slug(): string {
		return self::$cfg['slug'];
	}

	public static function name(): string {
		return self::$cfg['name'] ?? '';
	}

	public static function version(): string {
		return self::$cfg['version'] ?? '1.0.0';
	}

	public static function file(): string {
		return self::$file;
	}

	public static function dir(): string {
		return self::$file ? plugin_dir_path( self::$file ) : '';
	}

	public static function url(): string {
		return self::$file ? plugin_dir_url( self::$file ) : '';
	}

	public static function prefix(): string {
		return str_replace( '-', '_', self::slug() );
	}

	public static function text_domain(): string {
		return self::slug();
	}

	public static function namespace_root(): string {
		return str_replace( ' ', '', ucwords( str_replace( '-', ' ', self::slug() ) ) );
	}

	public static function asset( string $name ): string {
		return self::slug() . '-' . $name;
	}

	public static function option( string $key ): string {
		return self::prefix() . '_' . $key;
	}

	public static function nonce( string $name ): string {
		return self::prefix() . '_' . $name;
	}

	public static function cron_hook( string $name ): string {
		return self::prefix() . '_' . $name;
	}

	public static function menu_slug( string $page = 'settings' ): string {
		return self::slug() . '-' . $page;
	}

	public static function options_group(): string {
		return self::prefix() . '_options_group';
	}

	public static function js_object(): string {
		return preg_replace( '/[^a-zA-Z0-9]/', '', self::namespace_root() ) . 'Admin';
	}

	public static function shortcode(): string {
		return self::prefix();
	}
}
