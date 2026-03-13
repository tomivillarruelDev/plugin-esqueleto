#!/usr/bin/env php
<?php
/**
 * ╔══════════════════════════════════════════════════════════════════════════╗
 * ║          GENERADOR DE PLUGIN — Plugin Skeleton (EON)                   ║
 * ╠══════════════════════════════════════════════════════════════════════════╣
 * ║  Crea un nuevo plugin a partir de esta plantilla reemplazando todos     ║
 * ║  los identificadores hardcodeados en una sola pasada.                   ║
 * ║                                                                         ║
 * ║  Uso:                                                                   ║
 * ║    php bin/rename-plugin.php <slug> <Namespace> "<Nombre>"              ║
 * ║                                                                         ║
 * ║  Argumentos:                                                            ║
 * ║    <slug>       kebab-case  → text domain, handles, slugs de menú       ║
 * ║    <Namespace>  PascalCase  → namespace PHP, objeto JS                  ║
 * ║    "<Nombre>"   Legible     → menú admin, header del plugin             ║
 * ║                                                                         ║
 * ║  Ejemplo:                                                               ║
 * ║    php bin/rename-plugin.php eon-reservas EonReservas "EON Reservas"    ║
 * ║                                                                         ║
 * ║  Qué hace este script:                                                  ║
 * ║    1. Reemplaza MiPlugin → <Namespace>  en todos los archivos PHP       ║
 * ║    2. Reemplaza mi-plugin → <slug>      en PHP, JS, CSS, MD             ║
 * ║    3. Reemplaza mi_plugin → <prefix>    (snake_case derivado del slug)  ║
 * ║    4. Reemplaza "Mi Plugin" → "<Nombre>" en todos los archivos          ║
 * ║    5. Actualiza plugin.config.php con los nuevos valores                ║
 * ║    6. Renombra plugin.php → <slug>.php                                  ║
 * ║                                                                         ║
 * ║  Este directorio (bin/) NO se procesa para preservar el script.         ║
 * ╚══════════════════════════════════════════════════════════════════════════╝
 */

declare( strict_types=1 );

// ── 1. Validación de argumentos ───────────────────────────────────────────────

if ( $argc !== 4 ) {
	$msg  = "\n";
	$msg .= "\033[1;33m[EON Plugin Skeleton]\033[0m\n\n";
	$msg .= "Uso:  php bin/rename-plugin.php <slug> <Namespace> \"<Nombre>\"\n";
	$msg .= "Ej:   php bin/rename-plugin.php eon-reservas EonReservas \"EON Reservas\"\n\n";
	$msg .= "Argumentos:\n";
	$msg .= "  <slug>       kebab-case  (ej: eon-reservas, mi-contacto)\n";
	$msg .= "  <Namespace>  PascalCase  (ej: EonReservas, MiContacto)\n";
	$msg .= "  \"<Nombre>\"   Legible     (ej: \"EON Reservas\", \"Mi Contacto\")\n\n";
	fwrite( STDERR, $msg );
	exit( 1 );
}

$new_slug      = trim( $argv[1] ); // eon-reservas
$new_namespace = trim( $argv[2] ); // EonReservas
$new_name      = trim( $argv[3] ); // EON Reservas

// ── 2. Validaciones de formato ────────────────────────────────────────────────

if ( ! preg_match( '/^[a-z][a-z0-9\-]+$/', $new_slug ) ) {
	fwrite( STDERR, "Error: <slug> debe ser kebab-case (sólo minúsculas, números y guiones). Ej: eon-reservas\n" );
	exit( 1 );
}

if ( ! preg_match( '/^[A-Z][A-Za-z0-9]+$/', $new_namespace ) ) {
	fwrite( STDERR, "Error: <Namespace> debe ser PascalCase (empezar con mayúscula). Ej: EonReservas\n" );
	exit( 1 );
}

if ( empty( $new_name ) ) {
	fwrite( STDERR, "Error: <Nombre> no puede estar vacío.\n" );
	exit( 1 );
}

// ── 3. Valores originales de la plantilla ────────────────────────────────────
// Estos valores SIEMPRE son los de la plantilla base.
// El script bin/ no se procesa, por lo que estas cadenas nunca cambian.

$old_slug      = 'mi-plugin';  // kebab-case original
$old_namespace = 'MiPlugin';   // PascalCase original
$old_name      = 'Mi Plugin';  // Nombre legible original
$old_prefix    = 'mi_plugin';  // snake_case original (derivado de old_slug)

// ── 3.5 Validación de ejecución previa ───────────────────────────────────────

$config_file = dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'plugin.config.php';
if ( ! file_exists( $config_file ) ) {
	fwrite( STDERR, "Error: No se encontró plugin.config.php.\n" );
	exit( 1 );
}

$config_content = file_get_contents( $config_file );
if ( strpos( $config_content, "'slug'    => '{$old_slug}'" ) === false ) {
	echo "\n\033[1;33m[!] El plugin ya parece haber sido renombrado.\033[0m\n";
	echo "No se encontró el slug original '{$old_slug}' en plugin.config.php.\n";
	echo "Si querés volver a renombrarlo, hacelo manualmente en plugin.config.php.\n\n";
	exit( 0 );
}

$new_prefix    = str_replace( '-', '_', $new_slug ); // eon_reservas

// ── 4. Tabla de reemplazos ────────────────────────────────────────────────────
// strtr() aplica el reemplazo de la clave MÁS LARGA primero → sin conflictos.
// Orden importa: el namespace (MiPlugin) es subcadena de MiPluginAdmin,
// strtr lo maneja correctamente porque busca siempre la coincidencia más larga.

$replacements = [
	$old_namespace => $new_namespace, // MiPlugin  → EonReservas  (namespaces PHP, objeto JS)
	$old_name      => $new_name,      // Mi Plugin → EON Reservas (cabecera, menú admin)
	$old_slug      => $new_slug,      // mi-plugin → eon-reservas (handles, text domain, URIs)
	$old_prefix    => $new_prefix,    // mi_plugin → eon_reservas (opciones BD, nonces, crons)
];

// ── 5. Configuración del procesamiento de archivos ────────────────────────────

$root_dir   = dirname( __DIR__ );              // Raíz del plugin (un nivel arriba de bin/)
$extensions = [ 'php', 'js', 'css', 'md', 'json', 'svg', 'txt', 'xml' ];   // Tipos de archivo a procesar
$skip_dirs  = [ '.git', 'bin', 'node_modules', 'vendor' ]; // Carpetas a ignorar

$modified_files = [];
$scanned_count  = 0;

// ── 6. Recorrer y procesar archivos ───────────────────────────────────────────

$iterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator(
		$root_dir,
		RecursiveDirectoryIterator::SKIP_DOTS
	)
);

foreach ( $iterator as $file_info ) {
	if ( ! $file_info->isFile() ) {
		continue;
	}

	$abs_path = $file_info->getPathname();

	// Ruta relativa para logs y para detectar directorios ignorados.
	$relative = ltrim(
		str_replace( [ $root_dir . '/', $root_dir . '\\' ], '', $abs_path ),
		'/\\'
	);

	// Extraer el primer segmento de la ruta para comprobar si está en skip_dirs.
	$first_segment = strstr( str_replace( '\\', '/', $relative ), '/', true );
	if ( $first_segment && in_array( $first_segment, $skip_dirs, true ) ) {
		continue;
	}

	$ext = strtolower( $file_info->getExtension() );
	if ( ! in_array( $ext, $extensions, true ) ) {
		continue;
	}

	$scanned_count++;
	$original    = file_get_contents( $abs_path );
	$new_content = strtr( $original, $replacements );

	if ( $new_content !== $original ) {
		file_put_contents( $abs_path, $new_content );
		$modified_files[] = $relative;
	}
}

// ── 7. Renombrar plugin.php → {new-slug}.php ─────────────────────────────────

$main_old     = $root_dir . DIRECTORY_SEPARATOR . 'plugin.php';
$main_new     = $root_dir . DIRECTORY_SEPARATOR . $new_slug . '.php';
$renamed_main = false;

if ( file_exists( $main_old ) && ! file_exists( $main_new ) ) {
	rename( $main_old, $main_new );
	$renamed_main = true;
} elseif ( file_exists( $main_old ) && file_exists( $main_new ) ) {
	fwrite( STDERR, "Advertencia: ya existe {$new_slug}.php, plugin.php no fue renombrado.\n" );
}

// ── 8. Reporte final ──────────────────────────────────────────────────────────

echo "\n\033[1;32m✓ Plugin renombrado correctamente\033[0m\n\n";
echo "  Slug:        \033[0;36m{$new_slug}\033[0m\n";
echo "  Prefix:      \033[0;36m{$new_prefix}\033[0m\n";
echo "  Namespace:   \033[0;36m{$new_namespace}\033[0m\n";
echo "  Nombre:      \033[0;36m{$new_name}\033[0m\n";

if ( $renamed_main ) {
	echo "\n  \033[0;33mplugin.php → {$new_slug}.php\033[0m\n";
}

if ( ! empty( $modified_files ) ) {
	echo "\nArchivos modificados (" . count( $modified_files ) . "):\n";
	foreach ( $modified_files as $f ) {
		echo "  \033[0;90m→\033[0m {$f}\n";
	}
}

echo "\n\033[1;37mSiguientes pasos:\033[0m\n";
echo "  1. Copiá la carpeta a \033[0;36mwp-content/plugins/{$new_slug}/\033[0m\n";
echo "  2. Activá el plugin desde \033[0;36mPlugins → Plugins instalados\033[0m\n";
echo "  3. Usá el shortcode: \033[0;36m[{$new_prefix}]\033[0m\n\n";
