<?php

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('product', $product); ?>>
    <div class="product-block-list product-block-list-3">
        <div class="left">
            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="menu-thumb">
                <?php echo wp_kses_post($product->get_image()); ?>
            </a>
            <div class="group-action">
                <div class="shop-action">
                    <?php
                    matico_wishlist_button();
                    matico_compare_button();
                    matico_quickview_button();
                    do_action('matico_woocommerce_product_loop_action');
                    ?>
                </div>
            </div>
        </div>
        <div class="right">
            <?php woocommerce_template_loop_rating(); ?>
            <?php woocommerce_template_loop_product_title(); ?>
            <?php woocommerce_template_loop_price(); ?>
        </div>
        <div class="product-caption-bottom">
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
    </div>
</li>
