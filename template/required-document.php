<?php 

/* Template Name: Document Template */


get_header();
//echo showProductOnlyForTradeuser(39103);


//updateTextnomyRep('asthma',455);    


// The Query
//offset
/* $args = array(
	'post_type' => 'product',
	'posts_per_page' =>100,
	'offset'=>2400 
	
);
$the_query = new WP_Query( $args );

// The Loop
if ( $the_query->have_posts() ) {
	echo '<ul>';
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		$slug = basename(get_permalink());
		
		echo updateproductbySlug($slug,get_the_id());
		echo '<li>' . $slug . '</li>';
		echo '<li>' . get_the_id() . '</li>';
	}
	echo '</ul>';
} else {
	
} */
/* Restore original Post Data 
wp_reset_postdata();*/
//isProductAlreadyPurchasedInOtherPharmacy('ab.rizal7805@gmail.com','30','1159359');
 ?>
 
 <!--NHS tool start--><iframe title="NHS.UK Find services widget" src="https://developer.api.nhs.uk/widgets/services/all/?uid=df3482e0-0b6f-11ee-bdcf-455398f76731" width="100%" height="400px" style="border: solid 1px #ccc; max-width: 500px;"></iframe><!--NHS tool end-->

	<div id="primary">
		<main id="main" class="site-main">	 	

			<div class="account-wrap">
				<div class="account-inner ">
					<div class="login-form-head">
					<span class="login-form-title">Docuemnt require for this product</span>
					
					</div>
				<form class="matico-login-form-ajax"  action=""  method="post" enctype="multipart/form-data">
				<p>
				<label>File Upload <span class="required">*</span></label>
				<input type="file" name="document_user" type="text" required="" placeholder="File upload"  accept="image/x-png,image/gif,image/jpeg">
				</p>
			
				<input type="submit" name="documet_submit" value="Upload">
				      </form>			
				</div>
			</div>
			
			
		
		

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//do_action( 'matico_sidebar' );
get_footer();
