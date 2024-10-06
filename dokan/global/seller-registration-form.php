<?php
/**
 * Dokan Seller registration form
 *
 * @since 2.4
 */
?>
 
<div class="show_if_seller" style="<?php echo esc_attr( $role_style ); ?>">

    <div class="split-row form-row-wide">
	
		 <p class="form-row form-group">
            <label for="title"><?php _e( 'Title', 'dokan' ); ?> <span class="required">*</span></label>
          
			<select name="user_title" required="required"> 			
				<option value="Mr"  >Mr</option>
				<option value="Ms">Ms</option>
				<option value="Miss"  >Miss</option>
				<option value="Master">Master</option>
				<option value="Dr"  >Dr</option>
				<option value="Professor">Professor</option>
				<option value="Lord"  >Lord</option>
				<option value="Duke">Duke</option>
				<option value="Baron">Baron</option>
				<option value="Sir">Sir</option>
				<option value="Prince">Prince</option>
				<option value="Sultan">Sultan</option>
				<option value="Duchess">Duchess</option>
				<option value="Majest">Majest</option>
				<option value="Princess">Princess</option>159
			</select>
        </p>
        <p class="form-row form-group">
            <label for="first-name"><?php esc_html_e( 'First Name', 'dokan-lite' ); ?> <span class="required">*</span></label>
            <input type="text" class="input-text form-control" name="fname" id="first-name" value="<?php echo ! empty( $data['fname'] ) ? esc_attr( $data['fname'] ) : ''; ?>" required="required" />
        </p>

        <p class="form-row form-group">
            <label for="last-name"><?php esc_html_e( 'Last Name', 'dokan-lite' ); ?> <span class="required">*</span></label>
            <input type="text" class="input-text form-control" name="lname" id="last-name" value="<?php echo ! empty( $data['lname'] ) ? esc_attr( $data['lname'] ) : ''; ?>" required="required" />
        </p>
    </div>

    <p class="form-row form-group form-row-wide">
        <label for="company-name"><?php esc_html_e( 'Shop Name', 'dokan-lite' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text form-control" name="shopname" id="company-name" value="<?php echo ! empty( $data['shopname'] ) ? esc_attr( $data['shopname'] ) : ''; ?>" required="required" />
    </p>

    <p class="form-row form-group form-row-wide">
        <label for="seller-url" class="pull-left"><?php esc_html_e( 'Shop URL', 'dokan-lite' ); ?> <span class="required">*</span></label>
		<span>( This is auto filled from shop name )</span>
		
        <strong id="url-alart-mgs" class="pull-right"></strong>
		
        <input type="text" class="input-text form-control" name="shopurl" id="seller-url" value="<?php echo ! empty( $data['shopurl'] ) ? esc_attr( $data['shopurl'] ) : ''; ?>" required="required" />
        <small><?php echo esc_url( home_url() . '/' . dokan_get_option( 'custom_store_url', 'dokan_general', 'store' ) ); ?>/<strong id="url-alart"></strong></small>
    </p>
	
		<p class="form-row form-group form-row-wide">
		<input class="tc_check_box wholesale_check_box" type="checkbox" id="wholesale_check_box" name="wholesale_check_box">
		<label style="display: inline" for="">I am wholesaler/ Manufacturer </label>
		</p>
		<p class="form-row form-group form-row-wide hc_shops_outer">
		<input class="tc_check_box hc_shops" type="checkbox" id="hc_shops" name="hc_shops">
		<label style="display: inline" for="tc_agree">Healthcare shops 
		</label>
		</p>

    <?php
    /**
     * @since 3.2.8
     */
    do_action( 'dokan_seller_registration_after_shopurl_field', [] );
    ?>
	
		
		
	  <p class="form-row form-group form-row-wide custom_superintendent_name">
        <label for="shop-phone"><?php esc_html_e( 'Superintendent Pharmacist Name', 'dokan-lite' ); ?><span class="required">*</span></label>
        <input type="text" class="input-text form-control" name="superintendent_name" id="shop-superintendent_name" value="<?php echo ! empty( $data['superintendent_name'] ) ? esc_attr( $data['superintendent_name'] ) : ''; ?>" required="required" />
    </p>

    <p class="form-row form-group form-row-wide">
        <label for="shop-phone"><?php esc_html_e( 'Phone Number', 'dokan-lite' ); ?><span class="required">*</span></label>
        <input type="text" class="input-text form-control" name="phone" id="shop-phone" value="<?php echo ! empty( $data['phone'] ) ? esc_attr( $data['phone'] ) : ''; ?>" required="required" />
    </p>

    <?php
    $show_terms_condition = dokan_get_option( 'enable_tc_on_reg', 'dokan_general' );
    $terms_condition_url  = dokan_get_terms_condition_url();

    if ( 'on' === $show_terms_condition && $terms_condition_url ) {
        ?>
        <p class="form-row form-group form-row-wide">
            <input class="tc_check_box" type="checkbox" id="tc_agree" name="tc_agree" required="required">
            <label style="display: inline" for="tc_agree">
                <?php
                printf(
                /* translators: %1$s: opening anchor tag with link, %2$s: an ampersand %3$s: closing anchor tag */
                    __( 'I have read and agree to the %1$sTerms %2$s Conditions%3$s.', 'dokan-lite' ),
                    sprintf( '<a target="_blank" href="%s">', esc_url( $terms_condition_url ) ),
                    '&amp;',
                    '</a>'
                );
                ?>
            </label>
        </p>
        <?php
    }
    do_action( 'dokan_seller_registration_field_after' );
    ?>
</div>


<div class="show_if_jobers" style="<?php echo esc_attr( $role_style ); ?>">

<div class="split-row form-row-wide">

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="reg_post_code"><?php esc_html_e( 'Post Code', 'matico' ); ?>
            &nbsp;<span class="required">*</span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="reg_post_code" id="reg_post_code" autocomplete="reg_post_code" placeholder="<?php esc_html_e( 'Enter post code...', 'matico' ); ?>"/>

        <input type="hidden" name="reg_post_code_latitude" id="reg_post_code_latitude">
        <input type="hidden" name="reg_post_code_longitude" id="reg_post_code_longitude">
    </p>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="select_position"><?php esc_html_e( 'Select Position', 'matico' ); ?>
            &nbsp;<span class="required">*</span></label>
     
        <select name="select_position" id="select_position" class="woocommerce-Input woocommerce-Input--text input-text">
            <?php
            
            $terms = get_terms([
                'taxonomy'   => 'job_category',
                'hide_empty' => false, // Set to true if you want to exclude terms with no posts
            ]);

            // Check if terms were retrieved
            if ($terms && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
                }
            } 
                echo '<option value="other">other</option>';
            ?>

        </select>
    </p>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="jober_qualification"><?php esc_html_e( 'Qualification ', 'dokan-custom-codes' ); ?>
            &nbsp;<span class="required">*</span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="qualification" id="qualification"  placeholder="<?php esc_html_e( 'Enter your Qualification...', 'matico' ); ?>" />
    </p>

    <p class="form-row form-group form-row-wide">
        <label for="jober_city"><?php esc_html_e( 'City', 'dokan-custom-codes' ); ?><span class="required">*</span></label>
        <input type="text" class="input-text form-control" name="jober_city" id="jober_city" value="<?php if ( ! empty( $postdata['jober_city'] ) ) echo esc_attr($postdata['jober_city']); ?>" placeholder="<?php esc_html_e( 'Enter your city...', 'matico' ); ?>"/>
    </p>
    
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="reg_post_code"><?php esc_html_e( 'Professional Registration Number', 'matico' ); ?>
            &nbsp;<span class="required">*</span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="professional_pegistration_number" id="professional_pegistration_number" autocomplete="professional_pegistration_number" placeholder="<?php esc_html_e( 'Professional Registration Number...', 'matico' ); ?>"/>
    </p>

</div>

</div>

<?php do_action( 'dokan_reg_form_field' ); ?>

<p class="form-row form-group user-role user-role-job vendor-customer-registration">

	
    <label class="radio">
        <input type="radio" name="role" value="customer"<?php checked( $role, 'customer' ); ?>>
        <?php esc_html_e( 'I am a customer', 'dokan-lite' ); ?>
    </label>
    <br/>
    <label class="radio">
        <input type="radio" name="role" value="seller"<?php checked( $role, 'seller' ); ?>>
        <?php esc_html_e( 'I am a vendor', 'dokan-lite' ); ?>
    </label>
	<br/>
    <label class="radio">
        <input type="radio" name="role" value="jobers" data-gtm-form-interact-field-id="2" <?php checked( $role, 'jobers' ); ?>>
        <?php esc_html_e( 'Job seekers', 'dokan-lite' ); ?>
    </label>

    <?php do_action( 'dokan_registration_form_role', $role ); ?>

</p>
<script>

jQuery(document).ready(function($) {
  // Attach a change event handler to the radio buttons
  $('.user-role-job input[name="role"]').change(function() {
    // Get the selected value
    var selectedValue = $('input[name="role"]:checked').val();

    // Do something with the selected value
    console.log("Selected option: " + selectedValue);

    if ( selectedValue === 'jobers' ) {
        $( '.show_if_jobers' ).show();
        $( '#reg_post_code').prop('disabled', false);
        $( '#select_position').prop('disabled', false);
    } else {
        $( '.show_if_jobers' ).hide();
        $( '#reg_post_code').prop('disabled', true);
        $( '#select_position').prop('disabled', true);

    }
    // You can perform any other actions or logic based on the selected value here
  });
});


jQuery(document).ready(function($){ 



jQuery('.hc_shops').change(function(){
	
	    if (jQuery(this).is(':checked')) {
			jQuery("label[for='dokan-company-id-number']").hide();
			jQuery("#dokan-company-id-number").hide();
			jQuery(".custom_superintendent_name").hide();
			jQuery("#shop-superintendent_name").removeAttr("required");
			
		} else {
			
			jQuery("label[for='dokan-company-id-number']").show();
			jQuery("#dokan-company-id-number").show();
			jQuery(".custom_superintendent_name").show();
			jQuery("#shop-superintendent_name").attr("required","required");
		}
		
		
 });
 
 
 jQuery('.wholesale_check_box').change(function(){
	
	    if (jQuery(this).is(':checked')) {
			jQuery("label[for='dokan-company-id-number']").hide();
			jQuery("#dokan-company-id-number").hide();
			jQuery(".custom_superintendent_name").hide();
			jQuery(".hc_shops_outer").hide();
			jQuery("#shop-superintendent_name").removeAttr("required");
			
		} else {
			jQuery(".hc_shops").prop('checked', false); 
			jQuery("label[for='dokan-company-id-number']").show();
			jQuery("#dokan-company-id-number").show();
			jQuery(".custom_superintendent_name").show();
				jQuery(".hc_shops_outer").show();
			jQuery("#shop-superintendent_name").attr("required","required");
		}	
 });
});
</script>