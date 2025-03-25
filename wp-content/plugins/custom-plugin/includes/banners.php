<?php

if (!defined('ABSPATH')) {
    exit;
}


// Registrar el Custom Post Type para Banners
function banner_manager_register_post_type()
{
    $icon = 'dashicons-images-alt2';
    $labels = array(
        'name'          => 'Banners',
        'singular_name' => 'Banner',
        'menu_name'     => 'Banners Home Page',
        'add_new'            => 'Añadir Nuevo',
        'add_new_item'       => 'Añadir Nuevo Banner',
        'edit_item'     => 'Editar Banner',
        'new_item'      => 'Nuevo Banner',
        'view_item'     => 'Ver Banner',
        'view_items'    => 'Ver Banners',
        'search_items'  => 'Buscar Banners',
        'not_found'     => 'No se encontraron Banners',
        'not_found_in_trash' => 'No hay Banners en la papelera',
    );

    $args = array(
        'labels'        => $labels,
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 5,
        'supports'      => array('title', 'thumbnail'),
        'has_archive'   => false,
        'rewrite'       => array('slug' => 'banners'),
        'menu_icon'     => $icon,
    );

    register_post_type('banner', $args);
}
add_action('init', 'banner_manager_register_post_type');

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

// Guardar meta box
function banner_manager_save_meta_box($post_id)
{
    if (array_key_exists('banner_link', $_POST)) {
        update_post_meta($post_id, '_banner_link', sanitize_text_field($_POST['banner_link']));
    }
}
add_action('save_post', 'banner_manager_save_meta_box');



function banner_manager_banners_page()
{
    $selected_products = get_option('banner_manager_featured_products', array());
?>
    <div class="wrap">
        <h1>Seleccionar Ofertas del Día</h1>
        <form method="post" action="options.php">
            <?php settings_fields('banner_manager_options'); ?>
            <?php do_settings_sections('banner_manager_options'); ?>

            <table class="display">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Producto</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => -1,
                    );
                    $products = new WP_Query($args);

                    while ($products->have_posts()) {
                        $products->the_post();
                        $product = wc_get_product(get_the_ID());
                        $checked = in_array(get_the_ID(), $selected_products) ? 'checked' : '';
                        echo '<tr>';
                        echo '<td><input type="checkbox" name="banner_manager_featured_products[]" value="' . get_the_ID() . '" ' . $checked . ' /></td>';
                        echo '<td>' . get_the_title() . '</td>';
                        echo '<td>' . $product->get_price_html() . '</td>';
                        echo '</tr>';
                    }
                    wp_reset_postdata();
                    ?>
                </tbody>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
<?php
}
