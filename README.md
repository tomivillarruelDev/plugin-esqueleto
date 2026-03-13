# Mi Plugin

Plugin de WordPress — esqueleto base.

## Estructura de carpetas

```
plugin-esqueleto/
├── mi-plugin.php              ← Punto de entrada. WordPress lo lee primero.
├── uninstall.php              ← Se ejecuta al eliminar el plugin.
│
├── includes/
│   ├── Core/
│   │   ├── Plugin.php         ← Orquestador: registra todos los hooks.
│   │   ├── Activator.php      ← Lógica de activación.
│   │   └── Deactivator.php    ← Lógica de desactivación.
│   ├── Admin/
│   │   └── AdminPage.php      ← Página de configuración en el wp-admin.
│   └── Frontend/
│       └── Frontend.php       ← Shortcodes y assets del sitio público.
│
├── views/
│   ├── admin/
│   │   └── settings-page.php  ← Template HTML del panel de opciones.
│   └── frontend/
│       └── shortcode.php      ← Template HTML del shortcode [mi_plugin].
│
├── assets/
│   ├── css/
│   │   ├── admin.css          ← Estilos exclusivos del wp-admin.
│   │   └── public.css         ← Estilos del sitio público.
│   └── js/
│       ├── admin.js           ← JS del wp-admin (con acceso a AJAX + nonce).
│       └── public.js          ← JS del sitio público.
│
└── languages/                 ← Archivos .pot / .po / .mo para traducción.
```

## Requisitos

- WordPress 5.8+
- PHP 7.4+

## Instalación

1. Copiá la carpeta `mi-plugin/` en `/wp-content/plugins/`.
2. Activá el plugin desde **Plugins → Plugins instalados**.

## Uso

Insertá el shortcode en cualquier entrada o página:

```
[mi_plugin titulo="Bienvenido" color="#e44d26"]
```
