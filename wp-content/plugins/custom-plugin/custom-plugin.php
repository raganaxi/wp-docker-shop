<?php

/**
 * Plugin Name: Banner Manager
 * Description: Plugin para gestionar banners con thumbnails rectangulares.
 * Version: 1.0.0
 * Author: Antonio
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Registrar el Custom Post Type para Banners
function banner_manager_register_post_type()
{
    $labels = array(
        'name'          => 'Banners',
        'singular_name' => 'Banner',
        'menu_name'     => 'Banners',
        'add_new'       => 'Añadir Nuevo',
        'add_new_item'  => 'Añadir Nuevo Banner',
        'edit_item'     => 'Editar Banner',
        'new_item'      => 'Nuevo Banner',
        'view_item'     => 'Ver Banner',
        'search_items'  => 'Buscar Banners',
        'not_found'     => 'No se encontraron banners',
        'not_found_in_trash' => 'No se encontraron banners en la papelera',
    );

    $args = array(
        'labels'        => $labels,
        'public'        => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 5,
        'supports'      => array('title', 'thumbnail'),
        'has_archive'   => false,
        'rewrite'       => array('slug' => 'banners'),
    );

    register_post_type('banner', $args);
}
add_action('init', 'banner_manager_register_post_type');

// Agregar tamaño de imagen para banners
function banner_manager_image_sizes()
{
    add_image_size('banner_thumbnail', 1200, 400, true); // Tamaño rectangular
}
add_action('after_setup_theme', 'banner_manager_image_sizes');

// Agregar meta box para enlace del banner
function banner_manager_add_meta_box()
{
    add_meta_box(
        'banner_link_meta',
        'Enlace del Banner',
        'banner_manager_meta_box_callback',
        'banner',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'banner_manager_add_meta_box');

function banner_manager_meta_box_callback($post)
{
    $value = get_post_meta($post->ID, '_banner_link', true);
    echo '<label for="banner_link">URL del Banner:</label> ';
    echo '<input type="text" id="banner_link" name="banner_link" value="' . esc_attr($value) . '" size="50" />';
}

// Guardar el meta box
function banner_manager_save_meta_box($post_id)
{
    if (array_key_exists('banner_link', $_POST)) {
        update_post_meta(
            $post_id,
            '_banner_link',
            sanitize_text_field($_POST['banner_link'])
        );
    }
}
add_action('save_post', 'banner_manager_save_meta_box');

// Agregar shortcode para mostrar el carrusel
function banner_manager_carousel_shortcode()
{
    $args = array(
        'post_type'      => 'banner',
        'posts_per_page' => -1,
    );
    $query = new WP_Query($args);
    $output = '<div class="swiper-container"><div class="swiper-wrapper">';

    while ($query->have_posts()) {
        $query->the_post();
        $link = get_post_meta(get_the_ID(), '_banner_link', true);
        $image = get_the_post_thumbnail_url(get_the_ID(), 'banner_thumbnail');
        if ($image) {
            $output .= '<div class="swiper-slide">';
            if ($link) {
                $output .= '<a href="' . esc_url($link) . '"><img src="' . esc_url($image) . '" alt="' . esc_attr(get_the_title()) . '" /></a>';
            } else {
                $output .= '<img src="' . esc_url($image) . '" alt="' . esc_attr(get_the_title()) . '" />';
            }
            $output .= '</div>';
        }
    }
    wp_reset_postdata();
    $output .= '</div></div>';
    return $output;
}
add_shortcode('banner_carousel', 'banner_manager_carousel_shortcode');

// Cargar scripts de Swiper.js
function banner_manager_enqueue_scripts()
{
    wp_enqueue_style('swiper-style', 'https://unpkg.com/swiper/swiper-bundle.min.css');
    wp_enqueue_script('swiper-script', 'https://unpkg.com/swiper/swiper-bundle.min.js', array('jquery'), null, true);
    wp_add_inline_script('swiper-script', 'var swiper = new Swiper(".swiper-container", { loop: true, autoplay: { delay: 3000 } });');
}
add_action('wp_enqueue_scripts', 'banner_manager_enqueue_scripts');
