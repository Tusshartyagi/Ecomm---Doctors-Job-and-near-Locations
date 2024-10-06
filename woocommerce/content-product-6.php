<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('product-style-6', $product); ?> data-id="content-product-6">
    <?php do_action('woocommerce_before_shop_loop_item'); ?>
    <div class="product-block">
        <div class="product-transition">
            <?php
            do_action('woocommerce_before_shop_loop_item_title');
            matico_template_loop_product_thumbnail();
            ?>
            <?php
            woocommerce_template_loop_product_link_open();
            woocommerce_template_loop_product_link_close();
            ?>
        </div>
        <div class="product-summary">
            <?php
            woocommerce_template_loop_rating();
            do_action('woocommerce_shop_loop_item_title');
            do_action('woocommerce_after_shop_loop_item_title');
            matico_woocommerce_time_sale();
            matico_woocommerce_deal_progress();
            ?>
            <div class="bottom">
                <?php woocommerce_template_loop_add_to_cart(); ?>
                <div class="group-action ">
                    <div class="shop-action">
                        <?php
                        matico_wishlist_button();
                        matico_compare_button();
                        matico_quickview_button();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php do_action('woocommerce_after_shop_loop_item'); ?>
</li>
