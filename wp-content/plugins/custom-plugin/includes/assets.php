<?php
if (!defined('ABSPATH')) {
    exit;
}

function banner_manager_enqueue_scripts()
{
    wp_enqueue_style('swiper-style', BANNER_MANAGER_URL . 'assets/dll/swiper-bundle.min.css');
    wp_enqueue_script('swiper-script', BANNER_MANAGER_URL . 'assets/dll/swiper-bundle.min.js', array(), null, true);
    wp_enqueue_style('banner-manager-style', BANNER_MANAGER_URL . 'assets/css/styles.css');
    wp_enqueue_script('banner-manager-script', BANNER_MANAGER_URL . 'assets/js/scripts.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'banner_manager_enqueue_scripts');
