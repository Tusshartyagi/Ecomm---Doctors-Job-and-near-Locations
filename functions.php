<?php
/**
 * Theme functions and definitions.
 */
 
include('question-module.php');
include('custom-api.php');
function parent_theme_enqueue_styles()
{

	
	// Add Scripts
	 if (!is_page( 'Home' ) ){
		wp_enqueue_style('jquery-ui-style', get_stylesheet_directory_uri() . '/assets/css/jquery-ui.css', 'jquery-ui-style');	 
		 
	wp_register_script('jquery-ui-js', get_stylesheet_directory_uri() . "/assets/js/jquery-ui.js", "", "", true);
	wp_enqueue_script('jquery-ui-js');
	}
	wp_register_script('custom-js', get_stylesheet_directory_uri() . "/assets/js/custom.js", "", 1.2, true);
	wp_enqueue_script('custom-js');
	wp_localize_script('custom-js', 'admin_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

	wp_enqueue_style('custom_css', get_stylesheet_directory_uri() . '/assets/css/hk_custom.css', array(), 1.3, 'all');	 
	wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/custom-style.css', array(), 1.3, 'all');	 
}
add_action('wp_enqueue_scripts', 'parent_theme_enqueue_styles');
function enqueue_custom_scripts() {
    wp_enqueue_script('jquery');
    wp_localize_script('jquery', 'admin_ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');


/**
 * Add custom product data tabs
 */
function woo_new_product_tab($tabs)
{

	$tabs['uses__instructions_tab'] = array(
		'title' 	=> __('Uses / Instructions', 'woocommerce'),
		'priority' 	=> 10,
		'callback' 	=> 'woo_instructions_callback'
	);

	$tabs['warnings_tab'] = array(
		'title' 	=> __('Warnings', 'woocommerce'),
		'priority' 	=> 11,
		'callback' 	=> 'woo_warnings_callback'
	);

	$tabs['side_effects_tab'] = array(
		'title' 	=> __('Side Effects', 'woocommerce'),
		'priority' 	=> 12,
		'callback' 	=> 'woo_side_effects_callback'
	);

	$tabs['ingredients_tab'] = array(
		'title' 	=> __('Ingredients', 'woocommerce'),
		'priority' 	=> 12,
		'callback' 	=> 'woo_ingredients_callback'
	);

	$tabs['reviews']['priority'] = 40;

	return $tabs;
}
add_filter('woocommerce_product_tabs', 'woo_new_product_tab');
 

/**
 * Callback function for Instructions tab content.
 */
function woo_instructions_callback()
{

	$prod_id = get_the_ID();
	echo '<h2>' . __('Uses / Instructions', 'woocommerce') . '</h2>';
	echo get_field('uses__instructions', $prod_id);
}
/**
 * Callback function for Warnings tab content.
 */
function woo_warnings_callback()
{

	$prod_id = get_the_ID();
	echo '<h2>' . __('Warnings', 'woocommerce') . '</h2>';
	echo get_field('warnings', $prod_id);
}
/**
 * Callback function for side effects tab content.
 */
function woo_side_effects_callback()
{

	$prod_id = get_the_ID();
	echo '<h2>' . __('Side Effects', 'woocommerce') . '</h2>';
	echo get_field('side_effects', $prod_id);
}
/**
 * Callback function for Ingredients tab content.
 */
function woo_ingredients_callback()
{

	$prod_id = get_the_ID();
	echo '<h2>' . __('Ingredients', 'woocommerce') . '</h2>';
	echo get_field('ingredients', $prod_id);
}



/**
 * Add download link and safe for pregnancy icon on single product page.
 */
function woo_single_download_button()
{

	global $product;

	// Add download link as 'Leaflet' button.
	/* print_r($product); */
	// If product is variable product.
	if ($product->is_type('variable')) {
		$variations = $product->get_available_variations();

		foreach ($variations as $key => $value) {
			$product_variation = new WC_Product_Variation($value['variation_id']);

			foreach ($product_variation->get_files() as $key => $each_download) {
				echo '<a class="single-btn-info" href="' . $each_download["file"] . '">' . __('Information Leaflet', 'woocommerce') . '</a>';
			}
		}
	} else {
		// It's for Simple product.		
		foreach ($product->get_files() as $key => $each_download) {
			echo '<a class="single-btn-info" href="' . $each_download["file"] . '">' . __('Information Leaflet', 'woocommerce') . '</a>';
		}
	}

	echo "<div class='safedata'>";
	// Add 'Safe for pregnancy icon'
	if (get_post_meta($product->get_id(), 'safe_for_pregnancy', true)) {
		echo '<img title="Safe for pregnancy" class="safe-preg-icon" src="' . get_stylesheet_directory_uri() . '/images/pregnancy-safe.png">';
	}
	// Add 'Suitable for vegetarians icon'
	if (get_post_meta($product->get_id(), 'suitable_for_vegetarians', true)) { 
		echo '<img title="Suitable for vegetarians" class="safe-preg-icon" src="' . get_stylesheet_directory_uri() . '/images/SF-Vegetarians.jpg">';
	}
	// Add 'Suitable for vegans icon'
	if (get_post_meta($product->get_id(), 'suitable_for_vegans', true)) {
		echo '<img title="Suitable for vegans" class="safe-preg-icon" src="' . get_stylesheet_directory_uri() . '/images/Vegan.png">';
	}
	// Add 'Suitable for breastfeeding icon'
	if (get_post_meta($product->get_id(), 'suitable_for_breastfeeding', true)) {
		echo '<img title="Suitable for breastfeeding" class="safe-preg-icon" src="' . get_stylesheet_directory_uri() . '/images/breastfeeding-symbol.jpg">';
	}
	// Add 'Suitable for halal icon'
	if (get_post_meta($product->get_id(), 'halal', true)) {
		echo '<img title="Suitable for halal" class="safe-preg-icon" src="' . get_stylesheet_directory_uri() . '/images/halal.jpg">';
	}
	// Add 'Suitable for brand may vary icon'
	if (get_post_meta($product->get_id(), 'brands_may_vary', true)) {
		echo '<img title="Brands may vary" class="safe-preg-icon" src="' . get_stylesheet_directory_uri() . '/images/BRAND-MAY-VARY.jpg">';
	}
	
	
	// Add 'Suitable for Organic'
	if (get_post_meta($product->get_id(), 'organic', true)) {
		echo '<img title="Organic" class="safe-preg-icon" src="' . get_stylesheet_directory_uri() . '/images/organic.jpg">';
	}
	
	
		// Add 'Suitable for  Lactose free'
	if (get_post_meta($product->get_id(), 'lactose_free', true)) {
		echo '<img title="Lactose free" class="safe-preg-icon" src="' . get_stylesheet_directory_uri() . '/images/lactose free.png">';
	}
	
		// Add 'Suitable for  kosher '
	if (get_post_meta($product->get_id(), 'kosher', true)) {
		echo '<img title="Kosher" class="safe-preg-icon" src="' . get_stylesheet_directory_uri() . '/images/kosher.jpg">';
	}
	
		// Add 'Suitable for  gluten free'
	if (get_post_meta($product->get_id(), 'gluten_free', true)) {
		echo '<img title="Gluten free" class="safe-preg-icon" src="' . get_stylesheet_directory_uri() . '/images/gluten free.png">';
	}
	 
		// Add 'Suitable for sugar free '
	if (get_post_meta($product->get_id(), 'sugar_free', true)) {
		echo '<img title="Sugar free" class="safe-preg-icon" src="' . get_stylesheet_directory_uri() . '/images/sugar free.jpg">';
	}
	
	
	echo "</div>";
}
add_action('woocommerce_after_add_to_cart_form', 'woo_single_download_button', 5);		 

/**
 * Get re-purchase days limit.
 */
function woo_product_repurchase_days($product_id = 0)
{

	if ($product_id) {
		if (get_field('re-purchase_days', $product_id)) {
			$days = get_field('re-purchase_days', $product_id);
			if ($days >= 1)
				return $days;
		}
	}
	return false;
}


/**
 * Validating product before adding to the cart.
 * @param [type] $passed_validation [description]
 * @param [type] $product_id        [description]
 * @param [type] $quantity          [description]
 */
function woo_add_to_cart_validation($passed_validation, $product_id, $quantity)
{
	global $wpdb;
	// Re-purchase condition according to number of days.
	if (is_user_logged_in()) {
	
		$current_user = wp_get_current_user();

		
		
		if (wc_customer_bought_product($current_user->user_email, $current_user->ID, $product_id)) {

			// Get product re-purchase days.
			$days = woo_product_repurchase_days($product_id); 
			
			//print_r($days); die;		
			if ($days >= 1) {
				// Check user bought product in last number of days
				if (woo_user_has_bought_product_in_days($current_user->ID,  $product_id, $days)) {

					wc_add_notice(woo_repurchase_error_msg($days));
					return $passed_validation = false;
				}
			}
		} /* else if(get_field('questionnaire_enable',$product_id) == 1){
			
				return $passed_validation = false;
		} */
	}

	return $passed_validation;
}
add_filter('woocommerce_add_to_cart_validation', 'woo_add_to_cart_validation', 10, 3);


/**
 * Check product has purchased in given number of days
 */
function woo_user_has_bought_product_in_days($user_id = 0,  $product_ids = 0, $days = 0)
{

	global $wpdb;
	$customer_id = $user_id == 0 || $user_id == '' ? get_current_user_id() : $user_id;
	$statuses    = array_map('esc_sql', wc_get_is_paid_statuses());
	$date 		 = date('Y-m-d H:i:s', strtotime("-$days day"));

	if (is_array($product_ids))
		$product_ids = implode(',', $product_ids);

	if ($product_ids !=  (0 || ''))
		$query_line = "AND woim.meta_value IN ($product_ids)";
	else
		$query_line = "AND woim.meta_value != 0";

	// Count the number of products
	$product_count_query = $wpdb->get_col("
			SELECT COUNT(woim.meta_value) FROM {$wpdb->prefix}posts AS p
			INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id
			INNER JOIN {$wpdb->prefix}woocommerce_order_items AS woi ON p.ID = woi.order_id
			INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id
			WHERE p.post_status IN ( 'wc-" . implode("','wc-", $statuses) . "' )
			AND p.post_date > '$date'
			AND pm.meta_key = '_customer_user'
			AND pm.meta_value = $customer_id
			AND woim.meta_key IN ( '_product_id', '_variation_id' )
			$query_line
		");

	// Set the count in a string
	$count = reset($product_count_query);

	// Return a boolean value if count is higher than 0
	return $count > 0 ? true : false;
}

/**
 * Re-purchase error message.
 */
function woo_repurchase_error_msg($days = 0)
{
	return __('Sorry!, you can\'t re-purchase this product before ' . $days . ' day(s)', 'error');
}

add_action( 'wp_ajax_autofill_register_details', 'autofill_register_details' );
function autofill_register_details() 
{
	$logged_user = wp_get_current_user();
	$respone = [];
	$respone['first_name'] = get_user_meta($logged_user->ID, 'first_name', true);
	$respone['last_name'] = get_user_meta($logged_user->ID, 'last_name', true);
	$respone['address'] = get_user_meta($logged_user->ID, 'billing_address_1', true);//billing_full_address
	$respone['country'] = get_user_meta($logged_user->ID, 'billing_country', true);
	$respone['phone'] = get_user_meta($logged_user->ID, 'billing_phone', true);
	$respone['address_1'] = get_user_meta($logged_user->ID, 'billing_address_1', true);
	$respone['address_2'] = get_user_meta($logged_user->ID, 'billing_address_2', true);
	$respone['postcode'] = get_user_meta($logged_user->ID, 'billing_postcode', true);
	$respone['city'] = get_user_meta($logged_user->ID, 'billing_city', true);
	$respone['state'] = get_user_meta($logged_user->ID, 'billing_state', true);
	$respone['door_number'] = get_user_meta($logged_user->ID, 'billing_door_number', true);
	if (get_user_meta($logged_user->ID, 'billing_gender', true) == 'male') {
		$respone['gender'] = 'Mr.';
	} else {
		$respone['gender'] = 'Mrs.';
	}	
	$respone['dob'] = get_user_meta($logged_user->ID, 'billing_dob', true);
	echo json_encode( $respone ); die;
}

add_action('wp_ajax_nopriv_use_prescription_details', 'use_prescription_details');
add_action( 'wp_ajax_use_prescription_details', 'use_prescription_details' );
function use_prescription_details() {

	global $woocommerce;
	$logged_user = wp_get_current_user();
	$cart_items = $woocommerce->cart->cart_contents;
	$prescription_items = '';
	foreach ($cart_items as $cart_item_key => $cart_item) {
		$prescription_items = $woocommerce->cart->cart_contents[$cart_item_key]['prescription_items'];
	}
	if (!empty($prescription_items) && !is_user_logged_in()) {
		$prescription_items['flag'] = 'true';
		$prescription_items['first_name'] = $prescription_items['first-name'];
		$prescription_items['last_name'] = $prescription_items['last-name'];
		$prescription_items['phone'] = $prescription_items['your-phone'];
		if (!empty($logged_user)) {
			$prescription_items['email_address'] = $logged_user->user_email;
		}
		unset($prescription_items['first-name'], $prescription_items['last-name'], $prescription_items['your-phone']);
		echo json_encode( $prescription_items ); die;
	} else if(is_user_logged_in()) {
		$respone['flag'] = 'true';
		$respone['first_name'] = !empty($prescription_items['first-name']) ? $prescription_items['first-name'] : get_user_meta($logged_user->ID, 'first_name', true);
		$respone['last_name'] = !empty($prescription_items['last-name']) ? $prescription_items['last-name'] : get_user_meta($logged_user->ID, 'last_name', true);
		$respone['address'] = !empty($prescription_items['address']) ? $prescription_items['address'] : get_user_meta($logged_user->ID, 'billing_address_1', true);
		$respone['country_code'] = !empty($prescription_items['country_code']) ? $prescription_items['country_code'] : get_user_meta($logged_user->ID, 'billing_country', true);
		$respone['phone'] = !empty($prescription_items['your-phone']) ? $prescription_items['your-phone'] : get_user_meta($logged_user->ID, 'billing_phone', true);
		$respone['address_1'] = !empty($prescription_items['address_1']) ? $prescription_items['address_1'] : get_user_meta($logged_user->ID, 'billing_address_1', true);
		$respone['address_2'] = !empty($prescription_items['address_2']) ? $prescription_items['address_2'] : get_user_meta($logged_user->ID, 'billing_address_2', true);
		$respone['postcode'] = !empty($prescription_items['postcode']) ? $prescription_items['postcode'] : get_user_meta($logged_user->ID, 'billing_postcode', true);
		$respone['city'] = !empty($prescription_items['city']) ? $prescription_items['city'] : get_user_meta($logged_user->ID, 'billing_city', true);
		$respone['state'] = !empty($prescription_items['state']) ? $prescription_items['state'] : get_user_meta($logged_user->ID, 'billing_state', true);
		$respone['door_number'] = !empty($prescription_items['door_number']) ? $prescription_items['door_number'] : get_user_meta($logged_user->ID, 'billing_door_number', true);
		$email_address = get_user_meta($logged_user->ID, 'billing_email', true);
		$respone['email_address'] = !empty($email_address) ? $email_address : $logged_user->user_email;	
		echo json_encode( $respone ); die;
	} else {
		echo json_encode( array('flag'=>'false') ); die;
	}
}

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Product Questionnaire',
		'menu_title'	=> 'Product Questionnaire',
		'menu_slug' 	=> 'product_questionnaire',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	
	
}

add_filter('gettext', 'change_vendor_string');
function change_vendor_string($translated) {
    $translated = str_ireplace('Company ID/EUID Number', 'GPHC premises registration number', $translated);
    return $translated;
}

/**
 * Check product has purchased in given number of days
 */
function woo_user_with_same_address_has_bought_product_in_days($fields,  $product_ids = 0, $days = 0)
{

	global $wpdb;
	//$customer_id = $user_id == 0 || $user_id == '' ? get_current_user_id() : $user_id;
	$statuses    = array_map('esc_sql', wc_get_is_paid_statuses());
	$date 		 = date('Y-m-d H:i:s', strtotime("-$days day"));
	$billing_address_1 = $fields['billing_address_1'];
	$billing_city = $fields['billing_city'];
	$billing_postcode = $fields['billing_postcode'];
	$billing_email = $fields['billing_email'];
	 if (is_array($product_ids))
		$product_ids = implode(',', $product_ids);

	if ($product_ids !=  (0 || '')) {
		$query_line = "AND woim.meta_value IN ($product_ids)";
	}
	else {
		$query_line = "AND woim.meta_value != 0";	
	}
	
			
 	$product_count_query = $wpdb->get_col("SELECT COUNT(woim.meta_value) FROM {$wpdb->prefix}posts AS p
			INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id
			INNER JOIN {$wpdb->prefix}woocommerce_order_items AS woi ON p.ID = woi.order_id
			INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id
			WHERE p.post_status IN ( 'wc-" . implode("','wc-", $statuses) . "' )
			AND p.post_date > '$date'
			AND ((pm.meta_key = '_billing_address_1' AND pm.meta_value = '".$billing_address_1."') 
			OR (pm.meta_key = '_billing_postcode' AND pm.meta_value = '".$billing_postcode."'))
			AND woim.meta_key IN ( '_product_id', '_variation_id' )
			$query_line
				");					
	$count = reset($product_count_query);
	
	if($count > 0 ) {
		return $count > 0 ? true : false;
	} else {
		
		$product_count_query = $wpdb->get_col("SELECT COUNT(woim.meta_value) FROM {$wpdb->prefix}posts AS p
				INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id
				INNER JOIN {$wpdb->prefix}woocommerce_order_items AS woi ON p.ID = woi.order_id
				INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id
				WHERE p.post_status IN ( 'wc-" . implode("','wc-", $statuses) . "' )
				AND p.post_date > '$date'
				AND ((pm.meta_key = '_billing_email' AND pm.meta_value = '".$billing_email."') 
				)
				AND woim.meta_key IN ( '_product_id', '_variation_id' )
				$query_line
					");		
					
	//	echo $wpdb->last_query;			
		$count = reset($product_count_query);
		return $count > 0 ? true : false;		
		
	}
	
	// Return a boolean value if count is higher than 0
	
}

/**
 * Custom validation for billing first name and last name fields
 *
 * @author Misha Rudrastyh
 * @link https://rudrastyh.com/woocommerce/custom-checkout-validation.html#custom-validation
 */
/* add_action( 'woocommerce_after_checkout_validation', 'user_not_purchase_prohibited_product_same_address', 10, 2 );
 
function user_not_purchase_prohibited_product_same_address( $fields, $errors ){
	
	//echo '<pre>'; print_r($fields); echo '</pre>'; die;
	// Loop over $cart items
	 foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$product_id = $cart_item['product_id'];
		$days = woo_product_repurchase_days($product_id); 	
		//print_r($days); die;		
			if ($days >= 1) {
				// Check user bought product in last number of days
				 $errorcount = woo_user_with_same_address_has_bought_product_in_days($fields,  $product_id, $days); 
				 if ($errorcount) { 
					$errors->add( 'validation', woo_repurchase_error_msg($days) );
					//return $errors;
				 } 
			}
	} 
	
	
	return $errors;
}
 */
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_dokan_company_id_number']);
    return $fields;
}



function dokan_custom_new_seller_created( $vendor_id, $dokan_settings ) {
    $post_data = wp_unslash( $_POST );

    $superintendent_name =  $post_data['superintendent_name'];
	$user_title =  $post_data['user_title'];
	$wholesale_check_box =  $post_data['wholesale_check_box'];
   
    update_user_meta( $vendor_id, 'dokan_custom_superintendent_name', $superintendent_name );
    update_user_meta( $vendor_id, 'dokan_custom_user_title', $user_title );
    update_user_meta( $vendor_id, 'dokan_wholeseller_exists', $wholesale_check_box );
}

add_action( 'dokan_new_seller_created', 'dokan_custom_new_seller_created', 10, 2 );

  /* Add custom profile fields (call in theme : echo $curauth->fieldname;) */ 

add_action( 'dokan_seller_meta_fields', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { ?>

    <?php if ( ! current_user_can( 'manage_woocommerce' ) ) {
            return;
        }
        if ( ! user_can( $user, 'dokandar' ) ) {
            return;
        }
         $superintendent_name  = get_user_meta( $user->ID, 'dokan_custom_superintendent_name', true );
     ?>
	 
	 
	   <p class="form-row form-group form-row-wide">
        <label for="seller-url" class="pull-left"><?php esc_html_e( 'Superintendent Pharmacist Name', 'dokan-lite' ); ?> <span class="required">*</span></label>
        <strong id="url-alart-mgs" class="pull-right"></strong>
        <input type="text" class="input-text form-control" name="superintendent_name" id="superintendent_name" value="<?php echo esc_attr($superintendent_name); ?>" required="required" />
       
    </p>
        
    <?php
 }

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {

if ( ! current_user_can( 'manage_woocommerce' ) ) {
            return;
        }
    update_usermeta( $user_id, 'dokan_custom_superintendent_name', $_POST['dokan_custom_superintendent_name'] );
} 




class Dokan_Setup_Wizard_Override extends Dokan_Seller_Setup_Wizard {

    /**
     * Introduction step.
     */
    public function dokan_setup_introduction() {
        $dashboard_url = dokan_get_navigation_url();
        ?>
        <h1><?php esc_attr_e( 'Welcome to All Chemists !', 'dokan-lite' ); ?></h1>
        <p><?php echo wp_kses( __( 'Thank you for choosing All Chemists to power your online store ! This quick setup wizard will help you configure the basic settings. <strong>It’s completely optional and shouldn’t take longer than two minutes.</strong>', 'dokan-lite' ), [ 'strong' => [] ] ); ?></p>
        <p><?php esc_attr_e( 'No time right now? If you don’t want to go through the wizard, you can skip and return to the Store!', 'dokan-lite' ); ?></p>
        <p class="wc-setup-actions step">
            <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next lets-go-btn dokan-btn-theme"><?php esc_attr_e( 'Let\'s Go!', 'dokan-lite' ); ?></a>
            <a href="<?php echo esc_url( $dashboard_url ); ?>" class="button button-large not-right-now-btn dokan-btn-theme"><?php esc_attr_e( 'Not right now', 'dokan-lite' ); ?></a>
        </p>
        <?php
        do_action( 'dokan_seller_wizard_introduction', $this );
    }
	
	 /**
     * Store step.
     */
    public function dokan_setup_store() {
        $store_info = $this->store_info;

        $store_ppp       = isset( $store_info['store_ppp'] ) ? absint( $store_info['store_ppp'] ) : (int) dokan_get_option( 'store_products_per_page', 'dokan_general', 12 );
        $show_email      = isset( $store_info['show_email'] ) ? esc_attr( $store_info['show_email'] ) : 'no';
        $address_street1 = isset( $store_info['address']['street_1'] ) ? $store_info['address']['street_1'] : '';
        $address_street2 = isset( $store_info['address']['street_2'] ) ? $store_info['address']['street_2'] : '';
        $address_city    = isset( $store_info['address']['city'] ) ? $store_info['address']['city'] : '';
        $address_zip     = isset( $store_info['address']['zip'] ) ? $store_info['address']['zip'] : '';
        $address_country = isset( $store_info['address']['country'] ) ? $store_info['address']['country'] : '';
        $address_state   = isset( $store_info['address']['state'] ) ? $store_info['address']['state'] : '';

        $country_obj = new WC_Countries();
        $countries   = $country_obj->countries;
        $states      = $country_obj->states;
        ?>
        <h1><?php esc_attr_e( 'Store Setup', 'dokan-lite' ); ?></h1>
        <form method="post" class="dokan-seller-setup-form">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="store_ppp"><?php esc_attr_e( 'Store Products Per Page', 'dokan-lite' ); ?></label></th>
                    <td>
                        <input type="text" id="store_ppp" name="store_ppp" value="<?php echo esc_attr( $store_ppp ); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="address[street_1]"><?php esc_html_e( 'Street', 'dokan-lite' ); ?></label></th>
                    <td>
                        <input type="text" id="address[street_1]" name="address[street_1]" value="<?php echo esc_attr( $address_street1 ); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="address[street_2]"><?php esc_html_e( 'Street 2', 'dokan-lite' ); ?></label></th>
                    <td>
                        <input type="text" id="address[street_2]" name="address[street_2]" value="<?php echo esc_attr( $address_street2 ); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="address[city]"><?php esc_html_e( 'City', 'dokan-lite' ); ?></label></th>
                    <td>
                        <input type="text" id="address[city]" name="address[city]" value="<?php echo esc_attr( $address_city ); ?>"/>
                    </td>
                </tr>
                <th scope="row"><label for="address[zip]"><?php esc_html_e( 'Post/Zip Code', 'dokan-lite' ); ?></label></th>
                <td>
                    <input type="text" id="address[zip]" name="address[zip]" value="<?php echo esc_attr( $address_zip ); ?>"/>
                </td>
                <tr>
                    <th scope="row"><label for="address[country]"><?php esc_html_e( 'Country', 'dokan-lite' ); ?></label></th>
                    <td>
                        <select name="address[country]" class="wc-enhanced-select country_to_state" id="address[country]" style="width: 100%;">
                            <?php dokan_country_dropdown( $countries, $address_country, false ); ?>
                        </select>
                    </td>
                </tr>
       <?php     /*      <tr>
                    <th scope="row"><label for="calc_shipping_state"><?php esc_html_e( 'State', 'dokan-lite' ); ?></label></th>
                    <td>
                        <input type="text" id="calc_shipping_state" name="address[state]" value="<?php echo esc_attr( $address_state ); ?>" / placeholder="<?php esc_attr_e( 'State Name', 'dokan-lite' ); ?>">
                    </td>
                </tr> */ ?>

                <?php do_action( 'dokan_seller_wizard_store_setup_after_address_field', $this ); ?>

                <tr>
                    <th scope="row"><label for="show_email"><?php esc_html_e( 'Email', 'dokan-lite' ); ?></label></th>
                    <td class="checkbox">
                        <input type="checkbox" name="show_email" id="show_email" class="switch-input" value="1" <?php echo ( $show_email === 'yes' ) ? 'checked="true"' : ''; ?>>
                        <label for="show_email">
                            <?php esc_html_e( 'Show email address in store', 'dokan-lite' ); ?>
                        </label>
                    </td>
                </tr>

                <?php do_action( 'dokan_seller_wizard_store_setup_field', $this ); ?>

            </table>
            <p class="wc-setup-actions step">
                <input type="submit" class="button-primary button button-large button-next store-step-continue dokan-btn-theme" value="<?php esc_attr_e( 'Continue', 'dokan-lite' ); ?>" name="save_step"/>
                <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next store-step-skip-btn dokan-btn-theme"><?php esc_html_e( 'Skip this step', 'dokan-lite' ); ?></a>
                <?php wp_nonce_field( 'dokan-seller-setup' ); ?>
            </p>
        </form>
        <script>
            (function ($) {
                var states = <?php echo wp_json_encode( $states ); ?>;

                $('body').on('change', 'select.country_to_state, input.country_to_state', function () {
                    // Grab wrapping element to target only stateboxes in same 'group'
                    var $wrapper = $(this).closest('form.dokan-seller-setup-form');

                    var country = $(this).val(),
                        $statebox = $wrapper.find('#calc_shipping_state'),
                        $parent = $statebox.closest('tr'),
                        input_name = $statebox.attr('name'),
                        input_id = $statebox.attr('id'),
                        value = $statebox.val(),
                        placeholder = $statebox.attr('placeholder') || $statebox.attr('data-placeholder') || '',
                        state_option_text = '<?php echo esc_attr__( 'Select an option&hellip;', 'dokan-lite' ); ?>';

                    if (states[country]) {
                        if ($.isEmptyObject(states[country])) {
                            $statebox.closest('tr').hide().find('.select2-container').remove();
                            $statebox.replaceWith('<input type="hidden" class="hidden" name="' + input_name + '" id="' + input_id + '" value="" placeholder="' + placeholder + '" />');

                            $(document.body).trigger('country_to_state_changed', [country, $wrapper]);

                        } else {

                            var options = '',
                                state = states[country];

                            for (var index in state) {
                                if (state.hasOwnProperty(index)) {
                                    options = options + '<option value="' + index + '">' + state[index] + '</option>';
                                }
                            }

                            $statebox.closest('tr').show();

                            if ($statebox.is('input')) {
                                // Change for select
                                $statebox.replaceWith('<select name="' + input_name + '" id="' + input_id + '" class="wc-enhanced-select state_select" data-placeholder="' + placeholder + '"></select>');
                                $statebox = $wrapper.find('#calc_shipping_state');
                            }

                            $statebox.html('<option value="">' + state_option_text + '</option>' + options);
                            $statebox.val(value).trigger('change');

                            $(document.body).trigger('country_to_state_changed', [country, $wrapper]);

                        }
                    } else {
                        if ($statebox.is('select')) {

                            $parent.show().find('.select2-container').remove();
                            $statebox.replaceWith('<input type="text" class="input-text" name="' + input_name + '" id="' + input_id + '" placeholder="' + placeholder + '" />');

                            $(document.body).trigger('country_to_state_changed', [country, $wrapper]);

                        } else if ($statebox.is('input[type="hidden"]')) {

                            $parent.show().find('.select2-container').remove();
                            $statebox.replaceWith('<input type="text" class="input-text" name="' + input_name + '" id="' + input_id + '" placeholder="' + placeholder + '" />');

                            $(document.body).trigger('country_to_state_changed', [country, $wrapper]);

                        }
                    }

                    $(document.body).trigger('country_to_state_changing', [country, $wrapper]);
                    $('.wc-enhanced-select').select2();
                });

                $(':input.country_to_state').trigger('change');

            })(jQuery);

        </script>
        <style>
            .select2-container--open .select2-dropdown {
                left: 20px;
            }
        </style
        <?php

        do_action( 'dokan_seller_wizard_after_store_setup_form', $this );
    }
}

new Dokan_Setup_Wizard_Override;


function restrict_medicine_same($passed_validation, $product_id, $quantity) {

	$err_product = array();
	
	$restrict_meta_code = get_field('common_codes',$product_id);
	if($restrict_meta_code){
	if($restrict_meta_code !== 'Null'){
	
	
	// Set $cat_check true if a cart item is in paint cat
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		
		//echo '<pre>'; print_r($cart_item); echo '</pre>';
		$restrict_meta_code_cart = get_field('common_codes',$cart_item['product_id']);
		if($restrict_meta_code_cart == $restrict_meta_code)  {			
				$err_product[] = get_the_title($cart_item['product_id']); 
		}
		
	}
	
		}
	}
	if(isset($err_product) && !empty($err_product) ) {
		 
		//echo '<pre>'; print_r($err_product); echo '</pre>'; die;
			
		wc_add_notice( " ".$err_product[0]." and ".get_the_title($product_id)." can't be bought together" , "error" );
			return $passed_validation = false;
			
		} else {
			
			return $passed_validation;
		}
	
	
}

add_filter( 'woocommerce_add_to_cart_validation', 'restrict_medicine_same' ,10 ,3 );


     

/**
 * Custom Add To Cart Messages
 * Add this to your theme functions.php file
 **/
add_filter( 'wc_add_to_cart_message', 'custom_add_to_cart_message' );
function custom_add_to_cart_message() {
    global $woocommerce;
    // Output success messages
	$msgString = '';
	if(isset($_POST) && !empty($_POST)){
		$product = wc_get_product( $_POST['add-to-cart'] );
		$vendor       = dokan_get_vendor_by_product( $product );
		$store_info   = $vendor->get_shop_info();
	/* 	echo '<pre>'; print_r($store_info); echo '</pre>';
		echo '<pre>'; print_r($vendor->get_shop_url()); echo '</pre>'; */
		
		$msgString = '<p class="success_message">Product successfully added to your cart.  For more shopping with same store <a class="same_shop" href="'.$vendor->get_shop_url().'">click here</a></p>';
	}
	
	
	//echo '<pre>'; print_r($_POST['product_id']); echo '</pre>';
    if (get_option('woocommerce_cart_redirect_after_add')=='yes') :
        $return_to  = get_permalink(woocommerce_get_page_id('shop'));
        $message    = sprintf('<a href="%s" class="button">%s</a> %s', $return_to, __('Continue Shopping &rarr;', 'woocommerce'), __('Product successfully added to your cart .', 'woocommerce') );
    else :
        $message    = sprintf('<a  href="%s" class="button success_button">%s</a> %s', get_permalink(woocommerce_get_page_id('cart')), __('View Cart &rarr;', 'woocommerce'), __(''.$msgString.'', 'woocommerce') );
    endif;
        return $message;
}
/* Custom Add To Cart Messages */



add_action( 'woocommerce_share', 'single_product_summary_action', 1 );
function single_product_summary_action() {
    // remove the single_excerpt
    remove_action('woocommerce_share', 'woocommerce_template_single_excerpt', 20 );
    // Add our custom function replacement
    add_action( 'woocommerce_share', 'single_excerpt_custom_replacement', 20 );
}

function single_excerpt_custom_replacement() {
	
	//echo get_the_id(); 
	$is_sameDay = get_field( "same_day_delivery", get_the_id() );
	$amount = get_field( "same_day_delivery_min_amount", get_the_id() );
	$miles = get_field( "same_day_delivery_miles_from_shop", get_the_id() );
	if($is_sameDay){ 
	
	echo '<div class="delivary_note">';
	echo '<h3>Delivery Note</h5>';
    echo '<Strong>same day delivery is available for '.$amount.' amount of '.$miles.' miles from customer’s address.</Strong>';
	echo '</div>';
	}
}
 

function deleteProduct(){
	
	global $wpdb;
	
	
	
	
	$args = [
    'status'    => 'publish',
    'orderby' => 'name',
    'order'   => 'ASC',
    'limit' => -1,
];
$all_products = wc_get_products($args);
$i=1;
foreach ($all_products as $key => $product) {

    if ($product->get_type() == "variable") {

		echo $i.' '.$product->get_id(); echo '<br/>';
			//	wp_delete_post($product->get_id(), true );
      /*   foreach ($product->get_variation_attributes() as $variations) {
            foreach ($variations as $variation) {
                echo $product->get_title() . " - " . $variation;
            } */
			$i++;
        }
    }

	

}

function updateTextnomyRep($slug,$term_id){
	global $wpdb;
	
	 $ch = curl_init();
	$curlConfig = array(
    CURLOPT_URL            => "https://www.britishchemist.co.uk/webservice/index.php",
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS     => array(
        'jsonRequest' => '{"methodName":"proCatQuestionsUsingSlug","slug":"'.$slug.'"}'
       
    )
);
curl_setopt_array($ch, $curlConfig);
$result = curl_exec($ch);
if(isset(json_decode($result)->proCatQuestionsUsingSlug->response->quesions)){
	
	$dataFor = json_decode($result)->proCatQuestionsUsingSlug->response->quesions;
	$i=0;
	$j=1;
	foreach($dataFor as $set){
		
		echo $set->question_no;
		echo '<br/>';
		echo $set->question;
		echo '<br/>';
		echo $set->question_type;
		echo '<br/>';
		echo $set->answer;
		echo '<br/>';echo '<br/>';echo '<br/>';echo '<br/>';
		echo update_term_meta($term_id,'questions_'.$i.'_question_no', $set->question_no);
		echo update_term_meta($term_id,'questions_'.$i.'_question', $set->question);
		echo update_term_meta($term_id,'questions_'.$i.'_question_type', $set->question_type);
		echo update_term_meta($term_id,'questions_'.$i.'_answer', $set->answer);
		echo update_term_meta($term_id,'questions', $j);
		
		
		
		
		
		$j++;
		$i++;
		
	}
	
	
	
	
}
curl_close($ch);
	
}


function updateproductbySlug($slug,$id){
	global $wpdb;
	
	 $ch = curl_init();
	$curlConfig = array(
    CURLOPT_URL            => "https://www.britishchemist.co.uk/webservice/index.php",
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS     => array(
        'jsonRequest' => '{"methodName":"proacfFieldsUsingSlug","slug":"'.$slug.'"}'
       
    )
);
curl_setopt_array($ch, $curlConfig);
$result = curl_exec($ch);
echo '<pre>'; print_r(json_decode($result)->proacfFieldsUsingSlug->response->fieldsData); echo '</pre>';


if(json_decode($result)->proacfFieldsUsingSlug->response->fieldsData->uses__instructions){
		update_field('uses__instructions', json_decode($result)->proacfFieldsUsingSlug->response->fieldsData->uses__instructions, $id);
}
if(json_decode($result)->proacfFieldsUsingSlug->response->fieldsData->warnings){
		update_field('warnings', json_decode($result)->proacfFieldsUsingSlug->response->fieldsData->warnings, $id);
}
if(json_decode($result)->proacfFieldsUsingSlug->response->fieldsData->side_effects){
		update_field('side_effects', json_decode($result)->proacfFieldsUsingSlug->response->fieldsData->side_effects, $id);
}
if(json_decode($result)->proacfFieldsUsingSlug->response->fieldsData->ingredients){
		update_field('ingredients', json_decode($result)->proacfFieldsUsingSlug->response->fieldsData->ingredients, $id);
}

curl_close($ch);
	
}


add_action( 'woocommerce_before_single_product', 'show_variation_sku_underneath_product_title' );
function show_variation_sku_underneath_product_title() {

    global $product;

    if ( $product->is_type('variable') ) {
        ?>
        <script>
        jQuery(document).ready(function($) {     
            $('input.variation_id').change( function(){
                if( '' != $('input.variation_id').val() ) {

                    jQuery.ajax( {

                        url: '<?php echo admin_url( 'admin-ajax.php'); ?>',
                        type: 'post',
                        data: { 
                            action: 'get_variation_sku', 
                            variation_id: $('input.variation_id').val()
                        },
                        success: function(data) {
                       
                            if(data.length > 0) {
                                $('.product_after_title .sku_wrapper .sku').html( data);
                            }
                        }
                    });

                }
            });
        });
        </script>
    <?php
    }
}
    
add_action('wp_ajax_get_variation_sku' , 'get_variation_sku');
add_action('wp_ajax_nopriv_get_variation_sku','get_variation_sku');
function get_variation_sku() {

    $variation_id = intval( $_POST['variation_id'] );
    $sku = '';

    if ( $product = wc_get_product( $variation_id ) ) $sku = $product->get_sku();
    echo $sku;

    wp_die(); // this is required to terminate immediately and return a proper response
}

/**
 * Disaplay prouct attributes under the product title on product single page.
 */
function woo_add_attributes_after_single_product_title()
{

	global $product;

	$product_attributes = $product->get_attributes(); // Get the product attributes
	//echo '<pre>'; print_r($product_attributes); echo '</pre>';

	if (!empty($product_attributes)) {

		echo '<div class="list_attributes">';

		$separator = '';
		foreach ($product_attributes as $attribute) {

			$name = $attribute->get_name();
			$options = $attribute->get_options();
			$comma = '';
			echo $separator;

			foreach ($options as $label) {

				echo  '<span>'.$name. ' : <strong>' . $comma . $label . '</strong></span>';
				$comma = ', ';
			}
			$separator = ' | ';
		}
		echo '</div>';
	}
}
add_action('woocommerce_single_product_summary', 'woo_add_attributes_after_single_product_title', 6);



add_action( 'woocommerce_before_cart', 'wc_add_notice_free_shipping' );

 function wc_add_notice_free_shipping() {
	 $free_shipping_settings = get_option('woocommerce_free_shipping_13_settings');
	 $amount_for_free_shipping = $free_shipping_settings['min_amount']; 
	 $cart = WC()->cart->subtotal;
	 $remaining = $amount_for_free_shipping - $cart; 	
	 if( $amount_for_free_shipping > $cart ){		
	 $notice = sprintf( "Get free delivery when you spend %s . Spend an additional %s to get free delivery", wc_price($amount_for_free_shipping) ,  wc_price($remaining)); wc_print_notice( $notice , 'notice' ); 	
	 } 	
	 
	 }
	 
	 
	 
	 function maleriacalculater($params = array()) {

	
	extract(shortcode_atts(array(
		'slug' => '',		
	), $params));

	
	$otput =  '<div class="travlingform"><legend>Calculation of malaria tablets  </legend><form action="" method="POST" id="calculateForm"><div class="form-group">
<label class="dayweektext">NUMBER OF DAYS YOU ARE STAYING IN YOUR COUNTRY OF DESTINATION<span>*</span></label>
<input type="hidden" name="medicine" id="medicine" value="'.$params['slug'].'">
<input class="" type="text" name="daysout" id="daysout" value="">
<div class="showerrordays" style="display:none; color:red;">This is required field</div>
</div>
<div class="submitbuttons">	
<a class="submitbutton button" href="javascript:void(0)" onClick="calcualtedays_ind()">Calculate</a>
<a class="submitbutton_reset button" href="javascript:void(0)" onClick="resetform_ind()">Reset</a>
</div>
<div class="resultdiv">
</div>
</form></div>';

	
	

	return $otput;
}
add_shortcode('calculator', 'maleriacalculater');

	register_sidebar( array(
		'name'          => esc_html__( 'BMI calculator Sidebar', 'physio-qt' ),
		'description'   => esc_html__( 'Widgets for the BMI calculator Sidebar', 'physio-qt' ),
		'id'            => 'bmi-calculator',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Video Popup', '' ),
		'description'   => esc_html__( 'Widgets for video popup', '' ),
		'id'            => 'video-popup',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	
	
	add_action("woocommerce_save_account_details", "data_saved"); 
	function data_saved($user_ID ){
		global $wpdb; 
		
		
		//echo '<pre>'; print_r($_REQUEST); echo '</pre>'; die;
		
		if($_REQUEST['action'] == 'dokan_save_account_details'){
			
			 update_user_meta( $user_ID, 'dokan_custom_superintendent_name', $_REQUEST['superintendent_name'] );
			
		}
		
		
	}
	
	   //save the field value

add_action( 'dokan_store_profile_saved', 'save_extra_fields', 15 );
function save_extra_fields( $store_id ) { 
    $dokan_settings = dokan_get_store_info($store_id);
    if ( isset( $_POST['dokan_gphclogo'] ) ) {
        $dokan_settings['dokan_gphclogo'] = $_POST['dokan_gphclogo'];
    }
	
	 if ( isset( $_POST['setting_show_public'] ) ) {
        $dokan_settings['setting_show_public'] = $_POST['setting_show_public'];
    }
 update_user_meta( $store_id, 'dokan_profile_settings', $dokan_settings );
}


add_filter( 'dokan_query_var_filter', 'dokan_load_document_menu' );
function dokan_load_document_menu( $query_vars ) {

    $query_vars['services'] = 'services';
    $query_vars['job'] = 'job';
    $query_vars['chat'] = 'chat';
		
    return $query_vars;
}

add_filter( 'dokan_get_dashboard_nav', 'dokan_add_services_menu' );
function dokan_add_services_menu( $urls ) {
	//echo '<pre>'; print_r($urls); echo '</pre>'; die;
    $urls['services'] = array(
        'title' => __( 'Services', 'dokan'),
        'icon'  => '<i class="fa fa-user"></i>',
        'url'   => dokan_get_navigation_url( 'services' ),
        'pos'   => 51
    );

     $urls['job'] = array(
        'title' => __( 'Job', 'dokan'),
        'icon'  => '<i class="fa fa-user"></i>',
        'url'   => dokan_get_navigation_url( 'job' ),
        'pos'   => 52
    );

     $urls['chat'] = array(
        'title' => __( 'Chat', 'dokan'),
        'icon'  => '<i class="fa fa-user"></i>',
        'url'   => dokan_get_navigation_url( 'chat' ),
        'pos'   => 52
    );
    return $urls;
}

add_action( 'dokan_load_custom_template', 'dokan_load_template_services' );
function dokan_load_template_services( $query_vars ) {

    if ( isset( $query_vars['services'] ) ) {
         require_once dirname( __FILE__ ). '/template-services.php';
    
    }

     if ( isset( $query_vars['job'] ) ) {
         require_once dirname( __FILE__ ). '/template-job.php';
    
    }

    if ( isset( $query_vars['chat'] ) ) {
         require_once dirname( __FILE__ ). '/template-chat.php';
    
    }
}



/*Custom Post type start*/

function cw_post_type_service() {

	$supports = array(
		'title', // post title
		'editor', // post content
		'author', // post author
		'thumbnail', // featured images
		'excerpt', // post excerpt
		'custom-fields', // custom fields
		'comments', // post comments
		'revisions', // post revisions
		'post-formats', // post formats
	);

	$labels = array(
		'name' => _x('Services', 'plural'),
		'singular_name' => _x('Services', 'singular'),
		'menu_name' => _x('Service', 'admin menu'),
		'name_admin_bar' => _x('Service', 'admin bar'),
		'add_new' => _x('Add New', 'add new'),
		'add_new_item' => __('Add New Service'),
		'new_item' => __('New Service'),
		'edit_item' => __('Edit Service'),
		'view_item' => __('View Service'),
		'all_items' => __('All Services'),
		'search_items' => __('Search Service'),
		'not_found' => __('No Service found.'),
	);

	$args = array(
		'supports' => $supports,
		'labels' => $labels,
		'public' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'service'),
		'has_archive' => true,
		'hierarchical' => false,
	);
	register_post_type('service', $args);
}
add_action('init', 'cw_post_type_service');

/*Custom Post type end*/


/**
 * Adds a rewrite rule for our Example store page.
 *
 * @param array $rewrite_rules
 *
 * @return array
 */
function add_services_tab_rewrite_rules( $store_url ) {	
   
	  add_rewrite_rule( $store_url . '/([^/]+)/services?$', 'index.php?' . $store_url . '=$matches[1]&services=true', 'top' );
	  add_rewrite_rule( $store_url . '/([^/]+)/job?$', 'index.php?' . $store_url . '=$matches[1]&job=true', 'top' );
	
}

add_action( 'dokan_rewrite_rules_loaded', 'add_services_tab_rewrite_rules' );


/**
 * Adds an Example tab to the Dokan store page.
 *
 * @param array $tabs
 * @param integer $store_id
 *
 * @return array
 */
function add_services_store_tab( $tabs, $store_id ) {
    $tabs['services'] = array(
        'title' => __( 'Services', 'dokan' ),
        'url'   => dokan_get_store_url( $store_id ) . 'services',
    );


    $tabs['job'] = array(
        'title' => __( 'Job', 'dokan' ),
        'url'   => dokan_get_store_url( $store_id ) . 'job',
    );
    return $tabs;
}

add_filter( 'dokan_store_tabs', 'add_services_store_tab', 20, 2 );


/**
 * Registers the query variable for our service page.
 *
 * @param array $query_vars
 *
 * @return array
 */
function register_services_store_tab_query_var( $query_vars ) {
    $query_vars[] = 'services';
    $query_vars[] = 'job';
    return $query_vars;    
}

add_filter( 'dokan_query_var_filter', 'register_services_store_tab_query_var' );

/**
 * Loads the template for the service tab.
 *
 * @param string $template
 *
 * @return string
 */
function load_services_tab_template( $template ) {
	   if ( ! function_exists( 'WC' ) ) {
            return $template;
        }
		
		   if ( get_query_var( 'store_review' ) ) {
            return dokan_locate_template( 'store-reviews.php', '', DOKAN_PRO_DIR . '/templates/', true );
        }
	
    if ( get_query_var( 'services' ) ) {
		 // $root_path = get_home_path();
		 $rrot_path = ABSPATH;
		//echo $root_path. 'wp-content/themes/matico-child/dokan/'; die;
		
		
       return dokan_locate_template( 'store-service.php', '', $root_path.'wp-content/themes/matico-child/dokan/', true );
    }

    if ( get_query_var( 'job' ) ) {
		 // $root_path = get_home_path();
    	$root_path = ABSPATH;
		//echo $root_path. 'wp-content/themes/matico-child/dokan/'; die;
		
		
       return dokan_locate_template( 'store-service.php', '', $root_path.'wp-content/themes/matico-child/dokan/', true );
    }
	
	
     

    return $template;  
}

add_filter( 'template_include', 'load_services_tab_template', 999 );


function showProductOnlyForTradeuser($productID,$loggedInUser){
	global $wpdb;	
	
	$currentuserData = get_userdata($loggedInUser);
	
	$productData = wc_get_product( $productID );
	$postdata = get_postdata($productID);
	
	$userRole = implode(', ', $currentuserData->roles);
	
	$mo_capabilities = get_user_meta( $loggedInUser, 'mo_capabilities', true);
	
	if($userRole == 'administrator' || $userRole == 'seller'){		
		$flag = false;		
	} elseif( ($userRole == 'customer' )&& ($mo_capabilities['customer'] == 1) && ($mo_capabilities['dokan_wholesale_customer'] == 1)){
		
		$flag = false;	
		
	} else {
		
		$flag = true;
	}
	return $flag;

}

function productIsShow($product){
	global $wpdb;	
	$flag = false;
	$productID =  $product->get_id();	
	$loggedInUser = get_current_user_id(); 	
	//$productData = wc_get_product( $productID );
	$postdata = get_postdata($productID);	
	$productOwner = $postdata['Author_ID'];
	$wholesale_meta = get_post_meta( $productID, '_dokan_wholesale_meta', true);
	$wholesellerAuthor = get_user_meta( $productOwner, 'dokan_wholeseller_exists', true); 
	//print_r($wholesellerAuthor);
	
	$profile_settings = get_user_meta( $productOwner, 'dokan_profile_settings', true); 
	//echo '<pre>'; print_r($profile_settings); echo '</pre>';
	if(isset($profile_settings['setting_show_public']) && !empty($profile_settings['setting_show_public']) && $profile_settings['setting_show_public'] != 'yes'){  
		
		if((isset($wholesale_meta) && !empty($wholesale_meta)) &&  $wholesellerAuthor != '') {		 
			if(($wholesale_meta['enable_wholesale'] == 'yes') && ($wholesellerAuthor == 'on')){
				if(!empty($loggedInUser)){	 	
					$flag = showProductOnlyForTradeuser($productID ,$loggedInUser);			
				} else {		
					$flag = true; 
				} 
			} 		 
		}	
		
	}	
	return $flag;
	
	
}

 add_filter( 'dokan_vendor_shop_data', 'update_vendor_default_store_data' , 10, 2 );
 
 function update_vendor_default_store_data($shop_data, $vendor){
	 
	 
	if(!isset($shop_data['setting_show_public']) && empty($shop_data['setting_show_public'])){ 
	$shop_data['setting_show_public'] = 'no';
	}

	return $shop_data;
	 
 }
 
 
 

add_action( 'woocommerce_after_checkout_validation', 'prohibited_abuseable_prooduct_checkout', 10, 2 );
 
function prohibited_abuseable_prooduct_checkout( $fields, $errors ){
	if (!WC()->cart->is_empty() && isset($fields['billing_email']) && !empty($fields['billing_email'])) {

	// Loop over $cart items
	 foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$product_id = $cart_item['product_id'];
		$product = wc_get_product($product_id);
		 $sku = $product->get_sku();
		$days = woo_product_repurchase_days($product_id); 	
		//print_r($days); die;		
			if ($days >= 1) {		
				
				// Check user bought product in last number of days
				$errorcount_same = woo_user_with_same_address_has_bought_product_in_days($fields,  $product_id, $days);  
				 if ($errorcount_same) { 
					$errors->add( 'validation', woo_repurchase_error_msg($days) );
					return $errors;
				 } else {  
					
				$errorcount = isProductAlreadyPurchasedInOtherPharmacy($fields['billing_email'],$days,$sku);			 
 				 if ($errorcount) { 
					$errors->add( 'validation', 'Sorry but our records show this product has been purchased online within the last '.$days.' days from another online pharmacy, you have have to wait another '.$days.' days to be able to purchase from us' );
					return $errors;
				 }  
				 
				 }
			}
	} 
	
	}
	
	//echo '';
	return $errors;
}

function isProductAlreadyPurchasedInOtherPharmacy($email,$days,$sku){

	global $wpdb;		
	 $email;
	 $days; 
	 $sku;	
	$ch = curl_init();
	$curlConfig = array(
	CURLOPT_URL            => "https://www.britishchemist.co.uk/webservice/index.php",
	CURLOPT_POST           => true,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POSTFIELDS     => array(
	'jsonRequest' => '{"methodName":"abuseProduct","email_address":"'.$email.'","sku_code":"'.$sku.'","days":"'.$days.'"}'
	)
	);
	curl_setopt_array($ch, $curlConfig);
	$result = curl_exec($ch);
	
	
	
	if(isset(json_decode($result)->abuseProduct->response)){

		$dataFor = json_decode($result)->abuseProduct->response->result;	
		
		
	}
	curl_close($ch);
	
	if($dataFor){
		
		return true;
	} else {
		
		return false;
	}
	
	
	
	
}

add_action( 'woocommerce_before_resend_order_emails', 'send_custom_email_to_customer', 10, 2 );
function send_custom_email_to_customer( $order, $email_type ) {

    // Only send the email if it's a resend of the "customer_processing_order" email
    if ( $email_type == 'cancelled_order' ) {
        $cancel_reason = get_post_meta($order->get_id(), 'cancel_reason', true);
        // Get the customer's email address
		
		
        $customer_email = $order->get_billing_email();
        //$customer_email = 'tkumawat39@gmail.com';
		$headers = array('Content-Type: text/html; charset=UTF-8');
        // Set up the email subject and message
        $subject = 'Your order has been cancelled';
		
		if($cancel_reason){
			
			$message = '<strong>Dear Customer</strong><p>Your order has been cancelled and refunded for below reasons:</p>
			<p>'.$cancel_reason.'</p>			
			<p>Kind regards</p>
			<strong>Allchemist Team</strong>';

		} else{
			
			$message = '<strong>Dear Customer</strong><p>Your order has been cancelled and refunded for one of the following reasons:</p>				
			<p>1. Item is out of stock</p>
			<p>2. You have requested the cancellation</p>
			<p>3. We have emailed you for further questioning about an item you have ordered but we have not heard back from you.
			<p>4. You have not used your full name for orders</p>
			<p>5. Our regulations team are not happy with the answers to the questionnaire and have decided the item ordered is not safe to use. Please do not hesitate to contact us if you have any questions.</p>
			<p>We apologise for any inconvenience caused.</p>
			<p>Kind regards</p>
			<strong>Allchemist Team</strong>';
			
			
		}
		
        
							
        // Send the email to the customer
        wp_mail( $customer_email, $subject, $message,$headers );
    }
}


add_action('woocommerce_order_status_changed', 'send_custom_email_notifications', 10, 4 );
function send_custom_email_notifications( $order_id, $old_status, $new_status, $order ){
    if ( $new_status == 'cancelled' || $new_status == 'failed' ){
        $wc_emails = WC()->mailer()->get_emails(); // Get all WC_emails objects instances
        $customer_email = $order->get_billing_email(); // The customer email
    }

    if ( $new_status == 'cancelled' ) {
        // change the recipient of this instance
        $wc_emails['WC_Email_Cancelled_Order']->recipient = $customer_email;
        // Sending the email from this instance
        $wc_emails['WC_Email_Cancelled_Order']->trigger( $order_id );
    } 
    elseif ( $new_status == 'failed' ) {
        // change the recipient of this instance
        $wc_emails['WC_Email_Failed_Order']->recipient = $customer_email;
        // Sending the email from this instance
        $wc_emails['WC_Email_Failed_Order']->trigger( $order_id );
    } 
}


// Add meta box to order edit screen for admin order notes
function add_cancel_reason_meta_box() {
    add_meta_box(
        'order_cancel_reason',
        'Order Cancel Reason',
        'render_cancel_reason_meta_box',
        'shop_order',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_cancel_reason_meta_box');

// Render the cancel reason meta box
function render_cancel_reason_meta_box($post) {
    wp_nonce_field('cancel_reason_nonce', 'cancel_reason_nonce');

    $cancel_reason = get_post_meta($post->ID, 'cancel_reason', true);
    ?>
    <p>
        <label for="cancel_reason">Cancel Reason:</label>
        <textarea id="cancel_reason" name="cancel_reason" rows="4" style="width: 100%;"><?php echo esc_textarea($cancel_reason); ?></textarea>
		<span class="cancel_reason_success" style="display:none; color:green; ">Reason has been update</span>
    </p>
    <?php
}

// Save the cancel reason
function save_cancel_reason_meta_box($post_id) {
    if (!isset($_POST['cancel_reason_nonce']) || !wp_verify_nonce($_POST['cancel_reason_nonce'], 'cancel_reason_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['cancel_reason'])) {
        update_post_meta($post_id, 'cancel_reason', sanitize_textarea_field($_POST['cancel_reason']));
    }
}
add_action('save_post', 'save_cancel_reason_meta_box');


// Enqueue script for the meta box
function enqueue_cancel_reason_script() {
    wp_enqueue_script('cancel-reason-script', get_stylesheet_directory_uri() . '/assets/js/cancel-reason-script.js', array('jquery'), '1.0', true);
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/hk_custom.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'enqueue_cancel_reason_script');

// AJAX callback to save the cancel reason
function save_cancel_reason_ajax_callback() {
    if (!check_ajax_referer('cancel_reason_nonce', 'security', false)) {
        wp_send_json_error('Invalid nonce');
    }

    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $cancel_reason = isset($_POST['cancel_reason']) ? sanitize_textarea_field($_POST['cancel_reason']) : '';

    if (empty($order_id) || empty($cancel_reason)) {
        wp_send_json_error('Invalid data');
    }

    update_post_meta($order_id, 'cancel_reason', $cancel_reason);

    wp_send_json_success('Reason saved successfully');
}
add_action('wp_ajax_save_cancel_reason', 'save_cancel_reason_ajax_callback');


// Hook into the restrict_manage_posts action
add_action('restrict_manage_posts', 'custom_order_filter');

// Custom function to display the filter dropdown
function custom_order_filter() {
    global $typenow;

    // Check if we are on the admin orders page
    if ($typenow === 'shop_order') {
        // Add your custom meta key and value to filter the orders
        $meta_key = 'your_custom_meta_key';
        $meta_value = 'cancel_reason';

        echo '<select name="meta_key_exists">';
        echo '<option value="">' . __('All Orders', 'textdomain') . '</option>';
        echo '<option value="' . $meta_value . '">' . __('Cancellation Reason', 'textdomain') . '</option>';
        echo '</select>';
    }
}


// Hook into the request action to filter orders based on the meta key existence
add_filter('request', 'filter_orders_by_meta_key_existence');

// Custom function to modify the query to filter orders based on meta key existence
function filter_orders_by_meta_key_existence($vars) {
    global $typenow, $wpdb;

    // Check if we are on the admin orders page and a specific meta key is provided
    if ($typenow === 'shop_order' && isset($_GET['meta_key_exists']) && $_GET['meta_key_exists'] !== '') {
        $meta_key = sanitize_key($_GET['meta_key_exists']);

        // Use the WPDB class to construct a custom SQL query
        $sql = "SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value != ''";

        $post_ids = $wpdb->get_col($wpdb->prepare($sql, $meta_key));

        // If no matching orders found, set a dummy post ID to ensure an empty result
        if (empty($post_ids)) {
            $post_ids[] = 0;
        }

        // Modify the query to include the post IDs of orders with the given meta key
        $vars['post__in'] = $post_ids;
    }

    return $vars;
}



// Define a function to calculate shipping cost based on order amount
/* function custom_shipping_cost($cost, $package) {
	
	
	  if ( isset( $rates['free_shipping:7'] ) ) {
		  
			$order_subtotal = WC()->cart->get_subtotal();
			
			   if ($order_subtotal > 40) {
					// If order subtotal is greater than $40, set the shipping cost to $2.99
					$cost = 2.99;
				} else {
					// Otherwise, set the shipping cost to $5.89
					$cost = 5.89;
				}

				return $cost;
		  
		  
	  }
  
} */

// Hook into WooCommerce to apply the custom shipping cost
//add_filter('woocommerce_package_rates', 'custom_shipping_cost', 10, 2);

// Hook into the user registration process
function save_user_meta_on_registration($user_id) {
	
    // Get the custom meta value from the registration form
    $custom_value = isset($_POST['reg_post_code']) ? sanitize_text_field($_POST['reg_post_code']) : '';

    $reg_post_code_latitude = isset($_POST['reg_post_code_latitude']) ? sanitize_text_field($_POST['reg_post_code_latitude']) : '';
    $reg_post_code_longitude = isset($_POST['reg_post_code_longitude']) ? sanitize_text_field($_POST['reg_post_code_longitude']) : '';
 	$select_position = isset($_POST['select_position']) ? sanitize_text_field($_POST['select_position']) : '';
 	$professional_pegistration_number = isset($_POST['professional_pegistration_number']) ? sanitize_text_field($_POST['professional_pegistration_number']) : '';
 	// 27-02-2024
 	$jober_qualification = isset($_POST['qualification']) ? sanitize_text_field($_POST['qualification']) : '';
 	$jober_city = isset($_POST['jober_city']) ? sanitize_text_field($_POST['jober_city']) : '';
 	update_user_meta($user_id, 'jober_city', $jober_city);
 	
    // Save the custom meta value
    update_user_meta($user_id, 'reg_post_code', $custom_value);
    update_user_meta($user_id, 'select_position', $select_position);
    update_user_meta($user_id, 'reg_post_code_latitude', $reg_post_code_latitude);
    update_user_meta($user_id, 'reg_post_code_longitude', $reg_post_code_longitude);
    update_user_meta($user_id, 'professional_pegistration_number', $professional_pegistration_number);
    // 27-02-2024
    update_user_meta($user_id, 'jober_qualification', $jober_qualification);

}

add_action('user_register', 'save_user_meta_on_registration');


// Register Custom Post Type
add_action('init', 'custom_post_type_job', 0);

function custom_post_type_job() {
    $labels = array(
        'name'                  => _x('Jobs', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('Job', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Jobs', 'text_domain'),
        'all_items'             => __('All Jobs', 'text_domain'),
        'add_new_item'          => __('Add New Job', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'new_item'              => __('New Job', 'text_domain'),
        'edit_item'             => __('Edit Job', 'text_domain'),
        'update_item'           => __('Update Job', 'text_domain'),
        'view_item'             => __('View Job', 'text_domain'),
        'search_items'          => __('Search Jobs', 'text_domain'),
    );
    $args = array(
        'label'                 => __('Job', 'text_domain'),
        'description'           => __('Post Type Description', 'text_domain'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'public'                => true,
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 5,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite' => array('slug' => 'job'),
    );
    register_post_type('job', $args);
}


// Register Custom Taxonomy
function custom_taxonomy_job_category() {
    $labels = array(
        'name'                       => _x( 'Job Categories', 'Taxonomy General Name', 'text_domain' ),
        'singular_name'              => _x( 'Job Category', 'Taxonomy Singular Name', 'text_domain' ),
        'menu_name'                  => __( 'Job Categories', 'text_domain' ),
        'all_items'                  => __( 'All Categories', 'text_domain' ),
        'parent_item'                => __( 'Parent Category', 'text_domain' ),
        'parent_item_colon'          => __( 'Parent Category:', 'text_domain' ),
        'new_item_name'              => __( 'New Category Name', 'text_domain' ),
        'add_new_item'               => __( 'Add New Category', 'text_domain' ),
        'edit_item'                  => __( 'Edit Category', 'text_domain' ),
        'update_item'                => __( 'Update Category', 'text_domain' ),
        'view_item'                  => __( 'View Category', 'text_domain' ),
        'separate_items_with_commas' => __( 'Separate categories with commas', 'text_domain' ),
        'add_or_remove_items'        => __( 'Add or remove categories', 'text_domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
        'popular_items'              => __( 'Popular Categories', 'text_domain' ),
        'search_items'               => __( 'Search Categories', 'text_domain' ),
        'not_found'                  => __( 'Not Found', 'text_domain' ),
        'no_terms'                   => __( 'No categories', 'text_domain' ),
        'items_list'                 => __( 'Categories list', 'text_domain' ),
        'items_list_navigation'      => __( 'Categories list navigation', 'text_domain' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'job_category', array( 'job' ), $args );
}
add_action( 'init', 'custom_taxonomy_job_category', 0 );




// Function to add a new user role in WooCommerce
function add_custom_role() {
   	// Add a custom user role called 'custom_role'
	if (!get_role('jobers')) {
	    add_role( 'jobers', __('Jobers', 'text_domain'),
	        array(
	            'read'         => true,
	            'edit_posts'   => false,
	            'delete_posts' => false,
	            // Add more capabilities as needed
	        )
	    );
	}
}

// Hook the function to the 'init' action
add_action('init', 'add_custom_role');

// Add a custom user role during WooCommerce user registration
function add_custom_role_on_registration($data) {	
    $data[] = 'jobers';
    return $data;
}

// Hook the function to the 'woocommerce_new_customer_data' filter
add_filter('dokan_register_user_role', 'add_custom_role_on_registration', 10);



function custom_post_submit_action($post_id, $post) {

    if ($post->post_type == 'job') {
        $args1 = array(
		 'role' => 'jobers',
		 'orderby' => 'user_nicename',
		 'order' => 'ASC'
		);
		 $subscribers = get_users($args1);
		 foreach ($subscribers as $user) {

		 	$user_id = $user->ID;
		 	$user_email = $user->user_email;
		 	$targetLatitude = get_user_meta($user_id, 'reg_post_code_latitude', true);
		    $targetLongitude = get_user_meta($user_id, 'reg_post_code_longitude', true);

			if ( ! empty( $targetLatitude ) && !empty( $targetLongitude ) ) {

					global $wpdb;
					$table_name = $wpdb->prefix . 'near_job';

					$sql = "SELECT ID, lat, lon, post_id, (6371 * acos(cos(radians($targetLatitude)) * cos(radians(lat)) * cos(radians(lon) - radians($targetLongitude)) + sin(radians($targetLatitude)) * sin(radians(lat)))) AS distance FROM $table_name WHERE post_id = $post_id ORDER BY distance";
					print_r($sql);
					$query = $wpdb->prepare($sql);

					
					// Execute the query
					$results = $wpdb->get_results($query);	

					print_r($results);
					echo "<br>";
					if ($results) {
					    foreach ($results as $result) {
					        echo "CZCZXczxcxzczx";
					        if ($result->distance < 17) {

					        	$to = $user_email;
								$subject = 'Hello, this is the subject Job 2000000000000033333333333333330';
								$message = 'This is the body of the email message.';
								$headers = array('Content-Type: text/html; charset=UTF-8');

								// Send the email
								$result = wp_mail($to, $subject, $message, $headers);

								if ($result) {
								   // echo 'Email sent successfully!';
								} else {
								   // echo 'Error sending email.';
								}
					        }
					    }
					}
			}
			
		 }
		
    }
}

// Add your custom function to the save_post action hook
// add_action('save_post', 'custom_post_submit_action', 10, 2);


function bbloomer_add_premium_support_endpoint() {
    add_rewrite_endpoint( 'chat', EP_ROOT | EP_PAGES );
}
  
add_action( 'init', 'bbloomer_add_premium_support_endpoint' );
  
// ------------------
// 2. Add new query var
  
function bbloomer_premium_support_query_vars( $vars ) {
    $vars[] = 'chat';
    return $vars;
}
  
add_filter( 'query_vars', 'bbloomer_premium_support_query_vars', 0 );
  
// ------------------
// 3. Insert the new endpoint into the My Account menu
  
function bbloomer_add_premium_support_link_my_account( $items ) {
    $items['chat'] = 'Chat User';
    return $items;
}
  
add_filter( 'woocommerce_account_menu_items', 'bbloomer_add_premium_support_link_my_account' );
  
// ------------------
// 4. Add content to the new tab
  
function bbloomer_premium_support_content() {
   
   echo do_shortcode('[get_chat_list]');
}
  
add_action( 'woocommerce_account_chat_endpoint', 'bbloomer_premium_support_content' );
// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format



// 27-02-2024
// 24-06-2024
function dokan_custom_seller_registration_required_fields( $required_fields ) {
	if ( isset( $_POST['role'] ) && $_POST['role'] == 'jobers' ) {
    	$required_fields['qualification'] = __( 'Please enter your Qualification number', 'dokan-custom' );
	}
    return $required_fields;
};

add_filter( 'dokan_seller_registration_required_fields', 'dokan_custom_seller_registration_required_fields' );


function dokan_custom_new_jober_created( $vendor_id, $dokan_settings ) {
    $post_data = wp_unslash( $_POST );

    if ( isset( $post_data['role'] ) && $post_data['role'] == 'jobers' ) {
        $qualification = $post_data['qualification'];
        update_user_meta( $vendor_id, 'jober_qualification', $qualification );
    }
}
add_action( 'dokan_new_seller_created', 'dokan_custom_new_jober_created', 10, 2 );

add_action( 'personal_options_update', 'my_save_qualification_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_qualification_profile_fields' );

function my_save_qualification_profile_fields( $user_id ) {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        return;
    }

    $user = get_userdata( $user_id );
    if ( in_array( 'jobers', (array) $user->roles ) ) {
        if ( isset( $_POST['qualification'] ) ) {
            update_user_meta( $user_id, 'jober_qualification', $_POST['qualification'] );
        }
    }
}



// save rating in database
add_action('wp_ajax_save_user_rating', 'save_user_rating_callback');
add_action('wp_ajax_nopriv_save_user_rating', 'save_user_rating_callback');
function save_user_rating_callback() {

    // Get user ID and rating from the AJAX request
    $user_id = $_POST['user_id'];
    $rating = $_POST['rating'];
    var_dump($rating);

    // Here, you can implement your logic to save the rating to the user

    // For example, you can use update_user_meta to store the rating as user meta
    update_user_meta($user_id, 'user_rating', $rating);

    // Return a response
    echo 'Rating saved successfully!';

    // Always exit to avoid further execution
    wp_die();
}




// 27-02-2024 add
// Add custom REST API endpoint to retrieve user data by role
add_action('rest_api_init', 'hk_get_jober_user_data_rest_api');

function hk_get_jober_user_data_rest_api() {
    register_rest_route('custom/v1', '/users/(?P<role>\w+)', array(
        'methods' => 'GET',
        'callback' => 'kh_get_users_by_role',
    ));
}

// Callback function to retrieve user data by role
function kh_get_users_by_role($data) {
    $role = $data['role'];
    $users = get_users(array(
        'role' => $role,
    ));

    $response = array();

    foreach ($users as $user) {
    	$user_meta = get_user_meta($user->ID);

        $user_data = array(
            'user_id' => $user->ID,
            'username' => $user->user_login,
            'email' => $user->user_email,
            'display_name' => $user->display_name,
            'user_nicename' => $user->user_nicename,
            'role' => $role,
            'jober_qualification' => isset($user_meta['jober_qualification'][0]) ? $user_meta['jober_qualification'][0] : '',
            'select_position' => isset($user_meta['select_position'][0]) ? $user_meta['select_position'][0] : '',
            'reg_post_code' => isset($user_meta['reg_post_code'][0]) ? $user_meta['reg_post_code'][0] : '',
            'reg_post_code_latitude' => isset($user_meta['reg_post_code_latitude'][0]) ? $user_meta['reg_post_code_latitude'][0] : '',
            'reg_post_code_longitude' => isset($user_meta['reg_post_code_longitude'][0]) ? $user_meta['reg_post_code_longitude'][0] : ''
            // Add more user data fields as needed
        );

        $response[] = $user_data;
    }

    return rest_ensure_response($response);
}


add_action('wp_ajax_hk_save_user_review', 'hk_save_user_review_callback');
add_action('wp_ajax_nopriv_hk_save_user_review', 'hk_save_user_review_callback'); // Allow non-logged-in users to send email

function hk_save_user_review_callback() {
	if($_POST) {
		// var_dump($_POST);
		global $wpdb;
		$data = array(
			'user_rating' =>$_POST['user_rating'],
			'hk_reviewDescription' => $_POST['hk_reviewDescription'],
			'hk_reviewerName' => $_POST['hk_reviewerName'],
			'hk_jober_id' => isset($_POST['hk_jober_id']) ? $_POST['hk_jober_id'] : 0,
			'hk_job_id' => isset($_POST['hk_job_id']) ? $_POST['hk_job_id'] : 0,
		);

		$table_name = $wpdb->prefix . 'user_rating';
		$wpdb->insert( $table_name, $data );

		if ( $wpdb->insert_id ) {
			wp_send_json(array('success' => true, 'message' => 'Review saved successfully!'));
		} else {
			// Error occurred
			wp_send_json( array( 'success' => false, 'message' => 'Error saving review.' ) );
		}
	}
	exit();
}

add_action('wp_ajax_hk_review_data', 'hk_review_data_callback');
add_action('wp_ajax_nopriv_hk_review_data', 'hk_review_data_callback'); 

function hk_review_data_callback() {
	if($_POST) {
		// var_dump($_POST);
		global $wpdb;

		$html = "<div>";
		if(isset($_POST['hk_jober_id'])) {
			$jober_id = $_POST['hk_jober_id'];
			$table_name = $wpdb->prefix . 'user_rating';
			$review_data = $wpdb->get_results("SELECT * from {$table_name} WHERE hk_jober_id={$jober_id} AND hk_job_id = '' ORDER BY id DESC LIMIT 5");
			foreach ($review_data as $value) {
				$html .= '<div class="review">';
				$html .= '<span><i class="fas fa-star"></i>' . $value->user_rating . '</span>';
				$html .= '<h3>Reviewer: ' . $value->hk_reviewerName . '</h3>';
				$html .= '<p>Description: ' . $value->hk_reviewDescription . '</p>';
				// End HTML output for each review
				$html .= '</div>';
			}
		} else {

			$job_id = $_POST['hk_job_id'];
			$table_name = $wpdb->prefix . 'user_rating';
			$review_data = $wpdb->get_results("SELECT * from {$table_name} WHERE hk_jober_id='' AND hk_job_id={$job_id} ORDER BY id DESC LIMIT 5");
			foreach ($review_data as $value) {
				$html .= '<div class="review">';
				$html .= '<span><i class="fas fa-star"></i>' . $value->user_rating . '</span>';
				$html .= '<h3>Reviewer: ' . $value->hk_reviewerName . '</h3>';
				$html .= '<p>Description: ' . $value->hk_reviewDescription . '</p>';
				// End HTML output for each review
				$html .= '</div>';
			}
		}
		
		$html .= "</div>";

		echo $html;
		die;
	}
}


function custom_posts_per_page($query) {
    if (wp_is_mobile() && $query->is_main_query()) {
        $query->set('posts_per_page', 6);
    }
}
add_action('pre_get_posts', 'custom_posts_per_page');


function hk_chat_data_admin_menu() {
    add_menu_page(
        'Chat', // Page title
        'Chat', // Menu title
        'manage_options', // Capability required to access the menu
        'chat', // Menu slug
        'hk_chat_data_callback', // Callback function to display the page content
        'dashicons-format-chat'
    );
}
add_action('admin_menu', 'hk_chat_data_admin_menu');

function hk_send_chat_message($userid, $re_userid, $sender_id, $message) {
    global $wpdb;
    $chat_table_name = $wpdb->prefix . 'chat';
    
    // Insert chat message into the database
    $wpdb->insert(
        $chat_table_name,
        array(
            'userid' => $userid,
            're_userid' => $re_userid,
            'sender_id' => $sender_id,
            'chat_msg' => $message,
            'chat_date' => current_time('mysql')
        )
    );

    // After inserting the message, trigger the email notification
    if ($wpdb->insert_id) {
        // After inserting the message, trigger the email notification
        hk_notify_user_on_new_chat($re_userid, $sender_id, $message);
    } else {
        error_log('Failed to insert chat message into the database.');
    }
}

function hk_notify_user_on_new_chat($receiver_id, $sender_id, $message_content) {
    $user_info = get_userdata($receiver_id);
    if (!$user_info) {
        error_log('Receiver user not found: ' . $receiver_id);
        return;
    }
    $email = $user_info->user_email;

    // Get the sender's user info
    $sender_info = get_userdata($sender_id);
    if (!$sender_info) {
        error_log('Sender user not found: ' . $sender_id);
        return;
    }
    $sender_name = $sender_info->display_name;

    // Prepare the email
    $subject = 'You have received a new chat message';
    $message = sprintf(
        "Hello %s,\n\nYou have received a new message from %s:\n\n%s\n\nPlease log in to your account to respond.",
        $user_info->display_name,
        $sender_name,
        $message_content
    );
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    // Send the email
    $email_sent = wp_mail($email, $subject, $message, $headers);
    
    // Log email status
    if ($email_sent) {
        error_log('Email notification sent to ' . $email);
    } else {
        error_log('Failed to send email notification to ' . $email);
    }
}

// Callback function to display the page content for the top-level menu
function hk_chat_data_callback() {
	global $wpdb;

    // Get all data from the wp_chat table
    $chat_table_name = $wpdb->prefix . 'chat'; // Table name with prefix
    $chats = $wpdb->get_results( "SELECT * FROM $chat_table_name WHERE userid IS NOT NULL", ARRAY_A);
	?>
	<table class="wp-list-table widefat fixed striped table-view-list posts">
		<thead>
			<tr>
				<th>User Name</th>
				<th>RE-User Name</th>
				<th>Sender Name</th>
				<th>Message</th>
				<th>Chat date</th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php
			foreach ($chats as $chat) {
				$user_data = get_user_by('id', $chat['userid']);
				$username = $user_data->display_name;
				if($username != '') {
					$reuser_data = get_user_by('id', $chat['re_userid']);
					$reuser_name = $reuser_data->display_name;

					$sender_data = get_user_by('id', $chat['sender_id']);
					$sender_name = $sender_data->display_name;
					?>
					<tr>
						<td><?php echo $username; ?></td>
						<td><?php echo $reuser_name; ?></td>
						<td><?php echo $sender_name; ?></td>
						<td><?php echo $chat['chat_msg'];?></td>
						<td><?php echo $chat['chat_date'];?></td>
					</tr>
			<?php 
				} 
			} ?>
		</tbody>
	</table>
	<?php

}


// Add the date field to the general product data tab

add_action('dokan_product_edit_after_downloadable', 'add_custom_box_after_downloadable', 10, 2);

function add_custom_box_after_downloadable($post, $post_id) {
    ?>
    <div class="dokan-form-group " style="width: 40%;">
        <label for="_instock_date" class="form-label"><?php _e('In-stock Date', 'your-text-domain'); ?></label>
        <input type="date" name="_instock_date" id="_instock_date" value="<?php echo esc_attr(get_post_meta($post_id, '_instock_date', true)); ?>" class="dokan-form-control"/>
    </div>
    <?php
}

 add_action('dokan_process_product_meta', 'save_custom_field_data', 10, 1);

function save_custom_field_data($post_id) {
    if (isset($_POST['_instock_date'])) {
        $custom_date_field = sanitize_text_field($_POST['_instock_date']);
        update_post_meta($post_id, '_instock_date', $custom_date_field);
    }
}
 
 
add_action('woocommerce_product_options_inventory_product_data', 'add_custom_date_field');
function add_custom_date_field() {
    woocommerce_wp_text_input( array(
        'id' => '_instock_date',
		'class'=>'instock_date',
        'label' => __('Restock date', 'woocommerce'),
        'placeholder' => 'YYYY-MM-DD',
        'description' => __('Enter a Restock date for this product.', 'woocommerce'),
        'type' => 'date',
        'desc_tip' => 'true',
		'custom_attributes' => array(
            'style' => 'float:left;'
        )
    ));
}

// Save the custom field value
add_action('woocommerce_process_product_meta', 'save_custom_date_field');
function save_custom_date_field($post_id) {
    $custom_date_field = isset($_POST['_instock_date']) ? sanitize_text_field($_POST['_instock_date']) : '';
    update_post_meta($post_id, '_instock_date', $custom_date_field);
}


 

// Schedule the cron job event
add_action('wp', 'schedule_restock_cron_job');
function schedule_restock_cron_job() {
    if (!wp_next_scheduled('restock_products_event')) {
        wp_schedule_event(time(), 'daily', 'restock_products_event');
    }
}

// Hook the function to the cron event
add_action('restock_products_event', 'restock_products');
function restock_products() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_instock_date',
                'value' => date('Y-m-d'),
                'compare' => '='
            ),
            array(
                'key' => '_stock_status',
                'value' => 'outofstock',
                'compare' => '='
            )
        )
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product_id = get_the_ID();

            // Update product stock status to in stock
            update_post_meta($product_id, '_stock_status', 'instock');
            wc_update_product_stock_status($product_id, 'instock');

            // Log for debugging
            error_log("Product ID $product_id restocked.");
        }
        wp_reset_postdata();
    } else {
        // Log if no products found
        error_log("No products to restock.");
    }
}

// Clear the cron job on plugin/theme deactivation
register_deactivation_hook(__FILE__, 'clear_restock_cron_job');
function clear_restock_cron_job() {
    $timestamp = wp_next_scheduled('restock_products_event');
    wp_unschedule_event($timestamp, 'restock_products_event');
}

add_action('admin_footer', 'initialize_datepicker');
function initialize_datepicker() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
			
		   $('#_instock_date').css({
                'float': 'left'
            }); 
			
         
        });
    </script>
   <?php 
}



// Register custom REST API endpoint for job listings
function register_job_listings_endpoint() {
    register_rest_route('custom/v1', '/jobs', array(
        'methods'  => 'GET',
        'callback' => 'get_job_listings',
        'permission_callback' => '__return_true', // Public access
    ));
}
add_action('rest_api_init', 'register_job_listings_endpoint');


// Callback function to handle the API request
function get_job_listings(WP_REST_Request $request) {
    // Log the request parameters
    error_log(print_r($request->get_params(), true));

    // Define query arguments
    $args = array(
        'post_type'      => 'job',
        'posts_per_page' => -1,
        'paged'          => $request->get_param('paged') ? $request->get_param('paged') : 1,
        'post_status'    => 'publish',
    );

    // Add filters based on query parameters
    if ($request->get_param('job_category')) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'job_category', // Your custom taxonomy
                'field'    => 'term_id', // Or 'slug' if you are passing slugs
                'terms'    => (int) $request->get_param('job_category'),
                'operator' => 'IN',
            ),
        );
    }

    if ($request->get_param('reg_post_code')) {
        $args['meta_query'][] = array(
            'key'     => 'reg_post_code',
            'value'   => sanitize_text_field($request->get_param('reg_post_code')),
            'compare' => '=',
        );
    }

    // Execute the query
    $query = new WP_Query($args);

    // Log the query
    error_log(print_r($query->request, true));

    // Collect job data
    $jobs = array();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $job_id = get_the_ID();
			$categories = wp_get_post_terms($job_id, 'job_category', array('fields' => 'names'));
            $jobs[] = array(
                'id'              => $job_id,
                'title'           => get_the_title(),
                'url'             => get_permalink(),
                'author'          => array(
                    'id'    => get_post_field('post_author', $job_id),
                    'name'  => get_the_author_meta('display_name', get_post_field('post_author', $job_id)),
                ),
                'rating'          => get_post_meta($job_id, 'average_rating', true),
				'job_category'    => $categories,
                'qualifications'  => get_post_meta($job_id, 'qualifications', true),
                'reg_miles_km'    => get_post_meta($job_id, 'reg_miles_km', true),
                'cost_per_hour'   => get_post_meta($job_id, 'cost_per_hour', true),
                'reg_post_code'   => get_post_meta($job_id, 'reg_post_code', true),
            );
        }
        wp_reset_postdata();
    }

    // Log the result
    error_log(print_r($jobs, true));

    return rest_ensure_response($jobs);
}

// Register API endpoint to get job categories
function hk_get_job_categories_rest_api() {
    register_rest_route('custom/v1', '/job-categories', array(
        'methods' => 'GET',
        'callback' => 'hk_get_job_categories',
    ));
}

// Callback function to retrieve job categories
function hk_get_job_categories() {
    $terms = get_terms(array(
        'taxonomy' => 'job_category',
        'hide_empty' => false,
    ));

    if (is_wp_error($terms)) {
        return new WP_Error('term_error', 'Unable to retrieve job categories', array('status' => 500));
    }

    $response = array();

    foreach ($terms as $term) {
        $response[] = array(
            'term_id' => $term->term_id,
            'name' => $term->name,
            'slug' => $term->slug,
        );
    }

    return rest_ensure_response($response);
}

add_action('rest_api_init', 'hk_get_job_categories_rest_api');



// Register the custom REST API endpoint for user signup
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/signup', array(
        'methods' => 'POST',
        'callback' => 'handle_jobers_signup',
        'permission_callback' => '__return_true', // Adjust permissions as needed
    ));
});

function handle_jobers_signup(WP_REST_Request $request) {
    $username = sanitize_user($request->get_param('username'));
    $email = sanitize_email($request->get_param('email'));
    $password = sanitize_text_field($request->get_param('password'));
    $qualification = sanitize_text_field($request->get_param('qualification'));
    $post_code = sanitize_text_field($request->get_param('post_code'));
    $latitude = sanitize_text_field($request->get_param('latitude'));
    $longitude = sanitize_text_field($request->get_param('longitude'));
    $position = strtolower(sanitize_text_field($request->get_param('position'))); // Convert to lowercase
    $registration_number = sanitize_text_field($request->get_param('registration_number'));
    $city = sanitize_text_field($request->get_param('city'));
    $job_category = (int) $request->get_param('job_category'); // Category ID

    // Validate position (dropdown value)
    $allowed_positions = get_terms(array(
        'taxonomy' => 'job_category',
        'fields' => 'names', // Return term names
        'hide_empty' => false,
    ));

    // Convert allowed positions to lowercase for comparison
    $allowed_positions_lower = array_map('strtolower', $allowed_positions);

    if (!in_array($position, $allowed_positions_lower)) {
        return new WP_Error('invalid_position', 'Invalid position selected.', array('status' => 400));
    }

    // Create user
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        return new WP_Error('user_creation_failed', 'User creation failed.', array('status' => 500));
    }

    // Add user meta data
    update_user_meta($user_id, 'jober_qualification', $qualification);
    update_user_meta($user_id, 'reg_post_code', $post_code);
    update_user_meta($user_id, 'reg_post_code_latitude', $latitude);
    update_user_meta($user_id, 'reg_post_code_longitude', $longitude);
    update_user_meta($user_id, 'select_position', $position);
    update_user_meta($user_id, 'professional_registration_number', $registration_number);
    update_user_meta($user_id, 'jober_city', $city);

    // Assign role
    $user = new WP_User($user_id);
    $user->add_role('jobers');

    // Assign job category to the user using the provided term ID
    if ($job_category) {
        wp_set_object_terms($user_id, $job_category, 'job_category');
    }

    // Fetch job category name(s)
    $job_category_names = array();
    if ($job_category) {
        $term = get_term($job_category, 'job_category');
        if (!is_wp_error($term)) {
            $job_category_names[] = $term->name;
        }
    }

    // Manually trigger WooCommerce new account email
    if (class_exists('WC_Emails')) {
        $mailer = WC()->mailer();
        $email = $mailer->emails['WC_Email_Customer_New_Account'];
        if ($email) {
            $email->trigger($user_id);
        }
    }

    // Return response with user ID and job category name(s)
    return new WP_REST_Response(array(
        'id' => $user_id,
        'username' => $username,
        'email' => $email,
        'qualification' => $qualification,
        'post_code' => $post_code,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'position' => $position,
        'registration_number' => $registration_number,
        'city' => $city,
        'job_category' => $job_category_names,
    ), 200);
}

// Register the custom REST API endpoint for login
add_action('rest_api_init', function () {
    register_rest_route('jobers/v1', '/login', array(
        'methods' => 'POST',
        'callback' => 'jobers_login_handler',
        'permission_callback' => '__return_true', // Adjust permissions as needed
    ));
});

function jobers_login_handler($data) {
    $username = sanitize_text_field($data['username']);
    $password = sanitize_text_field($data['password']);
    
    $credentials = array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => true,
    );
    
    $user = wp_signon($credentials, false);
    
    if (is_wp_error($user)) {
        return new WP_Error('login_failed', 'Login failed', array('status' => 403));
    }

    return new WP_REST_Response(array('message' => 'Login successful', 'user_id' => $user->ID), 200);
};

// Register the custom REST API endpoint for fetching all jobers
add_action('rest_api_init', function () {
    register_rest_route('jobers/v1', '/all', array(
        'methods' => 'GET',
        'callback' => 'get_all_jobers',
        'permission_callback' => '__return_true', // Adjust permissions as needed
    ));
});

function get_all_jobers(WP_REST_Request $request) {
    $args = array(
        'role'    => 'jobers',
        'fields'  => array('ID', 'user_login', 'user_email'),
        'number'  => -1,
    );

    $users = get_users($args);

    if (empty($users)) {
        return new WP_REST_Response(array('message' => 'No jobers found.'), 404);
    }

    $jobers_data = array();

    foreach ($users as $user) {
        $user_id = $user->ID;

        // Get job categories as term IDs
        $job_category_terms = wp_get_object_terms($user_id, 'job_category', array('fields' => 'ids'));

        $jobers_data[] = array(
            'id'                        => $user_id,
            'username'                  => $user->user_login,
            'email'                     => $user->user_email,
            'qualification'             => get_user_meta($user_id, 'jober_qualification', true),
            'post_code'                 => get_user_meta($user_id, 'reg_post_code', true),
            'latitude'                  => get_user_meta($user_id, 'reg_post_code_latitude', true),
            'longitude'                 => get_user_meta($user_id, 'reg_post_code_longitude', true),
            'position'                  => get_user_meta($user_id, 'select_position', true),
            'registration_number'       => get_user_meta($user_id, 'professional_pegistration_number', true),
            'city'                      => get_user_meta($user_id, 'jober_city', true),
            'job_category'              => !is_wp_error($job_category_terms) ? $job_category_terms : array(),
        );
    }

    return new WP_REST_Response($jobers_data, 200);
}


add_action('wp_ajax_hk_job_send_email', 'hk_job_send_email');
add_action('wp_ajax_nopriv_hk_job_send_email', 'hk_job_send_email');

function hk_job_send_email() {
    // Sanitize and retrieve form data
    $user_name = sanitize_text_field($_POST['userName']);
    $hk_qualification = sanitize_text_field($_POST['hk_qualification']);
    $user_message = wp_kses_post($_POST['userMessage']);
    $hk_job_ids = sanitize_text_field($_POST['hk_job_ids']); // Comma-separated job IDs
    $file = isset($_FILES['file']) ? $_FILES['file'] : array();

    $job_ids_array = explode(',', $hk_job_ids);
    $upload_dir = wp_upload_dir();
    $file_path = '';

    // Handle file upload if provided
    if (!empty($file['tmp_name'])) {
        $file_path = $upload_dir['path'] . '/' . basename($file['name']);
        if (!move_uploaded_file($file['tmp_name'], $file_path)) {
            $file_path = '';
        }
    }

    foreach ($job_ids_array as $job_id) {
        $author_id = get_post_field('post_author', $job_id);
        $author_email = get_the_author_meta('user_email', $author_id);
        
        $subject = 'Job Application: ' . get_the_title($job_id);
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $message = '<p><strong>Name:</strong> ' . $user_name . '</p>';
        $message .= '<p><strong>Qualification:</strong> ' . $hk_qualification . '</p>';
        $message .= '<p><strong>Message:</strong> ' . $user_message . '</p>';

        // Add chat link
        $chat_link = '<br><a href="https://allchemists.co.uk/my-account/chat/?current_user_id=' . get_current_user_id() . '&&re_user=' . $author_id . '">Chat Link</a>';
        $message .= $chat_link;

        // Attach file if uploaded
        $attachments = $file_path ? array($file_path) : array();
        
        // Send email to each job's author
        wp_mail($author_email, $subject, $message, $headers, $attachments);
    }

    wp_send_json_success('Email(s) sent successfully.');
}


 function add_separate_shipping_method( $methods ) {
    $methods['separate_shipping'] = 'WC_Separate_Shipping_Method';
	$methods['club_shipping'] = 'WC_Club_Shipping_Method';
    return $methods;
}
add_filter( 'woocommerce_shipping_methods', 'add_separate_shipping_method' );

class WC_Separate_Shipping_Method extends WC_Shipping_Method {

    public function __construct() {
        $this->id = 'separate_shipping';
        $this->method_title = __( 'Non medical deliveries(Up to 10 working days)' );
        $this->method_description = __( 'Non medical deliveries Method for specific groceries products' );
        $this->enabled = "yes";
        $this->title = "Non medical deliveries(Up to 10 working days)";
        $this->init();
    }

    function init() {
        $this->init_form_fields();
        $this->init_settings();
        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
    }

    public function calculate_shipping( $package = array() ) {
        $rate = array(
            'id'    => $this->id,
            'label' => $this->title,
            'cost'  => 3.99,
            'calc_tax' => 'per_item'
        );
        $this->add_rate( $rate );
    }
} 
function modify_job_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_page('dashboard/job') ) {
        // Ensure it's the main query and on the correct page
        $query->set('post_type', 'job');
        $query->set('author', get_current_user_id()); // Assuming $author_id is current user
        $query->set('posts_per_page', 10);
        $query->set('paged', get_query_var('paged') ? get_query_var('paged') : 1);
        $query->set('post_status', array('publish'));
    }
}
add_action( 'pre_get_posts', 'modify_job_query' );
/* class WC_Club_Shipping_Method extends WC_Shipping_Method {

    public function __construct() {
        $this->id = 'club_shipping';
        $this->method_title = __( 'Club Shipping(Up to 10 working days)' );
        $this->method_description = __( 'Club Shipping' );
        $this->enabled = "yes";
        $this->title = "Club Shipping(Up to 10 working days)";
        $this->init();
    }

    function init() {
        $this->init_form_fields();
        $this->init_settings();
        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
    }

    public function calculate_shipping( $package = array() ) {
        $rate = array(
            'id'    => $this->id,
            'label' => $this->title,
            'cost'  => 3.89,
            'calc_tax' => 'per_item'
        );
        $this->add_rate( $rate );
    }
} 
 add_action('woocommerce_cart_totals_before_shipping', 'add_custom_shipping_button_to_cart');

function add_custom_shipping_button_to_cart() { 
    // Display button only on cart page
    if (is_cart()) {
        ?>
        <button type="button" id="apply_club_shipping" class="button alt">Apply Club Shipping</button>
        <div id="shipping_message"></div>
        <?php
    }
}

// Enqueue the custom script
add_action('wp_enqueue_scripts', 'enqueue_custom_cart_script');

function enqueue_custom_cart_script() {
    if (is_cart()) {
        wp_enqueue_script('custom-cart-script', get_stylesheet_directory_uri() . '/assets/js/custom-cart.js', array('jquery'), null, true);
        wp_localize_script('custom-cart-script', 'custom_cart_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('custom_cart_nonce')
        ));
    }
}


add_action('wp_ajax_apply_club_shipping', 'apply_club_shipping');
add_action('wp_ajax_nopriv_apply_club_shipping', 'apply_club_shipping');

function apply_club_shipping() {
    // Verify nonce (for security)
    check_ajax_referer('custom_cart_nonce', 'security');

 
 
    // Check if a shipping method is already set; if not, get the current shipping methods
    if (!WC()->session->get('chosen_shipping_methods')) {
        WC()->session->set('chosen_shipping_methods', array());
    }
 
    // Add "Club Shipping" method to chosen shipping methods
    $chosen_methods = WC()->session->get('chosen_shipping_methods');
	print_r($chosen_methods);
    if ( in_array('club_shipping', $chosen_methods)) {
        $chosen_methods[] = 'club_shipping';
        WC()->session->set('chosen_shipping_methods', $chosen_methods);
    }

    // Trigger recalculation of shipping and cart totals
    WC()->cart->calculate_shipping();
    WC()->cart->calculate_totals();

    // Refresh the cart
    WC()->cart->set_cart_contents($_SESSION['cart']);

    // Send success response
    wp_send_json_success(array('message' => 'Club Shipping has been applied.'));
}
 */
// Add this temporarily to functions.php and load any page to flush rewrite rules
// 
add_action('wp_ajax_load_more_jobs', 'load_more_jobs');
add_action('wp_ajax_nopriv_load_more_jobs', 'load_more_jobs');

function load_more_jobs() {
    check_ajax_referer('load_more_jobs', 'nonce');

    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $args = array(
        'post_type' => 'job',
        'posts_per_page' => 10,
        'paged' => $paged
    );
    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            echo '<tr>';
            echo '<td>' . get_the_id() . '</td>';
            echo '<td>' . get_the_title() . '</td>';
            echo '<td>' . get_the_date() . '</td>';
            echo '<td>' . wp_trim_words(get_the_content(), 4, '...') . '</td>';
            echo '<td>
                <a class="dokan-label dokan-label-success" href="' . site_url() . '/dashboard/job/?edit=' . get_the_id() . '">Edit</a>
                <a class="dokan-label dokan-label-success" onClick="deleteItem(' . get_the_id() . ')" href="javascript:void(0)">Delete</a>
                </td>';
            echo '</tr>';
        }
    } else {
        // No more posts to load
    }
    wp_reset_postdata();
    wp_die(); // This is required to terminate immediately and return a proper response
}

function add_popup_classes() {
    // Check if user is logged in
    $is_logged_in = is_user_logged_in();

    // Output inline style or class based on login status
    if ($is_logged_in) {
        echo '<style>
            #job_popup, #job_mail_popup {
                height: 80%!important; /* Adjust this value as needed */
                max-height: none;
            }
			.popup{
				height: 80%!important; /* Adjust this value as needed */
                max-height: none;
			}
        </style>';
    } else {
        echo '<style>
            #job_popup, #job_mail_popup {
                height: auto!important;
                max-height: 80%!important; /* Adjust this value as needed */
                overflow-y: auto; /* Ensure scrolling within the popup */
            }
			.popup{
				height: auto!important;
                max-height: 80%!important; /* Adjust this value as needed */
                overflow-y: auto; /* Ensure scrolling within the popup */
			}
        </style>';
    }
}
add_action('wp_head', 'add_popup_classes');


function hk_send_email_handler() {
    // Make sure the required fields are present
    if (!isset($_POST['hk_jober_ids']) || !isset($_POST['userName']) || !isset($_POST['hk_postcode']) || !isset($_POST['hk_costperhuor']) || !isset($_POST['hk_qualification']) || !isset($_POST['userMessage'])) {
        wp_send_json_error('Required fields are missing.');
        return;
    }

    // Get the data from the AJAX request
    $jober_ids = $_POST['hk_jober_ids'];
    $user_name = sanitize_text_field($_POST['userName']);
    $postcode = sanitize_text_field($_POST['hk_postcode']);
	$chat_link_base = 'https://allchemists.co.uk/my-account/chat/';
    $cost_per_hour = sanitize_text_field($_POST['hk_costperhuor']);
    $qualification = sanitize_text_field($_POST['hk_qualification']);
    $user_message = wp_kses_post($_POST['userMessage']); // Allow safe HTML tags

    // Loop through the selected jobers and send an email to each
    $current_user_id = get_current_user_id();
    if (!$current_user_id) {
        wp_send_json_error('User not logged in.');
        return;
    }
    foreach ($jober_ids as $jober_id) {
        $user_info = get_userdata($jober_id);
        $jober_email = $user_info->user_email;
		$chat_link = $chat_link_base . '?current_user_id=' . $jober_id . '&re_user=' . $current_user_id;
        $subject = 'Job Opportunity from ' . $user_name;
        $message = "
        Hi {$user_info->display_name},

        You have a new job opportunity. Here are the details:

        Job Name: {$user_name}
        Postcode: {$postcode}
        Hourly Rate: {$cost_per_hour}
        Qualification: {$qualification}
        
        Message:
        {$user_message}
		Chat Link: <a href='{$chat_link}'>Click here to chat</a>
        Best regards,
        {$user_name}
        ";

        // Use WordPress wp_mail function to send the email
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($jober_email, $subject, nl2br($message), $headers);
    }

    wp_send_json_success('Emails sent successfully!');
}

// Register the AJAX action for both logged-in and non-logged-in users
add_action('wp_ajax_hk_send_email', 'hk_send_email_handler');
add_action('wp_ajax_nopriv_hk_send_email', 'hk_send_email_handler');