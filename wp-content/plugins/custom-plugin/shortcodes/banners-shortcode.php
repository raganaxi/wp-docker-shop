<?php
if (!defined('ABSPATH')) {
    exit;
}

function banner_manager_carousel_shortcode()
{
    $args = array(
        'post_type'      => 'banner',
        'posts_per_page' => -1,
    );
    $query = new WP_Query($args);

    ob_start();
?>
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="swiper-slide">
                    <?php
                    $link = get_post_meta(get_the_ID(), '_banner_link', true);
                    $image = get_the_post_thumbnail_url(get_the_ID(), 'banner_thumbnail');
                    if ($image) :
                        if ($link) echo '<a href="' . esc_url($link) . '">';
                        echo '<img src="' . esc_url($image) . '" alt="' . esc_attr(get_the_title()) . '" />';
                        if ($link) echo '</a>';
                    endif;
                    ?>
                </div>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('banner_carousel', 'banner_manager_carousel_shortcode');
