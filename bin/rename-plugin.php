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
 * ║    1. Reemplaza MyPlugin → <Namespace>  en todos los archivos PHP       ║
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

// ── 1. Argumentos ───────────────────────────────────────────────────────────
if ( $argc !== 4 ) {
	$msg  = "\nUso:  php bin/rename-plugin.php <slug> <Namespace> \"<Nombre>\"\n";
	$msg .= "Ej:   php bin/rename-plugin.php eon-reservas EonReservas \"EON Reservas\"\n\n";
	fwrite( STDERR, $msg );
	exit( 1 );
}

$new_slug      = trim( $argv[1] ); 
$new_namespace = trim( $argv[2] ); 
$new_name      = trim( $argv[3] ); 

// ── 2. Validaciones ──────────────────────────────────────────────────────────
if ( ! preg_match( '/^[a-z][a-z0-9\-]+$/', $new_slug ) ) {
	fwrite( STDERR, "Error: <slug> debe ser kebab-case.\n" );
	exit( 1 );
}

if ( ! preg_match( '/^[A-Z][A-Za-z0-9]+$/', $new_namespace ) ) {
	fwrite( STDERR, "Error: <Namespace> debe ser PascalCase.\n" );
	exit( 1 );
}

if ( empty( $new_name ) ) {
	fwrite( STDERR, "Error: <Nombre> no puede estar vacío.\n" );
	exit( 1 );
}

// ── 3. Valores Base ──────────────────────────────────────────────────────────
$old_slug      = 'mi-plugin';
$old_namespace = 'MyPlugin';
$old_name      = 'Mi Plugin';
$old_prefix    = 'mi_plugin';

// ── 3.5 Check de ejecución previa ────────────────────────────────────────────
$config_file = dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'plugin.config.php';
if ( ! file_exists( $config_file ) ) {
	fwrite( STDERR, "Error: No se encontró plugin.config.php.\n" );
	exit( 1 );
}

$config_content = file_get_contents( $config_file );
if ( strpos( $config_content, "'slug'    => '{$old_slug}'" ) === false ) {
	echo "\n[!] El plugin ya parece haber sido renombrado.\n\n";
	exit( 0 );
}

$new_prefix    = str_replace( '-', '_', $new_slug );

// ── 4. Reemplazos ───────────────────────────────────────────────────────────
$replacements = [
	$old_namespace => $new_namespace,
	$old_name      => $new_name,
	$old_slug      => $new_slug,
	$old_prefix    => $new_prefix,
];

// ── 5. Config de archivos ────────────────────────────────────────────────────
$root_dir   = dirname( __DIR__ );
$extensions = [ 'php', 'js', 'css', 'md', 'json', 'svg', 'txt', 'xml' ];
$skip_dirs  = [ '.git', 'bin', 'node_modules', 'vendor' ];

$modified_files = [];
$scanned_count  = 0;

// ── 6. Procesamiento ─────────────────────────────────────────────────────────
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
	$relative = ltrim( str_replace( [ $root_dir . '/', $root_dir . '\\' ], '', $abs_path ), '/\\' );

	$first_segment = strstr( str_replace( '\\', '/', $relative ), '/', true );
	if ( $first_segment && in_array( $first_segment, $skip_dirs, true ) ) {
		continue;
	}

	if ( ! in_array( strtolower( $file_info->getExtension() ), $extensions, true ) ) {
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

// ── 7. Renombrar plugin principal ────────────────────────────────────────────
$main_old = $root_dir . DIRECTORY_SEPARATOR . 'plugin.php';
$main_new = $root_dir . DIRECTORY_SEPARATOR . $new_slug . '.php';

if ( file_exists( $main_old ) && ! file_exists( $main_new ) ) {
	rename( $main_old, $main_new );
}

// ── 8. Reporte ───────────────────────────────────────────────────────────────
echo "\n✓ Plugin renombrado correctamente\n\n";
echo "  Slug:      {$new_slug}\n";
echo "  Namespace: {$new_namespace}\n";
echo "  Nombre:    {$new_name}\n\n";

if ( ! empty( $modified_files ) ) {
	echo "Archivos modificados: " . count( $modified_files ) . "\n";
}
