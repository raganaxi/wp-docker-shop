<?php
if (!defined('ABSPATH')) {
    exit;
}

// Registrar configuraciones de opciones
function banner_manager_register_settings()
{
    register_setting('banner_manager_options', 'banner_manager_featured_products');
}
add_action('admin_init', 'banner_manager_register_settings');

// Agregar menús principales en el admin
function banner_manager_add_menu_pages()
{

    // Menú principal para Ofertas del Día
    add_menu_page(
        'Ofertas del Día',             // Título de la página
        'Ofertas del Día',             // Nombre del menú
        'manage_options',              // Permisos requeridos
        'banner-manager-offers',       // Slug del menú
        'banner_manager_offers_page',  // Función que renderiza la página
        'dashicons-tag',               // Icono del menú
        6                              // Posición en el menú
    );
}
add_action('admin_menu', 'banner_manager_add_menu_pages');
