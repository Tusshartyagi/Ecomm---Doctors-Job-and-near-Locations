<?php
 
// Add custom API endpoint for product data
add_action('rest_api_init', 'custom_register_rest');

function custom_register_rest() {
    register_rest_route('custom/v1', '/product/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_product_questionnaire_api_fun',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
        ),
    ));
	
	register_rest_route('custom/v1', '/save-question/', array( 
        'methods' => 'POST',
        'callback' => 'save_question_api_handler',
        'permission_callback' => '__return_true', // Allow unrestricted access (you should implement proper permission checks)
    ));
}

function get_product_questionnaire_api_fun($data) {
    $product_id = $data['id'];

    // Fetch product data using WooCommerce functions or custom queries
    $product = wc_get_product($product_id);	
	$woo_cat_id = woo_get_product_cat_id($product_id);
    if (!$product) {
        return new WP_Error('error', 'Product not found', array('status' => 404));
    }
	
	$chatbot = false;
	$questions_data = [] ;
	if(get_field('questionnaire_enable',$product_id)){
		
		if( have_rows('product_questions', 'option') ): 
				 $chatbot = true;
					while( have_rows('product_questions', 'option') ): the_row(); 	
					$questionDetails = [];	
					$questionDetails['question_no'] = get_sub_field('sno');
					$questionDetails['question_type'] = 'freetype';
					$questionDetails['question'] = get_sub_field('question');
					$questions_data[] = $questionDetails;	
						
					endwhile; 
		endif; 
		
	}	else {
		if ($woo_cat_id && have_rows('questions', 'product_cat_' . $woo_cat_id)) { 
			$chatbot = false;
			while (have_rows('questions', 'product_cat_' . $woo_cat_id)) : the_row();
			$questionDetails = [];
			$question_no = get_sub_field('question_no');
			$question_type = get_sub_field('question_type');
			$question = get_sub_field('question');
			
			$questionDetails['question_no'] = $question_no;
			$questionDetails['question_type'] = $question_type;
			$questionDetails['question'] = $question;
			$questions_data[] = $questionDetails;
			
			endwhile;
		
		}	
	}
    // Example: Get custom data (replace with your custom fields/meta)
    $custom_data = array(
        'questions' => $questions_data,
        
        // Add more custom fields as needed
    );

    // Prepare the response
    $response = array(
        'id' => $product_id,
        'name' => $product->get_name(), 
		'chatbot' => $chatbot,	
        'questions' => $custom_data,
    );

    return rest_ensure_response($response);
}



 
 

// Callback function to handle API request
function save_question_api_handler($request) {
    // Extract POST data
	 defined( 'WC_ABSPATH' ) || exit;

    // Load cart functions which are loaded only on the front-end.
    include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
    include_once WC_ABSPATH . 'includes/class-wc-cart.php';
	global $woocommerce;
	global $wpdb;
	
    $params = $request->get_params();
	
	$tableName  = $wpdb->prefix."questionnaire";
	$question_details = '1484';
	$cart_items = $woocommerce->cart->cart_contents;
	// $items = $woocommerce->cart->get_cart();
	// Replace with your actual WooCommerce API credentials and site URL
		$consumer_key = 'ck_e9ed6762fc61b57dcb88b5aa602a81047d85f4af';
		$consumer_secret = 'cs_24b5f344c45268521982a78169d42982f4a41648';
		$site_url = 'https://britishchemist.co.uk/'; // Your WordPress site URL

		// Product category slug to check against
		$category_slug = 'your_category_slug';

		// Retrieve cart contents using WooCommerce API
		echo $api_url = $site_url . '/wp-json/wc/v3/cart/';
		$response = wp_remote_get(
			$api_url,
			array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( $consumer_key . ':' . $consumer_secret ),
				),
			)
		);
	echo $consumer_key;
	
	echo '<pre>'; print_r($response ); echo '</pre>';  die;
	//$QuestionAnswerData = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$tableName." WHERE Id = '".$question_details."'" ), ARRAY_A );
	//$QuestionAnswerData['question_answer'];
	//echo '<pre>'; print_r($QuestionAnswerData); echo '</pre>';
	/* 	echo '<pre>'; print_r($params['question_answer'] ); echo '</pre>'; die;	
	$qData = $QuestionAnswerData['question_answer'];
	$unQdata = unserialize($qData);
	$data = $unQdata['question_answer']; */

	// $woocommerce->cart->cart_contents['793d06f888dd5b48a08610675de866ac']['questionnaires_items'] = $params['question_answer'];
	 //$woocommerce->cart->cart_contents['793d06f888dd5b48a08610675de866ac']['prescription_items'] = $params['main_patient_data'];
	 //$woocommerce->cart->cart_contents['793d06f888dd5b48a08610675de866ac']['add_patient_data'] = $params['patient_data'];
 
	
	 
	//echo '<pre>'; print_r(unserialize($data)); echo '</pre>'; die;

    // Check if required parameters are present
    if (isset($params['array_param']) && isset($params['string_param'])) {
        $array_param = $params['array_param'];
        $string_param = $params['string_param'];

        // Process your data here
        // Example: Save data to database, perform actions, etc.

        // Return a response (optional)
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Data processed successfully',
            'data' => array(
                'array_param' => $array_param,
                'string_param' => $string_param,
            ),
        ));
    } else {
        // Handle missing parameters error
        return new WP_Error('missing_params', 'Required parameters are missing.', array('status' => 400));
    }
}

