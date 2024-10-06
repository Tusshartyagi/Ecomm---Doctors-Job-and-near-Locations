<?php

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('product', $product); ?> data-id="content-product-list-1">
    <div class="product-block-list product-block-list-1">
        <div class="left">
            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="menu-thumb">
                <?php echo wp_kses_post($product->get_image()); ?>
            </a>
        </div>
        <div class="right">
            <?php woocommerce_template_loop_product_title(); ?>
            <?php woocommerce_template_loop_price(); ?>
        </div>
    </div>
</li>
