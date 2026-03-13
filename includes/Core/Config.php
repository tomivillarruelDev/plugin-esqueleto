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
 *  slug       → "mi-plugin"      (configurado en plugin.config.php)
 *  prefix     → "mi_plugin"      (slug con guiones reemplazados por _)
 *  text_domain→ "mi-plugin"      (= slug, convención de WP)
 *  asset()    → "mi-plugin-{n}"  (slug + nombre del asset)
 *  option()   → "mi_plugin_{k}"  (prefix + clave)
 *  nonce()    → "mi_plugin_{n}"  (prefix + nombre)
 *  cron_hook()→ "mi_plugin_{n}"  (prefix + nombre)
 *  menu_slug()→ "mi-plugin-{p}"  (slug + página)
 *  options_group() → "mi_plugin_options_group"
 *  js_object()     → "MiPluginAdmin"   (namespace_root + "Admin")
 *  shortcode()     → "mi_plugin"       (= prefix)
 * ─────────────────────────────────────────────────────────────────────────────
 */

declare( strict_types=1 );

namespace MiPlugin\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Config {

	/** @var array<string,string> Datos cargados desde plugin.config.php */
	private static array $cfg = [];

	/** @var string Ruta absoluta al archivo principal del plugin (__FILE__) */
	private static string $file = '';

	// ── Inicialización ────────────────────────────────────────────────────────

	/**
	 * Inicializa la configuración. Debe llamarse UNA sola vez, al inicio de
	 * plugin.php, antes del autoloader y antes de cualquier hook de WP.
	 *
	 * @param array<string,string> $cfg         Contenido de plugin.config.php.
	 * @param string               $plugin_file Ruta de __FILE__ del plugin principal.
	 *                                          Omitir sólo en uninstall.php.
	 * @throws \RuntimeException Si ya fue inicializada o si el slug es inválido.
	 */
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

	// ── Valores base (definidos en plugin.config.php) ─────────────────────────

	/** Slug kebab-case: "mi-plugin" */
	public static function slug(): string {
		return self::$cfg['slug'];
	}

	/** Nombre legible: "Mi Plugin" */
	public static function name(): string {
		return self::$cfg['name'] ?? '';
	}

	/** Versión semántica: "1.0.0" */
	public static function version(): string {
		return self::$cfg['version'] ?? '1.0.0';
	}

	// ── Rutas del sistema de archivos ─────────────────────────────────────────

	/** Ruta absoluta al archivo principal del plugin (con trailing slash en el dir) */
	public static function file(): string {
		return self::$file;
	}

	/** Directorio raíz del plugin con trailing slash. Ej: /var/www/wp-content/plugins/mi-plugin/ */
	public static function dir(): string {
		return self::$file ? plugin_dir_path( self::$file ) : '';
	}

	/** URL pública del plugin con trailing slash. Ej: https://sitio.com/wp-content/plugins/mi-plugin/ */
	public static function url(): string {
		return self::$file ? plugin_dir_url( self::$file ) : '';
	}

	// ── Identificadores derivados automáticamente del slug ────────────────────

	/**
	 * Prefijo en snake_case para opciones de BD, nonces, cron hooks, etc.
	 * "mi-plugin" → "mi_plugin"
	 */
	public static function prefix(): string {
		return str_replace( '-', '_', self::slug() );
	}

	/**
	 * Text domain para internacionalización (igual al slug, por convención de WP).
	 * "mi-plugin" → "mi-plugin"
	 */
	public static function text_domain(): string {
		return self::slug();
	}

	/**
	 * Raíz del namespace PHP en PascalCase (sólo para referencia y js_object).
	 * "mi-plugin" → "MiPlugin"
	 *
	 * NOTA DE DISEÑO: Este método es decorativo. No garantiza consistencia con
	 * el namespace real de los archivos PHP, ya que los namespaces son estáticos.
	 * El namespace real lo gestiona bin/rename-plugin.php al procesar los archivos.
	 */
	public static function namespace_root(): string {
		return str_replace( ' ', '', ucwords( str_replace( '-', ' ', self::slug() ) ) );
	}

	// ── Helpers para construir identificadores de WP ──────────────────────────

	/**
	 * Handle de un asset CSS o JS registrado con wp_enqueue_*.
	 * Config::asset('admin')  → "mi-plugin-admin"
	 * Config::asset('public') → "mi-plugin-public"
	 */
	public static function asset( string $name ): string {
		return self::slug() . '-' . $name;
	}

	/**
	 * Clave de opción en la tabla wp_options.
	 * Config::option('version')  → "mi_plugin_version"
	 * Config::option('settings') → "mi_plugin_settings"
	 */
	public static function option( string $key ): string {
		return self::prefix() . '_' . $key;
	}

	/**
	 * Action string de un nonce de WordPress.
	 * Config::nonce('admin') → "mi_plugin_admin"
	 */
	public static function nonce( string $name ): string {
		return self::prefix() . '_' . $name;
	}

	/**
	 * Hook name para un evento de WP-Cron.
	 * Config::cron_hook('daily_sync') → "mi_plugin_daily_sync"
	 */
	public static function cron_hook( string $name ): string {
		return self::prefix() . '_' . $name;
	}

	/**
	 * Slug de página en el menú del admin.
	 * Config::menu_slug()            → "mi-plugin-settings"
	 * Config::menu_slug('dashboard') → "mi-plugin-dashboard"
	 */
	public static function menu_slug( string $page = 'settings' ): string {
		return self::slug() . '-' . $page;
	}

	/**
	 * Options group para la Settings API de WordPress.
	 * Config::options_group() → "mi_plugin_options_group"
	 */
	public static function options_group(): string {
		return self::prefix() . '_options_group';
	}

	/**
	 * Nombre del objeto JavaScript inyectado con wp_localize_script().
	 * Config::js_object() → "MiPluginAdmin"
	 *
	 * Nota: el archivo admin.js debe referenciar este mismo nombre.
	 * bin/rename-plugin.php actualiza ambos de forma sincronizada.
	 */
	public static function js_object(): string {
		// Limpia el nombre para asegurar que sea una variable JS válida (sin backslashes de namespaces).
		return preg_replace( '/[^a-zA-Z0-9]/', '', self::namespace_root() ) . 'Admin';
	}

	/**
	 * Tag del shortcode registrado en WordPress.
	 * Config::shortcode() → "mi_plugin"
	 * Uso en el editor:   [mi_plugin titulo="Hola"]
	 */
	public static function shortcode(): string {
		return self::prefix();
	}
}
