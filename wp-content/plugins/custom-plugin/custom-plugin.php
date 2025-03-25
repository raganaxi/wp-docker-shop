<?php

/**
 * Plugin Name: Banner Manager
 * Description: Plugin multipropósito para gestionar banners y ofertas del día.
 * Version: 1.1.0
 * Author: Antonio
 */

if (!defined('ABSPATH')) {
    exit;
}

// Definir rutas base del plugin
define('BANNER_MANAGER_PATH', plugin_dir_path(__FILE__));
define('BANNER_MANAGER_URL', plugin_dir_url(__FILE__));

// Incluir archivos del plugin
require_once BANNER_MANAGER_PATH . 'includes/banners.php';
require_once BANNER_MANAGER_PATH . 'includes/offers.php';
require_once BANNER_MANAGER_PATH . 'includes/settings.php';
require_once BANNER_MANAGER_PATH . 'includes/assets.php';
require_once BANNER_MANAGER_PATH . 'shortcodes/banners-shortcode.php';
require_once BANNER_MANAGER_PATH . 'shortcodes/offers-shortcode.php';

// Activar el plugin (se ejecuta al activarlo)
function banner_manager_activate()
{
    banner_manager_register_post_type(); // Registrar el CPT de banners
    flush_rewrite_rules(); // Limpiar reglas de URL
}
register_activation_hook(__FILE__, 'banner_manager_activate');

// Desactivar el plugin (se ejecuta al desactivarlo)
function banner_manager_deactivate()
{
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'banner_manager_deactivate');
