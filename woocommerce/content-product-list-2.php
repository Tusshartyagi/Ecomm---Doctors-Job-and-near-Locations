<?php

defined('ABSPATH') || exit;

global $product;

$flag = productIsShow($product);
	
if (empty($product) || !$product->is_visible()  ||  !empty($flag) ) {
    return;
}
?>
<li <?php wc_product_class('product', $product); ?> data-id="content-product-list-2">
    <div class="product-block-list product-block-list-2">
        <div class="left">
            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="menu-thumb">
                <?php echo wp_kses_post($product->get_image()); ?>
            </a>
        </div>
        <div class="right">
            <?php woocommerce_template_loop_rating(); ?>
            <?php woocommerce_template_loop_product_title(); ?>
            <?php woocommerce_template_loop_price(); ?>
        </div>
    </div>
</li>
