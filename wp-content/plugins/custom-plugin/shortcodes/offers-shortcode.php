<?php
if (!defined('ABSPATH')) {
    exit;
}

function render_offers_carousel()
{
    $products = getOffersProducts();

    if (empty($products)) {
        return renderEmptyProducts();
    }

    return renderSection($products);
}

function getOffersProducts(): array
{
    $selected_products = get_option('banner_manager_featured_products', array());

    if (!is_array($selected_products) || empty($selected_products)) {
        return array();
    }

    $products = array();
    foreach ($selected_products as $id) {
        $product = wc_get_product($id);
        if ($product) {
            $products[] = $product;
        }
    }

    return $products;
}

function renderEmptyProducts(): string
{
    return '<div class="no-offers">' .
        __('No hay productos en oferta en este momento', 'textdomain') .
        '</div>';
}
function renderSection(array $products): string
{
    ob_start();
?>

    <div class="offers-section">
        <div class="offers-header">
            <h2 class="offers-title"><?php _e('Ofertas del Día', 'textdomain'); ?></h2>
            <div class="offers-nav-btns">
                <div class="swiper-button-next swiper-nav-btn"></div>
                <div class="swiper-button-prev swiper-nav-btn"></div>
            </div>
        </div>
        <div class="offers-container swiper mySwiperOffers">
            <div class="swiper-wrapper">
                <?php foreach ($products as $product) : ?>
                    <div class="swiper-slide">
                        <div class="product-card">
                            <div class="product-card__image">
                                <?php if ($product->is_on_sale()) : ?>
                                    <span class="product-card__badge"><?php _e('Oferta del Día', 'textdomain'); ?></span>
                                <?php endif; ?>
                                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                                    <?php echo $product->get_image('medium'); ?>
                                </a>
                            </div>
                            <div class="product-card__info">
                                <h2 class="product-card__title"><?php echo esc_html($product->get_name()); ?></h2>
                                <p class="product-card__description"><?php echo esc_html($product->get_short_description()); ?></p>
                                <div class="product-card__price-row">
                                    <?php if ($product->is_on_sale()) : ?>
                                        <div class="product-card__price">
                                            <del class="product-card__price-regular"><?php echo wc_price($product->get_regular_price()); ?></del>
                                            <span class="product-card__price-sale"><?php echo wc_price($product->get_sale_price()); ?></span>
                                        </div>
                                    <?php else : ?>
                                        <span class="product-card__price"><?php echo $product->get_price_html(); ?></span>
                                    <?php endif; ?>
                                    <?php echo addToCart($product); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

<?php
    return ob_get_clean();
}

function addToCart(WC_Product $product): string
{
    return sprintf(
        '<a href="%s" data-quantity="1" class="product-card__btn add_to_cart_button ajax_add_to_cart" %s>%s</a>',
        esc_url($product->add_to_cart_url()),
        wc_implode_html_attributes(array(
            'data-product_id'  => $product->get_id(),
            'data-product_sku' => $product->get_sku(),
            'aria-label'       => $product->add_to_cart_description(),
            'rel'              => 'nofollow',
        )),
        esc_html($product->add_to_cart_text())
    );
}
add_shortcode('offers_carousel', 'render_offers_carousel');
