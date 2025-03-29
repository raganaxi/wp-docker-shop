<?php

if (!defined('ABSPATH')) {
    exit;
}


function product_offers_seeder()
{
    if (!class_exists('WooCommerce')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Este plugin requiere WooCommerce para funcionar.');
    }

    // Lista de productos de farmacia a agregar
    $products = [
        [
            'title' => 'Paracetamol 500mg',
            'price' => 100.00,
            'sale_price' => 75.00,
            'image' => 'https://res.cloudinary.com/prixz/image/upload/d_default_product_txh9zg.svg,q_auto/items/9780201379633.webp',
        ],
        [
            'title' => 'Ibuprofeno 400mg',
            'price' => 120.00,
            'sale_price' => 90.00,
            'image' => 'https://res.cloudinary.com/prixz/image/upload/d_default_product_txh9zg.svg,q_auto/items/9780201379633.webp',
        ],
        [
            'title' => 'Vitamina C 1000mg',
            'price' => 150.00,
            'sale_price' => 110.00,
            'image' => 'https://res.cloudinary.com/prixz/image/upload/d_default_product_txh9zg.svg,q_auto/items/9780201379633.webp',
        ],

        [
            'title' => 'Amoxicilina 500mg',
            'price' => 200.00,
            'sale_price' => 150.00,
            'image' => 'https://res.cloudinary.com/prixz/image/upload/w_500/q_auto/f_auto/items/7501349021570.webp',
        ],
        [
            'title' => 'Omeprazol 20mg',
            'price' => 180.00,
            'sale_price' => 130.00,
            'image' => 'https://res.cloudinary.com/prixz/image/upload/w_500/q_auto/f_auto/items/9780201379628.webp',
        ],
    ];

    foreach ($products as $product_data) {
        // Verificar si el producto ya existe para evitar duplicados
        $existing_product = get_page_by_title($product_data['title'], OBJECT, 'product');

        if (!$existing_product) {
            // Crear nuevo producto
            $product = new WC_Product_Simple();
            $product->set_name($product_data['title']);
            $product->set_regular_price($product_data['price']);
            $product->set_sale_price($product_data['sale_price']);
            $product->set_status('publish');
            $product->set_catalog_visibility('visible');
            $product->set_sku(sanitize_title($product_data['title']));
            $product->set_manage_stock(false);
            $product->set_stock_status('instock');

            // Establecer imagen destacada (si existe)
            if (!empty($product_data['image'])) {
                $image_id = product_manager_upload_image($product_data['image']);
                if ($image_id) {
                    $product->set_image_id($image_id);
                }
            }

            // Guardar producto
            $product_id = $product->save();
        }
    }
}


// Función para subir imágenes a la librería de WordPress
function product_manager_upload_image($image_url)
{
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $tmp = download_url($image_url);
    if (is_wp_error($tmp)) {
        return 0;
    }

    $file_array = [
        'name'     => basename($image_url),
        'tmp_name' => $tmp,
    ];

    $id = media_handle_sideload($file_array, 0);

    if (is_wp_error($id)) {
        @unlink($tmp);
        return 0;
    }

    return $id;
}


// funcion para delete todos los productos
function product_manager_delete_all_products()
{
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
    );

    $products = new WP_Query($args);

    while ($products->have_posts()) {
        $products->the_post();
        wp_delete_post(get_the_ID(), true);
    }

    wp_reset_postdata();
}
