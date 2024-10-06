<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
    <meta name="google-site-verification" content="g9woA4kcdatT6GY33jM_5HLUSvHnPPTj2RV6Ou7DCiQ" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<link rel="profile" href="//gmpg.org/xfn/11">
	<link rel="icon" type="image/x-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/favicon.ico">
	</script><script type="application/ld+json">{
                  "@context": "https://schema.org",
                  "@type": "WebSite",
                  "url": "https://allchemists.co.uk",
                  "name": "All Chemists",
                  "headline": "Buy Quality Pharmacy Products from All Chemists UK ",
                  "description": "All Chemists is the United Kingdom's leading online pharmacy store, offering a wide selection of quality pharmacy products at competitive prices. Shop now and join our community of vendors for the best deals on pharmacy products.",
                  "mainEntityOfPage": "https://allchemists.co.uk"
            }</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WQDKGQ8');</script>
<!-- End Google Tag Manager -->	 
	<?php
	/**
	 * Functions hooked in to wp_head action
	 *
	 * @see matico_pingback_header - 1
	 */
	wp_head();

	?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action('matico_before_site'); ?>
<?php 
if (is_page( 'consultation' ) ) { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo 
get_stylesheet_directory_uri();   ?>/assets/css/jquery.convform.css">

	<link rel="stylesheet" type="text/css" href="<?php echo 
get_stylesheet_directory_uri();   ?>/assets/css/demo.css">
<?php  } ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WQDKGQ8"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="page" class="hfeed site">
	<?php
	/**
	 * Functions hooked in to matico_before_header action
	 *
	 */
	do_action('matico_before_header');
    if (matico_is_elementor_activated() && function_exists('hfe_init') && hfe_header_enabled()) {
        do_action('hfe_header');
    } else {
        get_template_part('template-parts/header/header-1');
    }

	/**
	 * Functions hooked in to matico_before_content action
	 *
	 */
	do_action('matico_before_content');
	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

<?php
/**
 * Functions hooked in to matico_content_top action
 *
 * @see matico_shop_messages - 10 - woo
 *
 */
do_action('matico_content_top');

//updateproductbySlug('dioctyl-100-mg-capsules','38202');
/* 
$fields = array('billing_address_1' => '381 Church Lane, Kingsbury, London, NW9 8JB', 'billing_city' => 'London', 'billing_postcode'=>'NW9 8JB');


woo_user_with_same_address_has_bought_product_in_days($fields , 32779, 10); */
/* $order_id = 33942;
$order = new WC_Order( $order_id );
custom_woocommerce_email_order_meta_fields($fields =array();, $sent_to_admin = 'tkumawat39@gmail.com', $order); */