<?php
/**
 * ╔══════════════════════════════════════════════════════════════════════╗
 * ║            CONFIGURACIÓN CENTRAL DEL PLUGIN                        ║
 * ╠══════════════════════════════════════════════════════════════════════╣
 * ║  Este es el ÚNICO archivo que debés editar cuando copies esta       ║
 * ║  plantilla para crear un nuevo plugin.                              ║
 * ║                                                                     ║
 * ║  Para renombrar también los namespaces PHP (única operación que     ║
 * ║  este archivo no puede controlar), ejecutá desde la raíz:           ║
 * ║                                                                     ║
 * ║    php bin/rename-plugin.php <slug> <Namespace> "<Nombre>"          ║
 * ║                                                                     ║
 * ║  Ejemplo:                                                           ║
 * ║    php bin/rename-plugin.php eon-reservas EonReservas "EON Reservas"║
 * ╚══════════════════════════════════════════════════════════════════════╝
 *
 * Todos los demás identificadores del plugin (handles de assets, claves
 * de opciones en la BD, slugs de menú, text domain, nonces, hooks de cron,
 * objetos JS) se derivan AUTOMÁTICAMENTE del slug definido aquí mediante
 * la clase Config (includes/Core/Config.php).
 */

return [
	// ── Obligatorio ──────────────────────────────────────────────────────────

	// kebab-case. Controla: text-domain, handles de assets, slugs de menú,
	// claves de opciones, nonces, hooks de cron, tag de shortcode.
	'slug'    => 'mi-plugin',

	// Nombre legible. Aparece en: menú del admin, títulos de página.
	'name'    => 'Mi Plugin',

	// Versión semántica. Se usa en el cache-busting de assets.
	'version' => '1.0.0',

	// ── Opcional ─────────────────────────────────────────────────────────────
	'author'  => 'Tu Nombre',
];
