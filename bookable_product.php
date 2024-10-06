<?php 

/* Template Name: Bookable Product */


get_header();
?>
<style type="text/css">
.pagination {
display: inline-block;
width: 100%;
}
</style>
<?php
// Define your custom post type
$custom_post_type = 'product';

// Define the query arguments
$args = array(
    'post_type'      => $custom_post_type,
    'posts_per_page' => 9, // Adjust the number of posts per page as needed
    'paged'          => get_query_var('paged') ? get_query_var('paged') : 1,
    'tax_query' => array( array(
        'taxonomy' => 'product_type',
        'terms'    => array( 'booking' ),
        'field'    => 'slug',
   )),
);

// Instantiate the WP_Query
$custom_query = new WP_Query($args);
?>

<style type="text/css">
.page-template-jobs .pagination {
    display: flex;
    width: 100%;
    flex-wrap: wrap;
    align-items: center;
}

.page-template-jobs .pagination a.page-numbers {
    text-decoration: none;
    color: #000;
    float: left;
    padding: 8px 16px;
}

.pagination span.current {
    background-color: #4CAF50;
    color: #FFF;
    padding: 4px 10px;
}
</style>
<div id="dokan-seller-listing-wrap" class="grid-view">

    <div class="matico-products-spacing ">
  <div class="matico-products-overflow">
    <ul class="products columns-4">
<?php
// The Loop
if ($custom_query->have_posts()) :
    while ($custom_query->have_posts()) : $custom_query->the_post();
        // Your custom post type loop content goes here
       
        // Replace $post_id with the actual ID of the post you want to get the URL for
        $post_id = get_the_ID();

        // Get the post URL by ID
        $post_url = get_permalink($post_id);

        
        $product_id = $post_id;

        // Get the product object by ID
        $thumbnail_id = get_post_thumbnail_id($product_id);

        // Check if the product exists
        if ($thumbnail_id) {

            // Get the image URL
            $image_url = wp_get_attachment_image_url($thumbnail_id, 'full');

            // Display the image
            $image_url_p = '<img width="700" height="700" src="' . esc_url($image_url) . '" alt="' . esc_attr($product->get_name()) . '">';
        } else {
            $image_url_p = '<img src="https://allchemists.co.uk/wp-content/uploads/woocommerce-placeholder-800x800.png" alt="https://allchemists.co.uk/wp-content/uploads/woocommerce-placeholder-800x800.png">';
        }

        //the_content();

        ?> 
      <li class="product-style-default product type-product post-39129 status-publish first instock product_cat-cosmetics-toiletries product_cat-dental product_cat-medicines-vitamins product_cat-mouth-hygiene product_cat-travel has-post-thumbnail shipping-taxable purchasable product-type-simple" data-id="">
        <div class="product-block">
          <div class="product-transition">
            <div class="product-image">
              <?php echo $image_url_p; ?>
            </div>
            <a href="<?php echo $post_url; ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"></a>
          </div>
          <div class="product-caption">
            <div class="count-review">
              <div class="star-rating"></div>
              <span>(0 reviews)</span>
            </div>
            <h3 class="woocommerce-loop-product__title">
              <a href="<?php echo $post_url; ?>"><?php the_title(); ?></a>
            </h3>
           <!--  <span class="price">
              <span class="woocommerce-Price-amount amount">
                <bdi>
                  <span class="woocommerce-Price-currencySymbol">£</span>3.24 </bdi>
              </span>
            </span> -->
          </div>
          <div class="product-caption-bottom">
            <a href="<?php echo $post_url; ?>" class="button" rel="nofollow">View Clinic</a>
          </div>
        </div>
      </li>
 <?php
    endwhile;

    // Pagination
    $total_pages = $custom_query->max_num_pages;
    if ($total_pages > 1) {
        echo '<div class="pagination">';
        echo paginate_links(array(
            'base'    => get_pagenum_link(1) . '%_%',
            'format'  => 'page/%#%',
            'current' => max(1, get_query_var('paged')),
            'total'   => $total_pages,
        ));
        echo '</div>';
    }

endif;?>

    </ul>
  </div>
</div>

</div>
<?php
// Restore original post data
wp_reset_postdata();

//do_action( 'matico_sidebar' );
get_footer();
