<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	
	<?php endif; ?>


</header>
	 <?php 
 $term = get_queried_object();  
 // print_r();
  $woo_cat_id = $term->term_id;
  if ($woo_cat_id && have_rows('questions', 'product_cat_' . $woo_cat_id) && $_SESSION['question_details'][$woo_cat_id] == '' ) {
	 
	  ?> 
	<div class="start_consultaion"> 
				<a href="<?php bloginfo('url'); ?>/consultation/?term_id=<?php echo $woo_cat_id; ?>" class="button_width blue_clr">Start Consultaion</a>
				</div>
		 
	<?php  }  ?>
<?php
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();
	

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	
	
	
	
	
	do_action( 'woocommerce_after_shop_loop' );
	
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}
/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	
	 echo '<br/><br/>';
	 
		 $cate = get_queried_object();
	 $cateslug = $cate->slug;	if($cateslug == 'weight-loss'){	?>	
		<div class="bmi-calculator">
			 <?php echo do_shortcode('[cc-bmi]'); ?>
			<?php //dynamic_sidebar('bmi-calculator'); ?>			
			<div class="right-part">
			<h3>Your BMI and Healthy Weight Range</h3>
				<p>There's no "perfect weight" that fits everyone. BMI, or body mass index, measures how healthy your weight is based on how tall you are. It gives you a clue to your risk for weight-related health problems.</p>
				<p>If you're over 20 and not pregnant, find out what your number is and what it means.</p>
			</div>
				<?php 	} 
				elseif($cateslug == 'anti-malaria'){			?>	
<div class="travlingform">
			<legend>Calculation of malaria tablets  </legend>
		<form action="" method="POST" id="calculateForm">
			<div class="form-group"><label>Select the medicine <span>*</span></label>
			<select class="" name="medicine"  id="medicine" onchange="getval(this);">
				<option value="">-select here-</option>
				<option value="1" >Lariam 250mg</option>
				<option value="2">Atovaquone/Proguanil (Generic Malarone)</option>
				<option value="3">Doxycycline 100mg capsules</option>
				<option value="4">Malarone</option>
			</select>
			<div class="showerror" style="display:none; color:red;">Please select the medicine
			</div>
		</div>
		<div class="form-group">
			<label class="dayweektext">NUMBER OF DAYS YOU ARE STAYING IN YOUR COUNTRY OF DESTINATION<span>*</span></label>
			<input class="" type="text" name="daysout" id="daysout" value="">
			<div class="showerrordays" style="display:none; color:red;">This is required field</div>
		</div>
		<div class="submitbuttons">	
		 <a class="submitbutton button" href="javascript:void(0)" onClick="calcualtedays()">Calculate</a>
		 <a class="submitbutton_reset button" href="javascript:void(0)" onClick="resetform()">Reset</a>
		</div>
		<div class="resultdiv">
		</div>
		</form>
</div>

	<script>	 function getval(set)	{ 	var selectedValue = set.value;		if(selectedValue == 1){  			jQuery(".dayweektext").html('NUMBER OF WEEKS YOU ARE STAYING IN YOUR COUNTRY OF DESTINATION<span>*</span>');		} else {			jQuery(".dayweektext").html('NUMBER OF DAYS YOU ARE STAYING IN YOUR COUNTRY OF DESTINATION<span>*</span>');		}	}		function calcualtedays(){ 	var medicine = jQuery("#medicine").val();	var daysout = jQuery("#daysout").val(); var error = 0; if(medicine == ''){ jQuery(".showerror").show(); error++; } if(daysout == ''){ jQuery(".showerrordays").show(); error++; } if(error == 0){ jQuery(".showerror").hide(); jQuery(".showerrordays").hide(); var result = ''; if(medicine == 4 || medicine == 2){  result = parseInt(2)+parseInt(daysout)+parseInt(7); } else if(medicine == 1){ result = parseInt(3)+parseInt(daysout)+parseInt(4);  } else { result = parseInt(2)+parseInt(daysout)+parseInt(28);  } jQuery(".resultdiv").html('<span>You need to order : '+result + ' Tablets</span>');  } }	 function resetform(){		jQuery('#calculateForm')[0].reset();	} 	 jQuery(document).ready(function(){ 				jQuery('#daysout').keyup(function(e)		{			if (/\D/g.test(this.value))			{							this.value = this.value.replace(/\D/g, '');			}		});	}); 		</script>	<?php				}
	do_action( 'woocommerce_archive_description' );

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );
	
/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
