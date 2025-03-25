<?php
if (!defined('ABSPATH')) {
    exit;
}

function banner_manager_offers_shortcode()
{
    $product_ids = get_option('banner_manager_featured_products', '');
    if (!$product_ids) return '';
    $ids = explode(',', $product_ids);
    $output = '<div class="offers-container">';

    foreach ($ids as $id) {
        $product = wc_get_product(trim($id));
        if ($product) {
            $output .= '<div class="offer-item">';
            $output .= '<a href="' . get_permalink($product->get_id()) . '">' . $product->get_image() . '<br>' . $product->get_name() . '</a>';
            $output .= '</div>';
        }
    }

    $output .= '</div>';
    return $output;
}
add_shortcode('banner_offers', 'banner_manager_offers_shortcode');
