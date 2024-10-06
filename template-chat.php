<?php
/**
 *  Dokan Dashboard Template
 *
 *  Dokan Main Dahsboard template for Fron-end
 *
 *  @since 2.4
 *
 *  @package dokan
 */
$author_id = get_current_user_id(); 

?>
<div class="dokan-dashboard-wrap">
    <?php
        /**
         *  dokan_dashboard_content_before hook
         *
         *  @hooked get_dashboard_side_navigation
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_before' );
    ?>

    <div class="dokan-dashboard-content">

        <?php
            /**
             *  dokan_dashboard_content_before hook
             *
             *  @hooked show_seller_dashboard_notice
             *
             *  @since 2.4
             */
            do_action( 'dokan_help_content_inside_before' );
        ?>

	<div class="dokan-dashboard-content dokan-product-listing" style="width:100%;">
		<article class="dokan-product-listing-area">
			<?php echo do_shortcode('[get_chat_list]'); ?>
		</article>
	</div>

         <?php
            /**
             *  dokan_dashboard_content_inside_after hook
             *
             *  @since 2.4
             */
            do_action( 'dokan_dashboard_content_inside_after' );
        ?>


    </div><!-- .dokan-dashboard-content -->

    <?php
        /**
         *  dokan_dashboard_content_after hook
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_after' );
    ?>

</div><!-- .dokan-dashboard-wrap -->
<style>
.customHeading {
	   width: 50%;
	   float:left;
	
}
.customButoon {
padding: 10px 20px;
    border-radius: 9px;

    color: #fff;
    width: 18%;
    background: #4DC42A;
    text-align: center;
	float:right;
}
.customButoon a {
  color: #fff;
}

.custom_success_message{ 

    background: aliceblue;
    color: green;
    border: 1px solid;
    padding: 10px;
    text-align: center;
}

.custom_error_message{ 

    background: blanchedalmond;
    color: red;
    border: 1px solid;
    padding: 10px;
    text-align: center;
}
</style>
