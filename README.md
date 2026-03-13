# Plugin Skeleton — EON

Plantilla de WordPress Plugin reutilizable. Diseñada para crear nuevos plugins
cambiando la menor cantidad posible de archivos.

---

## Crear un nuevo plugin a partir de esta plantilla

### Paso 1 — Copiá la carpeta del template
```
cp -r plugin-esqueleto/ wp-content/plugins/mi-nuevo-plugin/
```

### Paso 2 — Editá `plugin.config.php` ← **único archivo obligatorio**
```php
return [
    'slug'    => 'mi-nuevo-plugin',   // kebab-case
    'name'    => 'Mi Nuevo Plugin',   // Nombre legible
    'version' => '1.0.0',
    'author'  => 'Tu Nombre',
];
```

### Paso 3 — Renombrá los namespaces PHP (un solo comando)
```bash
php bin/rename-plugin.php mi-nuevo-plugin MiNuevoPlugin "Mi Nuevo Plugin"
```
Este script reemplaza en todos los archivos `.php`, `.js`, `.css` y `.md`:

| Busca        | Reemplaza por   | Dónde aparece                         |
|--------------|-----------------|---------------------------------------|
| `MiPlugin`   | `MiNuevoPlugin` | namespace, use, autoloader, objeto JS |
| `mi-plugin`  | `mi-nuevo-plugin`| text domain, handles, URIs           |
| `mi_plugin`  | `mi_nuevo_plugin`| opciones BD, nonces, cron hooks      |
| `Mi Plugin`  | `Mi Nuevo Plugin`| header del plugin, menú admin         |

Además renombra `plugin.php` → `mi-nuevo-plugin.php`.

### Paso 4 — Activá el plugin
Copiá la carpeta a `wp-content/plugins/` y activá desde el panel.

---

## Arquitectura: cómo funciona la reutilización

```
plugin.config.php          ← ★ Única fuente de verdad en runtime
	↓
includes/Core/Config.php   ← Deriva todos los identificadores del slug
	↓
   Plugin.php              → Orquestador: registra Ajax, Settings, Admin y Frontend
   Activator.php           → Gestión de versiones y migraciones (version_compare)
   Deactivator.php         → Config::cron_hook('cron_event')
   AdminPage.php           → Config::menu_slug(), Config::asset(), Config::nonce()
   Frontend.php            → Sanitización de shortcode, assets condicionales
   Ajax.php                → Handler centralizado con Nonce y Cap Check
   Settings.php            → Implementación de Settings API (WP nativo)
   uninstall.php           → Limpieza al eliminar usando Config::option()
```

### Características Principales

1. **Gestión de Versiones**: `Activator::activate()` detecta si es una instalación nueva o un upgrade usando `version_compare()`, facilitando futuras migraciones de base de datos.
2. **AJAX Seguro**: La clase `Ajax.php` centraliza las peticiones. Incluye verificación de Nonce (`check_ajax_referer`) y de capacidades (`current_user_can`) por defecto.
3. **Settings API**: Ejemplo completo en `Settings.php` para registrar secciones, campos y sanitizar opciones de forma nativa en WordPress.
4. **Shortcode Robusto**: `Frontend::render_shortcode()` sanitiza atributos (`sanitize_hex_color`, `sanitize_text_field`) y utiliza una flag estática para evitar encolar assets innecesariamente.
5. **Autoloader con Logging**: El autoloader en `plugin.php` captura errores de carga y los registra en el `error_log` del servidor para facilitar el debugging.
6. **Script de Renombrado Protegido**: `bin/rename-plugin.php` valida si el plugin ya fue procesado para evitar corrupciones por ejecuciones accidentales.

### Qué controla cada mecanismo

| Mecanismo              | Controla                                        |
|------------------------|-------------------------------------------------|
| `plugin.config.php`    | slug, nombre, versión (runtime)                 |
| `Config` (derivado)    | handles, text domain, option keys, nonces, etc. |
| `bin/rename-plugin.php`| namespaces PHP, header del plugin (one-time)    |

---

## Estructura de archivos

```
plugin-esqueleto/
├── plugin.php                    ← Entry point. Al crear un plugin nuevo
│                                   el script lo renombra a {slug}.php
├── plugin.config.php             ← ★ EDITAR ESTE ARCHIVO al crear nuevo plugin
├── uninstall.php                 ← Limpieza al eliminar (usa Config)
│
├── bin/
│   └── rename-plugin.php        ← Script de renombrado (ejecutar una vez)
│
├── includes/
│   ├── Core/
│   │   ├── Config.php           ← Centraliza todos los identificadores
│   │   ├── Plugin.php           ← Orquestador de hooks y módulos
│   │   ├── Activator.php        ← Hook de activación y upgrade
│   │   ├── Deactivator.php      ← Hook de desactivación
│   │   ├── Ajax.php             ← Handler de peticiones AJAX (Ejemplo)
│   │   └── Settings.php         ← Registro de opciones (Settings API)
│   ├── Admin/
│   │   └── AdminPage.php        ← Menú, assets y vista del wp-admin
│   └── Frontend/
│       └── Frontend.php         ← Shortcode y assets del sitio público
│
├── views/
│   ├── admin/
│   │   └── settings-page.php    ← Template HTML del panel de opciones
│   └── frontend/
│       └── shortcode.php        ← Template HTML del shortcode (Escapado)
│
├── assets/
│   ├── css/
│   │   ├── admin.css            ← Estilos del wp-admin
│   │   └── public.css           ← Estilos del sitio público
│   └── js/
│       ├── admin.js             ← JS del admin (acceso a AJAX + nonce)
│       └── public.js            ← JS del sitio público
│
└── languages/                   ← .pot / .po / .mo para traducciones
```

## Identificadores derivados del slug `mi-plugin`

| Método                        | Devuelve                    |
|-------------------------------|-----------------------------|
| `Config::slug()`              | `mi-plugin`                 |
| `Config::prefix()`            | `mi_plugin`                 |
| `Config::text_domain()`       | `mi-plugin`                 |
| `Config::namespace_root()`    | `MiPlugin`                  |
| `Config::asset('admin')`      | `mi-plugin-admin`           |
| `Config::option('version')`   | `mi_plugin_version`         |
| `Config::nonce('admin')`      | `mi_plugin_admin`           |
| `Config::cron_hook('sync')`   | `mi_plugin_sync`            |
| `Config::menu_slug()`         | `mi-plugin-settings`        |
| `Config::options_group()`     | `mi_plugin_options_group`   |
| `Config::js_object()`         | `MiPluginAdmin`             |
| `Config::shortcode()`         | `mi_plugin`                 |

## Requisitos

- WordPress 5.8+
- PHP 7.4+

## Shortcode

```
[mi_plugin titulo="Bienvenido" color="#e44d26"]
```
