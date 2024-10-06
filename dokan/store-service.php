<?php
/**
 * The Template for displaying services.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */

$store_user = get_userdata( get_query_var( 'author' ) );
$store_info = dokan_get_store_info( $store_user->ID );
$map_location = isset( $store_info['location'] ) ? esc_attr( $store_info['location'] ) : '';
$layout       = get_theme_mod( 'store_layout', 'left' );

get_header( 'shop' );


/* echo '<pre>'; print_r($store_user); echo '</pre>'; */
$store_user->ID; 
/* echo '<pre>'; print_r($store_info); echo '</pre>'; */

?>

<?php do_action( 'woocommerce_before_main_content' ); ?>

<div class="dokan-store-wrap layout-<?php echo esc_attr( $layout ); ?>">

    <?php if ( 'left' === $layout ) { ?>
        <?php dokan_get_template_part( 'store', 'sidebar', array( 'store_user' => $store_user, 'store_info' => $store_info, 'map_location' => $map_location ) ); ?>
    <?php } ?>

<div id="dokan-primary" class="dokan-single-store">
    <div id="dokan-content" class="store-review-wrap woocommerce" role="main">

        <?php dokan_get_template_part( 'store-header' ); ?>    

        <div id="services">

		<?php 
		
		
			$args = array(
			  'author'        => $store_user->ID, 
			  'post_type' => 'service',
			  'orderby'       =>  'post_date',
			  'order'         =>  'ASC',
			  'posts_per_page' => -1 // no limit
			);


			$current_user_posts = get_posts( $args );
			
			if(count($current_user_posts) > 0 ){
		
			foreach($current_user_posts as $custom_post) { 	
		
			?>
				<h5><?php echo get_the_title($custom_post->ID); ?></h5>
				<p><i class="fas fa-shopping-bag"></i> <?php echo  $custom_post->post_content; ?></p>
				<hr/>
		
			<?php  } 
			} else { 
			?>
			<p>No Service Found</p>
			<?php  } ?>
		
		
	
          
        </div>

       

    </div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

    <?php if ( 'right' === $layout ) { ?>
        <?php dokan_get_template_part( 'store', 'sidebar', array( 'store_user' => $store_user, 'store_info' => $store_info, 'map_location' => $map_location ) ); ?>
    <?php } ?>

</div><!-- .dokan-store-wrap -->

<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php get_footer(); ?>
