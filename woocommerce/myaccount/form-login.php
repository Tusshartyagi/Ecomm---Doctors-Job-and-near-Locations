<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="u-columns col2-set" id="customer_login">

    <div class="u-column1 login-form-col col-1">

		<?php endif; ?>

        <form class="woocommerce-form woocommerce-form-login login" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>
            <div class="woocommerce-form-login-wrap">
                <h2 class="login-form-title"><?php esc_html_e( 'Login', 'matico' ); ?></h2>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="username"><?php esc_html_e( 'Username or email address', 'matico' ); ?>
                        &nbsp;<span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" placeholder="<?php esc_html_e( 'Enter your username or email address...', 'matico' ); ?>"/><?php // @codingStandardsIgnoreLine ?>
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="password"><?php esc_html_e( 'Password', 'matico' ); ?>
                        &nbsp;<span class="required">*</span></label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="<?php esc_html_e( 'Enter your password...', 'matico' ); ?>"/>
                </p>
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever"/>
					<span><?php esc_html_e( 'Remember me', 'matico' ); ?></span>
				</label>
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

				<p class="woocommerce-LostPassword lost_password">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'matico' ); ?></a>
				</p>
				<?php do_action( 'woocommerce_login_form' ); ?>
                <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'matico' ); ?>"><?php esc_html_e( 'Log in', 'matico' ); ?></button>
            </div>


			<?php do_action( 'woocommerce_login_form_end' ); ?>

        </form>

		<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

    </div>

    <div class="u-column2 col-2">


        <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

			<?php do_action( 'woocommerce_register_form_start' ); ?>
            <h2 class="register-from-title"><?php esc_html_e( 'Register', 'matico' ); ?></h2>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_username"><?php esc_html_e( 'Username', 'matico' ); ?>
                        &nbsp;<span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" placeholder="<?php esc_html_e( 'Enter your username...', 'matico' ); ?>"/><?php // @codingStandardsIgnoreLine ?>
                </p>

			<?php endif; ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_email"><?php esc_html_e( 'Email address', 'matico' ); ?>
                    &nbsp;<span class="required">*</span></label>
                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" placeholder="<?php esc_html_e( 'Enter your email...', 'matico' ); ?>"/><?php // @codingStandardsIgnoreLine ?>
            </p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_password"><?php esc_html_e( 'Password', 'matico' ); ?>
                        &nbsp;<span class="required">*</span></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="<?php esc_html_e( 'Enter your password...', 'matico' ); ?>"/>
                </p>

                

			<?php else : ?>

                <p><?php esc_html_e( 'A password will be sent to your email address.', 'matico' ); ?></p>

			<?php endif; ?>

			<?php do_action( 'woocommerce_register_form' ); ?>

            <p class="woocommerce-FormRow form-row">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'matico' ); ?>"><?php esc_html_e( 'Register', 'matico' ); ?></button>
            </p>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

        </form>

    </div>

</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>


<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#reg_post_code').on('keyup', function() {
        var postcode = $(this).val();

        if (postcode) {
            // Make a request to Google Maps Geocoding API
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'address': postcode }, function(results, status) {
                if (status == 'OK') {
                    var location = results[0].geometry.location;
                    var latitude = location.lat();
                    var longitude = location.lng();

                    console.log('Latitude: ' + latitude + ', Longitude: ' + longitude);

                     jQuery("#reg_post_code_latitude").val(latitude);
                     jQuery("#reg_post_code_longitude").val(longitude);   
                    


                    // Do something with the latitude and longitude values
                    // For example, display them on the page or send them to the server via AJAX
                } else {
                    console.error('Geocode was not successful for the following reason: ' + status);
                }
            });
        } else {
            console.error('Please enter a postcode.');
        }
    });
});


</script>


