<?php
if (!defined('ABSPATH')) {
    exit;
}

// Registrar configuración de ofertas
function banner_manager_register_offers_setting()
{
    register_setting('banner_manager_options', 'banner_manager_featured_products');
}
add_action('admin_init', 'banner_manager_register_offers_setting');

// Página de administración para Ofertas del Día
function banner_manager_offers_page()
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
