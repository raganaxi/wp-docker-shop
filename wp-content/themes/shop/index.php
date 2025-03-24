<?php
get_header();

if (is_front_page() && is_home()) { // Solo en la homepage por defecto
    if (function_exists('is_plugin_active') && is_plugin_active('custom-plugin/custom-plugin.php')) {
        echo do_shortcode('[banner_carousel]');
    }
}

get_template_part('nav', 'below');
get_footer();
