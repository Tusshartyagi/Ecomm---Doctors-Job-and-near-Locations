
		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'matico_before_footer' );
    if (matico_is_elementor_activated() && function_exists('hfe_init') && (hfe_footer_enabled() || hfe_is_before_footer_enabled())) {
        do_action('hfe_footer_before');
        do_action('hfe_footer');
    } else {
        ?>

        <footer id="colophon" class="site-footer" role="contentinfo">
            <?php
            /**
             * Functions hooked in to matico_footer action
             *
             * @see matico_footer_default - 20
             *
             *
             */
            do_action('matico_footer');

            ?>

        </footer><!-- #colophon -->

        <?php
    }

		/**
		 * Functions hooked in to matico_after_footer action
		 * @see matico_sticky_single_add_to_cart 	- 999 - woo
		 */
		do_action( 'matico_after_footer' );
	?>

</div><!-- #page -->
<?php /*<a href="#" class="wp-video-popup">Play Video</a>
 echo do_shortcode('[wp-video-popup video="https://www.youtube.com/watch?v=YlUKcNNmywk&autoplay=1"]
'); */ ?>
<?php

/**
 * Functions hooked in to wp_footer action
 * @see matico_template_account_dropdown 	- 1
 * @see matico_mobile_nav - 1
 * @see matico_render_woocommerce_shop_canvas - 1 - woo
 */

wp_footer();

if (is_page( 'consultation' ) ) { ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>    
<script type="text/javascript" src="<?php echo 
get_stylesheet_directory_uri();   ?>/assets/js/jquery.convform.js"></script>
<script>

	jQuery(document).ready(function($){		
			convForm = jQuery('#chat').convform({selectInputStyle: 'disable'});
			console.log(convForm);		
		});
	
	</script>

<?php  } ?>

	
	


<script>
	
	function calcualtedays_ind() {
    var medicine = jQuery("#medicine").val();
    var daysout = jQuery("#daysout").val();
    var error = 0;
    if (medicine == "") {
        jQuery(".showerror").show();
        error++;
    }
    if (daysout == "") {
        jQuery(".showerrordays").show();
        error++;
    }
    if (error == 0) {
        jQuery(".showerror").hide();
        jQuery(".showerrordays").hide();
        var result = "";
        if (medicine == 'lariam-250mg-tablets') {            
			result = parseInt(3)+parseInt(daysout)+parseInt(4);
        } else if( medicine == 'atovaquone-proguanil-generic-malarone' || medicine == 'malarone') {
			result = parseInt(2)+parseInt(daysout)+parseInt(7);
        } else if( medicine == 'doxycycline-100mg-capsules'){	
			result = parseInt(2)+parseInt(daysout)+parseInt(28);
		} else {	
			
		}
		
        jQuery(".resultdiv").html("<span>You need to order : " + result + " Tablets</span>");
    }
}
function resetform_ind() {
    jQuery("#calculateForm")[0].reset();
}
	jQuery(document).ready(function($){	
		
			  $("#dokan-primary #dokan-content .dokan-store-products-filter-area").prepend("<h5 style='    display: inline;'>Search this vendor only</h5><i class='focusOnClick' style='color:blue; margin-left: 10px;'>Click here to search entire site</i>");	
			  
			   $("#dokna_product_search-2 .widget-title").after("<p><i class='focusOnClickvendorShop' style='color:blue; margin-left: 10px;'>Click here to search below vendor site only</i></p>");	
			  
			  
			jQuery(".focusOnClick").click(function(){ 
				jQuery('.dokan-ajax-search-suggestion').focus();
			});	
			jQuery(".focusOnClickvendorShop").click(function(){ 
				jQuery('.dokan-store-products-filter-search').focus();
			});	
		});
		

	
	</script> 
	


</body>
</html>
