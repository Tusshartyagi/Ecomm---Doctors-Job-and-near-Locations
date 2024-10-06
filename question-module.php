<?php 
function start_session() {
	//$_SESSION['question_details'] = '';
	//print_r($_SESSION);
	if(!session_id()) {
		session_start();
	}
}
add_action('init', 'start_session', 1);

function woo_get_questions_html()
{ 

	if ((isset($_GET['id']) && !empty($_GET['id'])) || (isset($_GET['term_id']) && !empty($_GET['term_id']))) {
		$html = '';
		if(isset($_GET['term_id']) && !empty($_GET['term_id'])){
			$woo_cat_id = $_GET['term_id'];
		} else {
			$woo_cat_id = woo_get_product_cat_id($_GET['id']);
		}
		$form = isset($_POST['answer']) ? $_POST['answer'] : '';
		$formFree = isset($_POST['free_text']) ? $_POST['free_text'] : '';
		if ($woo_cat_id && have_rows('questions', 'product_cat_' . $woo_cat_id)) {
			global $questionnaires_errors;

			// I agree errors.
			$i_errors = false;
			if ($questionnaires_errors && isset($questionnaires_errors->errors['agree_errors'])) {
				$i_errors = $questionnaires_errors->errors['agree_errors'][0];
			}

			// Questions errors.
			$q_errors = false;
			if ($questionnaires_errors && isset($questionnaires_errors->errors['questionnaires_errors'])) {
				$q_errors = $questionnaires_errors->errors['questionnaires_errors'][0];
			}

			// Answer errors.
			$ans_errors = false;
			if ($questionnaires_errors && isset($questionnaires_errors->errors['q_answers_errors'])) {
				$ans_errors = $questionnaires_errors->errors['q_answers_errors'][0];
			}

			$required_error_msg = __('You must answer this question.', 'woocommerce');
			$agree_error_msg = __('Term and Conditions required!', 'woocommerce');
			$wrong_answer_msg 	= get_field('wrong_answer_message', 'product_cat_' . $woo_cat_id);
			//echo '<pre>'; print_r($form); echo '</pre>';
			$num = 1;
			$html .= '<form method="post" class="wpcf7-form init" action="" id="questions_form">';

			$html .= '<table class="styled-table">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th>' . __('Q No.', 'woocommerce') . '</th>';
			$html .= '<th>' . __('Question', 'woocommerce') . '</th>';
			$html .= '<th>' . __('Answer', 'woocommerce') . '</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			while (have_rows('questions', 'product_cat_' . $woo_cat_id)) : the_row();
				$question_no = get_sub_field('question_no');
				$question_type = get_sub_field('question_type');
				$html .= '<tr>';
				$html .= '<td width="100">' . $num . '</td>';
				$html .= '<td>' . get_sub_field('question');

				$ans_style = ($ans_errors && in_array($question_no, $ans_errors)) ? 'block' : 'none';
			 	$html .= '<div  class="qest_errors" id="error_' . $question_no . '" style="display:' . $ans_style . ';">' . $required_error_msg . '</div>'; 

				$q_style = ($q_errors && in_array($question_no, $q_errors)) ? 'block' : 'none';
				$html .= '<div  class="qest_errors" id="server_error_' . $question_no . '" style="display:' . $q_style . ';">' . $required_error_msg . '</div>';
				
				$html .= '<textarea class="textArea_box" id="free_text_'.$question_no.'" name="free_text[]" style="display:' . $ans_style . ';">'.$formFree[$question_no-1].'</textarea>';

				$html .= '</td>';
				$html .= '<td width="200" class="answer-input" width="140px">';
				if($question_type == 'freetype'){
					$freeTextBox = '';
					if(!empty($form)){
						$freeTextBox = $form[$question_no];
						
					}
					
					
					$html .= '<span>';
					$html .= '<textarea class="answer_box" name="answer[' . $question_no . ']" value="">'.$freeTextBox.'</textarea>';
					$html .= '</span>';
				} else {
					$html .= '<span>';
					$html .= '<input type="radio" id="ans_y' . $question_no . '" class="radio_answer" data-id="' . $question_no . '" name="answer[' . $question_no . ']" value="yes" data-value="';
					$html .= get_sub_field('answer') == 'yes' ? 1 : 0;
					$html .= '" ';
					$html .= !empty($form) && isset($form[$question_no]) && $form[$question_no] == 'yes' ? 'checked="checked"' : '';
					$html .= '>';				
					$html .= '<label for="ans_y' . $question_no . '">' . __('Yes', 'woocommerce') . '</label>';
					$html .= '</span>'; 
					 $html .= '<span>';
					$html .= '<input type="radio" id="ans_n' . $question_no . '" class="radio_answer" data-id="' . $question_no . '" name="answer[' . $question_no . ']" value="no" data-value="';
					$html .= get_sub_field('answer') == 'no' ? 1 : 0;
					$html .= '" ';
					$html .= !empty($form) && isset($form[$question_no]) && $form[$question_no] == 'no' ? 'checked="checked"' : '';
					$html .= '>';
					$html .= '<label for="ans_n' . $question_no . '">' . __('No', 'woocommerce') . '</label>';
					$html .= '</span>'; 
				
				}
				$html .= '</td>';
				$html .= '</tr>';
				$num++;
			endwhile;
			$html .= '</tbody>';
			$html .= '</table>';

			$title = (!empty($_REQUEST['title'])) ? $_REQUEST['title'] : '';
			$first_name = (!empty($_REQUEST['first-name'])) ? $_REQUEST['first-name'] : '';
			$last_name = (!empty($_REQUEST['last-name'])) ? $_REQUEST['last-name'] : '';
			$dob = (!empty($_REQUEST['dob'])) ? $_REQUEST['dob'] : '';
			$phone = (!empty($_REQUEST['your-phone'])) ? $_REQUEST['your-phone'] : '';
			$address = (!empty($_REQUEST['address'])) ? $_REQUEST['address'] : '';
			$postcode = (!empty($_REQUEST['postcode'])) ? $_REQUEST['postcode'] : '';
			$door_number = (!empty($_REQUEST['door_number'])) ? $_REQUEST['door_number'] : '';
			$html_iiner = '';
			$html .= '<div class="white-featured-box container">';
			if (is_user_logged_in()) {
				$autofill_cheked = isset($_POST['autofill_register_details']) ? 'checked="checked"' : '';
				//$html_iiner .= '<div class="row"><div class="col-xs-12 col-md-12">';
				$html_iiner .= '<input type="checkbox" name="autofill_register_details" id="autofill_register_details" value="1" ' . $autofill_cheked . '>';
				$html_iiner .= '<label for="autofill_register_details">&nbsp;Autofill your registered details.</label>';
				//$html_iiner .= '</div></div>';
			}
			$html .= '<div class="row">						
						<div class="col-xs-12 col-md-12 column-12 column-tablet-6 column-desktop-6">'.$html_iiner.'
							<div class="column-12 column-tablet-12 column-desktop-12  marbottom20">
							<span>First Name *</span><span class="wpcf7-form-control-wrap first-name"><input type="text" id="consultation_first_name" name="first-name" value="'.$first_name.'" size="40" class="wpcf7-form-control wpcf7-text " placeholder="First Name" required></span></div>
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">
							<span>Last Name *</span><span class="wpcf7-form-control-wrap last-name"><input type="text" id="consultation_last_name" name="last-name" value="'.$last_name.'" size="40" class="wpcf7-form-control wpcf7-text " placeholder="Last Name" required></span></div>
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">
							<span>Date of birth *</span><span class="wpcf7-form-control-wrap dob"><input type="text" id="consultation_dob" name="dob" value="'.$dob.'" class="wpcf7-form-control wpcf7-text " autocomplete="off" placeholder="Date of birth" required></span></div>
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">
							<span>Contact Number *</span><span class="wpcf7-form-control-wrap your-phone"><input id="consultation_phone" type="tel" name="your-phone" value="'.$phone.'" size="40" class="wpcf7-form-control wpcf7-text wpcf7-tel  wpcf7-validates-as-tel" placeholder="Contact Number" required></span> </div>
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">
							<span>Door number *</span><span class="wpcf7-form-control-wrap dob"><input type="text" id="consultation_door_num" name="door_number" value="'.$door_number.'" class="wpcf7-form-control wpcf7-text " autocomplete="off" placeholder="Door number" required></span></div>
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">
							<span>Search address by postcode *<br/> Note:- Please don\'t repeat door number</span><span class="wpcf7-form-control-wrap address"><input id="consultation_address" value="'.$address.'" name="address" type="text" class="wpcf7-form-control wpcf7-text " autocomplete="off" placeholder="Search address by postcode.." required></input></span>	</div>
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">							
							<span>Postal code *</span><span class="wpcf7-form-control-wrap postcode"><input type="text" id="consultation_postcode" name="postcode" value="'.$postcode.'" size="40" class="wpcf7-form-control wpcf7-text " placeholder="Postal code..." required></span></div>
						</div> 
									
						<div class="col-xs-12 col-md-12 column-12 column-tablet-6 column-desktop-6" >
							<input type="checkbox" name="add_patient" id="add_patient" value="1"><label for="add_patient">&nbsp;Check to add patient.</label>
							<div class="patientForm" style="display:none;">
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">
							<span>Patient First Name *</span><span class="wpcf7-form-control-wrap patient_first-name"><input type="text" id="patient_consultation_first_name" name="patient_first-name" value="" size="40" class="wpcf7-form-control wpcf7-text requiredFields" placeholder="Patient First Name" ></span>
							</div>
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">
							<span>Patient Last Name *</span><span class="wpcf7-form-control-wrap patient_last-name"><input type="text" id="patient_consultation_last_name" name="patient_last-name" value="" size="40" class="wpcf7-form-control wpcf7-text  requiredFields" placeholder="Patient Last Name" ></span></div>
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">
							<span>Patient Date of birth *</span><span class="wpcf7-form-control-wrap patient_dob"><input type="text" id="patient_consultation_dob" name="patient_dob" value="" class="wpcf7-form-control wpcf7-text  requiredFields" autocomplete="off" placeholder="Patient Date of birth" ></span></div>
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">
							<span>Patient Contact Number *</span><span class="wpcf7-form-control-wrap patient_your-phone"><input id="patient_consultation_phone" type="tel" name="patient_your-phone" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-tel requiredFields wpcf7-validates-as-tel" placeholder="Patient Contact Number" ></span></div>
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">
							<span>Patient Door number *</span><span class="wpcf7-form-control-wrap dob"><input type="text" id="patient_consultation_door_num" name="patient_door_number" value="" class="wpcf7-form-control wpcf7-text  requiredFields" autocomplete="off" placeholder="Door number" ></span></div>
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">
							<span>Search address by postcode *<br/> Note:- Please don\'t repeat door number</span><span class="wpcf7-form-control-wrap address"><input type="text" id="patient_consultation_address" value="" name="patient_address" class="wpcf7-form-control wpcf7-text  requiredFields" autocomplete="off" placeholder="Search address by postcode.." ></input></span></div>
							<div class="column-12 column-tablet-12 column-desktop-12 marbottom20">		
							<span>Postal code *</span><span class="wpcf7-form-control-wrap patient_postcode"><input type="text" id="patient_consultation_postcode" name="patient_postcode" value="" size="40" class="wpcf7-form-control wpcf7-text  requiredFields" placeholder="Postal code..." ></span></div></div></div>';
			$html .= '</div>';

			$country_code = (!empty($_REQUEST['country_code'])) ? $_REQUEST['country_code'] : '';
			$address_1 = (!empty($_REQUEST['address_1'])) ? $_REQUEST['address_1'] : '';
			$address_2 = (!empty($_REQUEST['address_2'])) ? $_REQUEST['address_2'] : '';
			$city = (!empty($_REQUEST['city'])) ? $_REQUEST['city'] : '';
			$state = (!empty($_REQUEST['state'])) ? $_REQUEST['state'] : '';
			$door_number = (!empty($_REQUEST['door_number'])) ? $_REQUEST['door_number'] : '';

			$html .='<input type="hidden" value="'.$country_code.'" id="consultation_country" name="country_code">';
			$html .='<input type="hidden" value="'.$address_1.'" id="consultation_address_1" name="address_1">';
			$html .='<input type="hidden" value="'.$address_2.'" id="consultation_address_2" name="address_2">';
			$html .='<input type="hidden" value="'.$city.'" id="consultation_city" name="city">';
			$html .='<input type="hidden" value="'.$state.'" id="consultation_state" name="state">';

			$cheked = isset($_POST['qustionnaires_terms_check']) ? 'checked="checked"' : '';
			$html .= '<div class="questions_checkbox">';
			$html .= '<input type="checkbox" name="qustionnaires_terms_check" id="qustionnaires_terms_check" class="btn btn-primary" value="1" ' . $cheked . '>';
			$html .= '<label>' . 'I confirm that I am over 18 and I agree to the <a href="' . site_url('/term-conditions/') . '" target="_blank">Terms and Conditions</a>.</label>';
			$html .= '</div>';

			$agree_style = $i_errors ? 'block' : 'none';
			//$agree_style = 'none';
			$html .= '<div  class="qest_errors" id="q_i_agree_error" style="display:' . $agree_style . ';">' . $agree_error_msg . '</div>';
			$html .= '<div  class="required_error" id="required_error" style="display:none; color:#f00;">Please fill all required fields</div>';
			
			$html .= '<input type="submit" name="qustionnaires_form_submit" id="qustionnaires_form_submit" class="btn btn-primary" value="Submit">';
			if(isset($_GET['term_id']) && !empty($_GET['term_id'])) {
				$html .= '<input type="hidden" name="term_id" value="' . $_GET['term_id'] . '">';
			}
			$html .= '<input type="hidden" name="id" value="' . $_GET['id'] . '">';
			$html .= wp_nonce_field('questionnaires_form_action', 'questionnaires_generate_nonce_field');
			$html .= '</form>';			

		} else { 
			$chatBotQuestion = '';
			if(get_field('questionnaire_enable',$_GET['id'])){ 
				
				if(get_field('questionnaire_enable',$_GET['id']) && get_field('individual_questions_enabled',$_GET['id'])){ /* echo "Trilok"; die; */
				
								
								// Check rows existexists.
				if( have_rows('question_row', $_GET['id']) ):
					$l=1;
					// Loop through rows.
					while( have_rows('question_row', $_GET['id']) ) : the_row();

						// Load sub field value.
						$question = get_sub_field('question');
						$question_type = get_sub_field('question_type');
						$add_options = get_sub_field('add_options');
					
						
						if($question_type == 'Selection'){
							
							
							$options = '';
							if(isset($add_options) && !empty($add_options)){
								foreach($add_options as $op_value){
									$options .= '<option value="'.$op_value['add_option_row'].'">'.$op_value['add_option_row'].'</option>';									
								}				
																
							}
							$chatBotQuestion .= '<select name="question_'.$l.'" data-conv-question="'.$question.'">"'.$options.'"</select>';
							
						} else {
							
							$chatBotQuestion .=  '<input type="text" name="question_'.$l.'" data-conv-question="'.$question.'" >';
							
						}
						$l++;
					// End loop.
					endwhile;

				// No value.
				else :
					// Do something...
				endif;				
				 
				} elseif(get_field('questionnaire_enable',$_GET['id']) && !get_field('individual_questions_enabled',$_GET['id'])) {
			
					 if( have_rows('product_questions', 'option') ): 
					while( have_rows('product_questions', 'option') ): the_row(); 					
						$chatBotQuestion .=  '<input type="text" name="question_'.get_sub_field('sno').'" data-conv-question="'.get_sub_field('question').'">';
	
					endwhile; 
				endif;
				} 
				
				
							
			
				return $html 	=	
						'<section id="demo">
						<div class="vertical-align">
							<div class="container">
								<div class="row">
									<div class="col-sm-6 col-sm-offset-3 col-xs-offset-0 chatbotcustombox">
										<div class="card no-border">
										<div class="header-section">
												<img title="Safe for pregnancy" class="safe-preg-icon" src="' . get_stylesheet_directory_uri() . '/assets/images/doctor-icon.svg">
											<p>
												Please answer the following questions as honestly as possible so our pharmacist can assess its suitability for the intended user
											</p>
										</div>
											<div id="chat" class="conv-form-wrapper">
												<form action="" method="POST" id="questions_form" class="hidden" name="chatbot_question">'.$chatBotQuestion.'</form> 
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>';
				
		
			}  
			//return '<h4>' . __('No Questionnaires!', 'woocommerce') . '</h4>';
		}
		return $html;
	} else {
		return '<h4>' . __('No any product seleted!', 'woocommerce') . '</h4>';
	}
	return false;
}
add_shortcode('woo-questionnaires', 'woo_get_questions_html');
/**
 * Questions form submission handler.
 */
function woo_question_form_submit()
{	
	global $woocommerce;
	global $wpdb;
	
	//echo '<pre>'; print_r($_POST); echo '</pre>'; die;
 	
	if(isset($_POST['question_1']) && !empty($_POST['question_1'])){ 
	//echo '<pre>'; print_r($_POST); echo '</pre>'; die;
		$product_id = $_REQUEST['id'];
			///echo '<pre>'; print_r($_POST); echo '</pre>';
		
		 if (!WC()->cart->is_empty()) { 
			$questionAnsArr =array();
			$dabarray =array();
			
		if(get_field('questionnaire_enable',$product_id) && get_field('individual_questions_enabled',$product_id)){ 
		
		if( have_rows('question_row', $product_id) ){
					$l=1;					
					while( have_rows('question_row', $product_id) ) : the_row();

						// Load sub field value.
						$question = get_sub_field('question');						
					
					
					$dabarray['questionno'] = $l;
					$dabarray['question'] = $question;
					$dabarray['answer'] = $_POST['question_'.$l];	
					$questionAnsArr[] = $dabarray;
					
						$l++;
			
					endwhile;			
		 }
		
		
		} elseif(get_field('questionnaire_enable',$product_id) && !get_field('individual_questions_enabled',$product_id)) { 
			
			if( have_rows('product_questions', 'option') ): 
				while( have_rows('product_questions', 'option') ): the_row();
					$question_no = get_sub_field('sno');
					$question = get_sub_field('question');
					$dabarray['questionno'] = $question_no;
					$dabarray['question'] = $question;
					$dabarray['answer'] = $_POST['question_'.$question_no];	
					$questionAnsArr[] = $dabarray;

				endwhile; 
			endif;
		} 
		
		
		//echo '<pre>'; print_r($questionAnsArr); echo '</pre>'; die;
			
			
			
			
			
			// update cart item questionnaire.
			woo_update_cart_item_questionnaire_bot($product_id, $questionAnsArr);
	
	
					wp_redirect(site_url('/cart/'));
		
			exit;
		} else {  
			wp_redirect(site_url('/cart/'));
		// do nothing, if cart is empty.
		} 
	}
	// Catogery Questionnaire			
	if ((isset($_POST['id']) && !empty($_POST['id'])) || (isset($_POST['term_id']) && !empty($_POST['term_id']))) {
		if (isset($_POST['qustionnaires_form_submit'])) {
			if (!wp_verify_nonce($_POST['questionnaires_generate_nonce_field'], 'questionnaires_form_action'))
			{
				wp_die('Our Site is protected!!');
			} else {
				
				
				$product_id = $_POST['id'];
				$pro_term_id = $_POST['term_id'];
				$queryParm = '';
				if(isset($pro_term_id) && !empty($pro_term_id)){
					
					$queryParm = 'term_id';
					$product_id = $pro_term_id;
					
				}
				// Get real questions value from backend.
				$real_questions =  woo_get_product_cat_questionnaire($product_id , $queryParm);
				//pre($real_questions);
				$errors = array();
				$ans_errors = array();
				$chk_errors = false;
				if (!isset($_POST['qustionnaires_terms_check']) || empty($_POST['qustionnaires_terms_check']))
				{
					$chk_errors = true;
				}				
				if (!isset($_POST['answer'])) {
					$errors = $real_questions ? array_keys($real_questions) : false;
				} elseif (isset($_POST['answer'])) {
					$form_answers = $_POST['answer'];
					if ($real_questions) {
						foreach ($real_questions as $k => $v) {
							// Check form ansers keys are same as real questions keys.
							if (!array_key_exists($k, $form_answers)) {
								$errors[] = $k;
							} else {
								// Check form answers is equal to value of real questions or not.
								if ($form_answers[$k] != $v)
									$ans_errors[] = $k;
							}
						}
					}
				}
				
				$ans_errors_2 = array();
				if(isset($ans_errors) && !empty($ans_errors)){
					
					foreach($ans_errors as $ans_errors_freetext){
						
						if($_POST['free_text'][$ans_errors_freetext-1] == ''){
							
							$ans_errors_2[] = $ans_errors_freetext;
						} 
						
						
					}
					
					
				}
				if (!empty($errors) || !empty($ans_errors_2) || $chk_errors) {
					global $questionnaires_errors;

					$questionnaires_errors = new WP_Error();

					if (!empty($errors))
						$questionnaires_errors->add('questionnaires_errors', $errors);

					/* if (!empty($ans_errors))
						$questionnaires_errors->add('q_answers_errors', $ans_errors); */ 
					if (!empty($ans_errors_2))
						$questionnaires_errors->add('q_answers_errors', $ans_errors_2);
					
					if ($chk_errors)
						$questionnaires_errors->add('agree_errors', true);
					
					
				
					return $questionnaires_errors;
				} else { 
						//pre($_POST);
						$prescription = array();
						$prescription['title'] = $_POST['title'];
						$prescription['first-name'] = $_POST['first-name'];
						$prescription['last-name'] = $_POST['last-name'];
						$prescription['your-phone'] = $_POST['your-phone'];
						$prescription['address'] = $_POST['address'];
						$prescription['dob'] = $_POST['dob'];
						$prescription['postcode'] = $_POST['postcode'];
						$prescription['country_code'] = $_POST['country_code'];
						$prescription['address_1'] = $_POST['address_1'];
						$prescription['address_2'] = $_POST['address_2'];
						$prescription['city'] = $_POST['city'];
						$prescription['state'] = $_POST['state'];
						$prescription['door_number'] = $_POST['door_number'];

						
						// update cart item questionnaire.
						woo_update_cart_item_questionnaire($product_id, $_POST, $prescription, $queryParm);
						
						//woo_update_similar_cart_item_questionnaire();
						$catID = woo_get_product_cat_id($product_id);
						if(isset($pro_term_id) && !empty($pro_term_id)){
							$catID = $pro_term_id;
						}	
							
						if($catID){						
							$term = get_term( $catID, 'product_cat' ); 
							$term_link = get_term_link( $term); 
							wp_redirect($term_link);
						} else {
							wp_redirect(site_url('/cart/'));
						}	
						die;
				
				 }
			}
		}
	}
}
add_action('init', 'woo_question_form_submit');
/** 
 * Get product category by product id.
 */
function woo_get_product_cat_id($product_id = 0 , $questionnaire=null)
{

	if ($product_id) {

		$term_list 		 = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids')); 
		
	
		$removed_term_id = 352; // 'Treatment' category id.
		//echo '<pre>'; print_r($term_list); echo '</pre>'; 
		// Removed the 'Treatment' id from array.
		if (($key = array_search($removed_term_id, $term_list)) !== false) {
			unset($term_list[$key]);
			$term_list = array_values($term_list);
		}
		//echo '<pre>'; print_r($term_list); echo '</pre>';
		return !empty($term_list) ? (int)$term_list[0] : 0; // return last category id.

	}
	return false;
}

/** 
 * Get questions according to product id.
 * And return array with right answers.
 */
function woo_get_product_cat_questionnaire($product_id = 0, $queryParm= null)
{

	if ($product_id) {

		$answers = array();
		$woo_cat_id = woo_get_product_cat_id($product_id);
		if(isset($queryParm) && !empty( $queryParm)){
			$woo_cat_id = $product_id;
		}
		

		if ($woo_cat_id && have_rows('questions', 'product_cat_' . $woo_cat_id)) {
			while (have_rows('questions', 'product_cat_' . $woo_cat_id)) {
				the_row();
				$question_type = get_sub_field('question_type');
				if($question_type == 'yesno'){
					$question_no = get_sub_field('question_no');
					$answers[$question_no] = get_sub_field('answer');
				}
			}
		}
		if (!empty($answers))
			return $answers;
	}
	return false;
}




function pre($data){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	echo '====================='; die;
	
}


add_filter( 'woocommerce_loop_add_to_cart_link', 'replace_default_button', 10, 2);
function replace_default_button($button, $product){
	global $product;		
	$woo_cat_id = woo_get_product_cat_id($product->get_id());
	$question_details = '';
	if(isset($_SESSION['question_details']) && !empty($_SESSION['question_details'])) {
		$question_details = $_SESSION['question_details'][$woo_cat_id];	
	}
	if ($woo_cat_id && have_rows('questions', 'product_cat_' . $woo_cat_id) && !$question_details && $question_details == '' ) {		
	
		return "<a class='button product_type_variable add_to_cart_button' href='". get_permalink( $product->get_id())."'>Learn More </a>";
	} 
	if(get_field('questionnaire_enable',$product->get_id()) == 1 ){
		
		return "<a class='button product_type_variable add_to_cart_button' href='". get_permalink( $product->get_id())."'>Learn More </a>";
		
	}
	return $button;
}


add_action('woocommerce_add_to_cart','QuadLayers_callback_function');
function QuadLayers_callback_function(){
    # add your code here
	global $woocommerce;

			$cart_items = $woocommerce->cart->cart_contents; 

	
			
		 	 foreach ($cart_items as $cart_item_key => $cart_item) {
			
				//pre(getSubmittedQuestionDetails($cart_item['product_id']));
				if(getSubmittedQuestionDetails($cart_item['product_id'])){
					
					$SubmittedData = getQuestionDataFromDb($cart_item['product_id']);
				
					 $woocommerce->cart->cart_contents[$cart_item_key]['prescription_items'] = $SubmittedData['main_patient_data'];
					$woocommerce->cart->cart_contents[$cart_item_key]['questionnaires_items'] = $SubmittedData['question_answer']; 
					$woocommerce->cart->cart_contents[$cart_item_key]['add_patient_data'] = $SubmittedData['patient_data'];
					
				}
			
				
			
		}
		
		
	 


		
		$woocommerce->cart->set_session(); 	
		//pre($woocommerce->cart->cart_contents);
}


function getSubmittedQuestionDetails($productId){
	global $woocommerce;
	$woo_cat_id = woo_get_product_cat_id($productId);
	$question_details = '';
	if(isset($_SESSION['question_details']) && !empty($_SESSION['question_details'])) {
		$question_details = $_SESSION['question_details'][$woo_cat_id];
	}
	
	if ($woo_cat_id && have_rows('questions', 'product_cat_' . $woo_cat_id) && $question_details && $question_details !== '' ) {
		
		return true;
	}
	return false;
	
}


function getQuestionDataFromDb($productId){
	global $wpdb;
	global $woocommerce;
	$question_details = '000';
	$woo_cat_id = woo_get_product_cat_id($productId);
	if(isset($_SESSION['question_details']) && !empty($_SESSION['question_details'])) {
		$question_details = $_SESSION['question_details'][$woo_cat_id]; 
	}
	$tableName  = $wpdb->prefix."questionnaire";
	
	$QuestionAnswerData = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$tableName." WHERE Id = '".$question_details."'" ), ARRAY_A );
	//echo $wpdb->last_query;
	return $QuestionAnswerData;
	//pre($QuestionAnswerData);
	
}



// define the woocommerce_hidden_order_itemmeta callback 
function custom_woocommerce_hidden_order_itemmeta( $array ){ 
   //custom code here
  //pre($array);
 array_push($array,"questionnaires_items","prescription_items","document_required");
  // echo '<pre>'; print_r($array); echo '</pre>';
    return $array;
} 

//add the action 
add_filter('woocommerce_hidden_order_itemmeta', 'custom_woocommerce_hidden_order_itemmeta', 10, 1);


//remove order item meta key
add_filter( 'woocommerce_order_item_get_formatted_meta_data', 'remove_order_meta_from_frontend', 10, 1 );

function remove_order_meta_from_frontend($formatted_meta){
    $temp_metas = [];
	
	//echo '<pre>'; print_r($formatted_meta); echo '</pre>'; die;
    foreach($formatted_meta as $key => $meta) {
        if ( isset( $meta->key ) && ! in_array( $meta->key, [
                'questionnaires_items',
                'prescription_items'
            ] ) ) {
            $temp_metas[ $key ] = $meta;
        }
    }
    return $temp_metas;
}



/**
 * Add a custom field (in an order) to the emails
 */
add_filter('woocommerce_email_order_meta_fields', 'custom_woocommerce_email_order_meta_fields', 10, 3);
function custom_woocommerce_email_order_meta_fields($fields, $sent_to_admin, $order)
{	global $woocommerce;
    $question_answer_data = array();
	$order_id = method_exists($order, 'get_id') ? $order->get_id() : $order->id;
	$prescription_items .= '';
	$patient_prescription_items .= '';
	$uniqueOrderItemId = findUniqeOrderItemCat($order_id);	
		foreach ($uniqueOrderItemId as $item_id) {
	
		// Here you get your data
		$custom_field = wc_get_order_item_meta($item_id['item_id'], 'prescription_items', true);
		$custom_field = unserialize($custom_field);
		
		if(isset($custom_field) && !empty($custom_field)){	
			$prescription_items .= '<h4>User Details</h4>';
			foreach ($custom_field as $key => $value) {
				if ($key == 'first-name') {
					$prescription_items .= 'First Name: ' .$value . "<br>";
				}
				if ($key == 'last-name') {
					$prescription_items .= 'Last Name: ' .$value . "<br>";
				}
				if ($key == 'your-phone') {
					$prescription_items .= 'Phone: ' .$value . "<br>";
				}				
				if ($key == 'dob') {
					$prescription_items .= 'Date of birth: ' .$value . "<br>";
				}
				if ($key == 'address') {
					$prescription_items .= 'Address: ' .$value . "<br>";
				}
				if ($key == 'postcode') {
					$prescription_items .= 'Postcode: ' .$value . "<br><br><br>";
				}
			}
		}	
	
		
		
		if($prescription_items != '') {
			$headingTitle = '<div class="" style="text-align: center;padding: 10px;font-size: 15px; font-weight: bold;">Medical Assesment</div>';
		} else {			
			$headingTitle = '<div class="" style="text-align: center;padding: 10px;font-size: 15px; font-weight: bold;">Chatbot Questionnaire</div>';				
		}
		
		$custom_field_question = wc_get_order_item_meta($item_id['item_id'], 'questionnaires_items', true);
		
		
		$custom_field_question = unserialize($custom_field_question); 
		
		$questionStringSet =  serialize(array());
		//$custom_field_question = unserialize($custom_field_question);
		if(isset($custom_field_question) && !empty($custom_field_question)){
			if(isset($custom_field_question['question_answer']) && !empty($custom_field_question['question_answer'])){			
				$questionStringSet = $custom_field_question['question_answer'];
			}
		}
		
	/* 	$fixed_data = preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {      
			return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
		},$questionStringSet ); */
		$question_answer_data = unserialize($questionStringSet); 	
		//echo '<pre>'; print_r(count($question_answer_data)); echo '</pre>';
		$lengthQuestion  = count($question_answer_data);
		 if($lengthQuestion < 8 ){			
			$headingTitle = '<div class="" style="text-align: center;padding: 10px;font-size: 15px; font-weight: bold;">Chatbot Questionnaire</div>';
		} else { 			
			$headingTitle = '<div class="" style="text-align: center;padding: 10px;font-size: 15px; font-weight: bold;">Medical Assesment</div>'; 			
		}	
		if(isset($question_answer_data) && !empty($question_answer_data)){		
		
			$html_question .= $headingTitle."<table  style='width:100%; border-collapse: collapse;' class='display table_style'><thead><tr><th style=' padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #4DC42A; color: white;'>Q No.</th><th style=' padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #4DC42A; color: white;'>Question</th> <th style=' padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #4DC42A; color: white;'>Answer</th><th style=' padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #4DC42A; color: white;'>Reason</th></tr>
			</thead><tbody>";
			
			foreach($question_answer_data as $question_answer){
				$freeAnswer = '-';
				$classError = '';
				$styleErr = '';
				if($question_answer['freeAnswer'] != ''){
					$freeAnswer = $question_answer['freeAnswer'];
					$classError = 'ErrRed';
					$styleErr = 'background:#8e1627; color: #fff;';
				}					
				$html_question .= '<tr>
				<td style="border: 1px solid #ddd; padding: 8px;">'.$question_answer['questionno'].'</td>
				<td style="border: 1px solid #ddd; padding: 8px;">'.$question_answer['question'].'</td>
				<td style="border: 1px solid #ddd; padding: 8px;">'.$question_answer['answer'].'</td>
				<td style="border: 1px solid #ddd; padding: 8px; '.$styleErr.'">'.$freeAnswer.'</td></tr>';	
			}
			
			$html_question .= "</tbody><tfoot><tr><th style=' padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #4DC42A; color: white;'>Q No.</th><th style=' padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #4DC42A; color: white;'>Question</th> <th style=' padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #4DC42A; color: white;'>Answer</th><th style=' padding-top: 12px; padding-bottom: 12px; text-align: center; background-color: #4DC42A; color: white;'>Reason</th></tr>
			</tfoot></table><br/>";
		}			
		$patient_data = array();
		if(isset($custom_field_question['patient_data']) && !empty($custom_field_question['patient_data'])) {
			//$patient_data  = unserialize($custom_field_question['patient_data']);
			$patient_data  = unserialize($custom_field_question['patient_data']);
		}
		//$patient_data  = unserialize($custom_field_question['patient_data']);
		if(isset($patient_data) && !empty($patient_data)) {
			$patient_prescription_items .= '<h4>Patient Details</h4><br>';
			
			$patient_prescription_items .= 'Patient First Name: ' . $patient_data['patient_first_name'] . "<br>";
			$patient_prescription_items .= 'Patient Lirst Name: ' . $patient_data['patient_last_name'] . "<br>";
			$patient_prescription_items .= 'Patient DOB: ' . $patient_data['patient_dob'] . "<br>";
			$patient_prescription_items .= 'Patient Phone: ' . $patient_data['patient_phone'] . "<br>";
			$patient_prescription_items .= 'Patient Door Number: ' . $patient_data['patient_door_number'] . "<br>";
			$patient_prescription_items .= 'Patient Address: ' . $patient_data['patient_address'] . "<br>";
			$patient_prescription_items .= 'Patient postcode: ' . $patient_data['patient_postcode'] . "<br><br><br><br>";
			
		}
		
	}
	$allDetails = $prescription_items.'<br/><br/>'.$patient_prescription_items.'<br/><br/>'.$html_question;	

	if($prescription_items != '' || $patient_prescription_items != '' || $html_question!= '' ){	
		
		$fields['card_message'] = array(
			'label' => __('Prescription Details'),
			'value' => $allDetails,
		); 
	}	
	return $fields;
}

add_action( 'add_meta_boxes', 'hcf_register_meta_boxes' );
function hcf_register_meta_boxes() {
		$i=0;
	$order_id = $_GET['post']; 
	$order = wc_get_order($order_id);
	if(isset($order) && !empty($order)) {
	$items = $order->get_items();
	//echo '<pre>'; print_r($items); echo '</pre>';
	
		foreach ($order->get_items() as $item_id => $item) {
		//echo '<pre>'; print_r($item_id); echo '</pre>';
		$custom_field_question = wc_get_order_item_meta($item_id, 'questionnaires_items', true);
     //  echo '<pre>'; print_r($custom_field_question); echo '</pre>';
			if($custom_field_question){
				$i++;	
			}
		}
	}

	
	if($i>0){
	add_meta_box( 'hcf-1', __( 'Questionnaire sections', 'hcf' ), 'hcf_display_callback', 'shop_order' );
	}
}

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function hcf_display_callback( $post ) {
  	
	$order_id = $_GET['post']; 
/* 	$order = wc_get_order($order_id);
	$items = $order->get_items(); */
	$uniqueOrderItemId = findUniqeOrderItemCat($order_id);	
	echo '<style> 
	.table_style {
	font-family: Arial, Helvetica, sans-serif;
	border-collapse: collapse;
	width: 100%;
	}

	.table_style td, #example th {
	border: 1px solid #ddd;
	padding: 8px;
	}

	.table_style tr:nth-child(even){background-color: #f2f2f2;}

	.table_style tr:hover {background-color: #ddd;}

	.table_style th {
	padding-top: 12px;
	padding-bottom: 12px;
	text-align: left;
	background-color: #4DC42A;
	color: white;
	}
	</style>';


	foreach ($uniqueOrderItemId as $item_id) {
			
		$custom_field_question = wc_get_order_item_meta($item_id['item_id'], 'questionnaires_items', true);	
		//echo '<pre>'; print_r($custom_field_question); echo '</pre>'; 
		//print_r($custom_field_question);
		$question_answer_data = array();
		if(is_array($custom_field_question)){
			$custom_field_question = $custom_field_question;
		} else {			
			$custom_field_question = unserialize($custom_field_question);
		}
		
		//$custom_field_question = unserialize($custom_field_question); 
		if(isset($custom_field_question['question_answer']) && !empty($custom_field_question['question_answer'])) 
		{
			$fixed_data = preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {      
			return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
			},$custom_field_question['question_answer'] );
			$question_answer_data = unserialize($fixed_data);			
		}
	//	echo '<pre>'; print_r($question_answer_data); echo '</pre>';
				
		if (array_key_exists("freeAnswer",$question_answer_data[0])) {		
			$headingTitle = '<div class="" style="text-align: center;padding: 10px;font-size: 15px; font-weight: bold;">Medical Assesment</div>'; 
		} else {				
					
			$headingTitle = '<div class="" style="text-align: center;padding: 10px;font-size: 15px; font-weight: bold;">Chatbot Questionnaire</div>';	
		}

		if(isset($question_answer_data) && !empty($question_answer_data)) {
			$html_question .= $headingTitle."<table  style='width:100%' class='display table_style'><thead><tr><th>Q No.</th><th>Question</th> <th>Answer</th><th>Reason</th></tr>
			</thead><tbody>";
			foreach($question_answer_data as $question_answer){
				$freeAnswer = '-';
				$classError = '';
				$styleErr = '';
				if($question_answer['freeAnswer'] != ''){
				$freeAnswer = $question_answer['freeAnswer'];
				$classError = 'ErrRed';
				$styleErr = 'style="background:#8e1627; color:#fff;"';
				}

				$html_question .= '<tr class="'.$classError.'" '.$styleErr.' >
				<td>'.$question_answer['questionno'].'</td>
				<td>'.$question_answer['question'].'</td>
				<td>'.$question_answer['answer'].'</td>
				<td>'.$freeAnswer.'</td></tr>';
			} 
			$html_question .= '</tbody>
			<tfoot>
			<tr>
			<th>Q No.</th>
			<th>Question</th>
			<th>Answer</th>
			<th>Reason</th>
			</tr>
			</tfoot></table><br/>
			'; 
		} 	
	}	
   
	echo $html_question; 
 	
	
}



/**
 * Display field value on the order edit page
 */
add_action('woocommerce_admin_order_data_after_billing_address','my_custom_checkout_field_display_admin_order_meta', 10, 1);
function my_custom_checkout_field_display_admin_order_meta($order)
{

	$prescription_items = '';
	$order_id = method_exists($order, 'get_id') ? $order->get_id() : $order->id;	$uniqueOrderItemId = findUniqeOrderItemCat($order_id);	
	foreach ($uniqueOrderItemId as $item_id) {

		// Here you get your data
		$custom_field = wc_get_order_item_meta($item_id['item_id'], 'prescription_items', true);
		$questionnaires_items = wc_get_order_item_meta($item_id['item_id'], 'questionnaires_items', true);
		if(is_array($questionnaires_items)){
			$questionnaires_items = $questionnaires_items;
		} else {
			$questionnaires_items = unserialize($questionnaires_items);
		}
		//echo '<pre>'; print_r($questionnaires_items); echo '</pre>';
		// To test data output (uncomment the line below)
		$patient_data = array();
		if($questionnaires_items['patient_data'] && isset($questionnaires_items['patient_data']) && !empty($questionnaires_items['patient_data'])) {
			$patient_data  = unserialize($questionnaires_items['patient_data']);
			//$patient_data  = $questionnaires_items['patient_data'];
		}
		$prescription_items = '';
		
		$patient_data_string = '';
		//echo '<pre>'; print_r($patient_data); echo '</pre>';
		if(isset($patient_data) && !empty($patient_data)){
			
			$patient_data_string .= 'Patient First Name: ' . $patient_data['patient_first_name'] . "<br>";
			$patient_data_string .= 'Patient Lirst Name: ' . $patient_data['patient_last_name'] . "<br>";
			$patient_data_string .= 'Patient DOB: ' . $patient_data['patient_dob'] . "<br>";
			$patient_data_string .= 'Patient Phone: ' . $patient_data['patient_phone'] . "<br>";
			$patient_data_string .= 'Patient Door Number: ' . $patient_data['patient_door_number'] . "<br>";
			$patient_data_string .= 'Patient Address: ' . $patient_data['patient_address'] . "<br>";
			$patient_data_string .= 'Patient postcode: ' . $patient_data['patient_postcode'] . "<br>";
			
		}
		
			$custom_field = unserialize($custom_field);
		
		// If it is an array of values
		if (is_array($custom_field)) {
			foreach ($custom_field as $key => $value) {
				if ($key == 'first-name') {
					$prescription_items .= 'First Name: ' . $value . "<br>";
				}
				if ($key == 'last-name') {
					$prescription_items .= 'Last Name: ' . $value . "<br>";
				}
				if ($key == 'your-phone') {
					$prescription_items .= 'Phone: ' . $value . "<br>";
				}				
				if ($key == 'dob') {
					$prescription_items .= 'Date of birth: ' . $value . "<br>";
				}
				if ($key == 'address') {
					$prescription_items .= 'Address: ' . $value . "<br>";
				}
				if ($key == 'postcode') {
					$prescription_items .= 'Postcode: ' . $value . "<br>";
				}
			}
			//$custom_field = implode( '<br>', $custom_field ); // one value displayed by line 
		}
		// just one value (a string)
		else {
			$prescription_items = $custom_field;
		}
		
		if(!empty($patient_data_string)){
			echo '<br/><p><strong>' . __('Prescription Patient Details') . ':</strong><br/> ' . $patient_data_string . '</p>';
		
	}
	if (!empty($prescription_items)) {
		echo '<br/><p><strong>' . __('Prescription Details') . ':</strong><br/> ' . $prescription_items . '</p>';
	}
	}
	
}



/** 
 * Update cart item questionnaire.
 */
function woo_update_cart_item_questionnaire($product_id = 0, $questionnaires = array(), $main_patient_data = array(), $term=null)
{	//session_start();
	global $woocommerce;
	global $wpdb;
	if ($product_id && !empty($questionnaires)) {	
		
		$cat_ids = array(woo_get_product_cat_id($product_id));
		//$cart_items = $woocommerce->cart->cart_contents;
		$woo_cat_id = woo_get_product_cat_id($product_id,$questionnaires);
		if($term == 'term_id'){
			$woo_cat_id = $product_id;
		}		
		
		//$woo_cat_id = woo_get_product_cat_id($product_id,$questionnaires);
		if ($woo_cat_id && have_rows('questions', 'product_cat_' . $woo_cat_id)) {
			$questionAnsArr = array();
			$dabarray = array();
		
			$htmlQuestion = '';
			while (have_rows('questions', 'product_cat_' . $woo_cat_id)) : the_row();
			
			$question_no = get_sub_field('question_no');
			$question = get_sub_field('question');			
			$dabarray['questionno'] = $question_no;
			$dabarray['question'] = $question;
			$dabarray['answer'] = $questionnaires['answer'][$question_no];
			$dabarray['freeAnswer'] = $questionnaires['free_text'][$question_no-1];	
				
			$questionAnsArr[] = $dabarray;
			endwhile;
			
		}
		//pre($questionAnsArr);
	
		$arrPatient =array();
		if($questionnaires['add_patient'] == 1 ){			
			
			$arrPatient = array('patient_first_name'=> $questionnaires['patient_first-name'] , 'patient_last_name'=> $questionnaires['patient_last-name'], 'patient_dob'=> $questionnaires['patient_dob'], 'patient_phone'=> $questionnaires['patient_your-phone'],'patient_door_number'=> $questionnaires['patient_door_number'],'patient_address'=> $questionnaires['patient_address'],'patient_postcode'=> $questionnaires['patient_postcode']);
			
		}

		$question_answer = serialize($questionAnsArr); 
		$patient_data = serialize($arrPatient);
		$cartFrm = serialize($main_patient_data);
		$questionnairesData =array();
		
		
	 	$questionnairesData['question_answer'] = $question_answer;
		$questionnairesData['patient_data'] = $patient_data;	
		
		$questionnairesDataSerial = serialize($questionnairesData);

		 $wpdb->insert( 
				'mo_questionnaire', 
				array( 
					'question_answer' => $questionnairesDataSerial,					
					'main_patient_data' => $cartFrm, 
					'patient_data' => $patient_data,
				), 
				array( 
					'%s', 
					'%s', 
					'%s', 					
					
				) 
			);
	
			$session_Id = $wpdb->insert_id;		
		
				$_SESSION["question_details"][$woo_cat_id]  = $session_Id;
			
			return true;
	}
	return false;
}

function woo_update_cart_item_questionnaire_bot($product_id = 0, $questionnaires = array())
{

	if ($product_id && !empty($questionnaires)) {

		global $woocommerce;
		$cart_items = $woocommerce->cart->cart_contents;	
		$question_answer = serialize($questionnaires); 
		$questionnairesData['question_answer'] = $question_answer;
		$questionnairesData = serialize($questionnairesData);
	//echo '<pre>'; print_r($cart_items); echo '</pre>';
		foreach ($cart_items as $cart_item_key => $cart_item) { 

			if ($cart_item['product_id'] == $product_id) {
			
				
				if (count($questionnaires) > 0) {
		
				$woocommerce->cart->cart_contents[$cart_item_key]['questionnaires_items'] = $questionnairesData;
				}
			}
		}
		//echo '<pre>'; print_r($woocommerce->cart->cart_contents); echo '</pre>'; die;
		// The modified object gets saved.
		$woocommerce->cart->set_session();
		
		return true;
	}

	return false;
}

function woo_update_cart_item_document($product_id, $documentUrl)
{
	global $woocommerce;
	
	
	if (!empty($product_id) && !empty($documentUrl)) {

		
		$cart_items = $woocommerce->cart->cart_contents;	

		foreach ($cart_items as $cart_item_key => $cart_item) { 
		
			if ($cart_item['product_id'] == $product_id) {
			
				
				
		
				$woocommerce->cart->cart_contents[$cart_item_key]['document_required'] = $documentUrl;
				$woocommerce->cart->cart_contents[$cart_item_key]['trilok'] = 'vxcvcxv cvxcv';
				
			}
		}

		$woocommerce->cart->set_session();

		return true;
	}

	return false;
	
}


//add_action('init','session_destroy_test');

function session_destroy_test(){
	
//session_destroy();
 //global $woocommerce;
//pre($woocommerce->cart->cart_contents);
 //echo  '<pre>'; print_r( $woocommerce->cart->cart_contents); echo '</pre>';
}


/**
 * Add custom metadata (Questionnaires) into order meta.
 */
if (!function_exists('woo_add_values_to_order_item_meta')) {
	function woo_add_values_to_order_item_meta($item_id, $values)
	{

		global $woocommerce, $wpdb;
		$questionnaires_items = isset($values['questionnaires_items']) ? $values['questionnaires_items'] : '';
		$prescription_items = isset($values['prescription_items']) ? $values['prescription_items'] : '';
		$document_required = isset($values['document_required']) ? $values['document_required'] : '';
		if (!empty($questionnaires_items)) {
			wc_add_order_item_meta($item_id, 'questionnaires_items', $questionnaires_items);
		}
		if (!empty($prescription_items)) {
			wc_add_order_item_meta($item_id, 'prescription_items', $prescription_items);
		}
		
		if (!empty($document_required)) {
			wc_add_order_item_meta($item_id, 'document_required', $document_required);
		}
		
		
	}
}
add_action('woocommerce_add_order_item_meta', 'woo_add_values_to_order_item_meta', 1, 2);


function woo_redirect_on_add_to_cart()
{
	
	if (isset($_POST['add-to-cart'])) {
		$product_id = $_POST['add-to-cart'];
	} else if (isset($_GET['add-to-cart'])) {
		$product_id = $_GET['add-to-cart'];
	} else {
		$product_id = 0;
	}
	//print_r(get_field('document_required',$product_id)); die;
	if ($product_id) {
	
		if (get_field('questionnaire_enable',$product_id) == 1 ){
			
			return site_url('/consultation/?id=' . $product_id);
		}
		
		/* if (get_field('document_required',$product_id)  == 1 ){
			
			return site_url('/document-required/?id=' . $product_id);
		} */
	}
}
add_filter('woocommerce_add_to_cart_redirect', 'woo_redirect_on_add_to_cart');



function findUniqeOrderItemCat($order_id){	
		global $woocommerce, $post;
		global $wpdb;
		$order = wc_get_order($order_id);
		$makeArr = array();
		$customArr =array();
		foreach ($order->get_items() as $item_id => $item) {
			
			$product_id = $item->get_product_id(); 
			$termId =  woo_get_product_cat_id($product_id);
			if (($termId && have_rows('questions', 'product_cat_' . $termId)) || get_field('questionnaire_enable',$product_id) == 1 ){		
				$customArr['item_id'] = $item_id;
				$customArr['term_id'] = $termId;
				$makeArr[] = $customArr;
			}
		}

		//echo '<pre>'; print_r($makeArr); echo '</pre>';

		$uniqueItemIds = uniquAsoc($makeArr,'term_id');	
		//echo '<pre>'; print_r($uniqueItemIds); echo '</pre>';	
		return $uniqueItemIds;

		
}


 function uniquAsoc($array,$key){
        $resArray=[];
        foreach($array as $val){
          if(empty($resArray)){
            array_push($resArray,$val);
          }else{
            $value=array_column($resArray,$key);
            if(!in_array($val[$key],$value)){
                array_push($resArray,$val);
              }
          }          
        }
        return $resArray;
    }




add_filter( 'woocommerce_product_tabs', 'remove_tabs_on_single_product_page', 11 );
 
function remove_tabs_on_single_product_page( $tabs ) {
  unset( $tabs['seller'] );
  return $tabs;
}



/**
 * Define the woocommerce_before_checkout_form callback 
 */
 function woo_action_woocommerce_before_checkout_form($wccm_autocreate_account)
{	

	// Check csart is empty or not.
	if (!WC()->cart->is_empty()) {

		woo_questionnaire_validation_check_and_redirect();
	}
} 

add_action('woocommerce_before_checkout_form', 'woo_action_woocommerce_before_checkout_form', 10, 1);

/**
 * Check the questionnaire validation and redirect to questionnaire/consultation page.
 */
 function woo_questionnaire_validation_check_and_redirect()
{

	$url = woo_questionnaire_validate_checkout(); 
	if ($url) {
		wp_safe_redirect($url);
		exit();
	}
}

/**
 * Check the questionnaire validate with cart data.
 */
function woo_questionnaire_validate_checkout() 
{
	//echo "Trilok"; die;
	global $woocommerce;

	// Check csart is empty or not.
	if (!WC()->cart->is_empty()) {		

		// Check questionnaire.
		$cart_items = $woocommerce->cart->cart_contents;

		foreach ($cart_items as $cart_item_key => $cart_item) {		
		

			// Check qestionnaires_item is empty or not
			if(get_field('questionnaire_enable',$cart_item['product_id'])){
				if (!isset($cart_item['questionnaires_items']) || empty($cart_item['questionnaires_items'])) {
					
						return site_url('/consultation/?id=' . $cart_item['product_id']);
					
				}
			}
			
			
				// Check qestionnaires_item is empty or not
			if(get_field('document_required',$cart_item['product_id'])){
				if (!isset($cart_item['document_required']) || empty($cart_item['document_required'])) {
					
						return site_url('/document-required/?id=' . $cart_item['product_id']);
					
				}
			}
		
		}
	}
	return false;
}


add_action( 'woocommerce_before_add_to_cart_button', 'add_content_after_addtocart_button_func' );
/*
 * Content below "Add to cart" Button.
 */
function add_content_after_addtocart_button_func() {
	global $wpdb;
	$product_id = get_the_id();
	
	if (get_field('document_required',$product_id)  == 1 ){
        		$file_upload_template =
					'<div class="wau_wrapper_div" style="margin: 50px 0px; padding: 21px 21px; border: 1px solid #eee;">
						<label class="wau_file_addon" for="wau_file_addon">Proof of identity required to purchase this product. Please upload below.</label>
						<input type="file" name="wau_file_addon" id="wau_file_addon" accept="image/*" class="wau-auto-width wau-files" />
					</div>';
				echo $file_upload_template;
	}			

}


function dvpi_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = 0, $variations = null) {
	
	
	global $woocommerce;
	if (get_field('document_required',$product_id)  == 1 ){
		if($_FILES['wau_file_addon']['name'] == ''){
		
		   $error_message = 'Proof of identity is required to purchase this product so please upload';
				// add your notice
				wc_add_notice( $error_message, 'error' );
				$passed = false;
		} else {	
			$passed = true;
		}
	}
	
    return $passed; 
}
add_filter( 'woocommerce_add_to_cart_validation', 'dvpi_add_to_cart_validation', 10, 5 );



add_action('woocommerce_add_to_cart', 'custom_add_to_cart');
function custom_add_to_cart() {
	global $wpdb;
	global $woocommerce;
	if(isset($_POST['add-to-cart']) && !empty($_POST['add-to-cart'])) {
		$product_id = $_POST['add-to-cart'];  
		if (get_field('document_required',$product_id)  == 1 ){	
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			if(isset($_FILES['wau_file_addon']) && !empty($_FILES['wau_file_addon'])){ 
				$uploadedfile = $_FILES['wau_file_addon'];
				$movefile = wp_handle_upload( $uploadedfile,array( 'test_form' => false ));			
				if ( $movefile && !isset( $movefile['error'] ) ) {			
					 $documentUrl = $movefile['url'];
					$output = woo_update_cart_item_document($product_id,$documentUrl);
				} else {
					$movefile['error'];
				}

			}
		}
	}
}


// Add header
function action_woocommerce_admin_order_item_headers( $order ) {
    // Set the column name
    $column_name = __( 'Required Document', 'woocommerce' );
    
    // Display the column name
    echo '<th class="my-class">' . $column_name . '</th>';
}
add_action( 'woocommerce_admin_order_item_headers', 'action_woocommerce_admin_order_item_headers', 10, 1 );

//Add content
function action_woocommerce_admin_order_item_values( $product, $item, $item_id ) {
    // Only for "line_item" items type, to avoid errors
   // if ( ! $item->is_type('line_item') ) return;

    // Get value
  //  $value = $item->get_meta( 'prefix-tempiconsegna' );
    
    // NOT empty
	//echo $item_id;    
		$custom_field = wc_get_order_item_meta($item_id, 'document_required', true);
		echo '<td class="document_required_img">
		<a   style ="display: block;" href="'.$custom_field.'">
		<img  style ="width: 100px;" class="document_required_img_src" src="'.$custom_field .'"/>
		</a>
			<a style ="display: block;" href="'.$custom_field.'">Download</a>
		</td>';
	

   
}
add_action( 'woocommerce_admin_order_item_values', 'action_woocommerce_admin_order_item_values', 10, 3 );