<?php

if(!function_exists('matico_dokan_sold_store')){
    function matico_dokan_sold_store(){
        global $product;
        $vendor       = dokan_get_vendor_by_product( $product );
        if(!$vendor->id){
            return;
        }
        $store_info   = $vendor->get_shop_info();
        ?>
        <div class="sold-by-meta">
            <span class="sold-by-label"><?php esc_html_e( 'Sold By:', 'matico' ); ?> </span>
            <a href="<?php echo esc_attr( $vendor->get_shop_url() ); ?>"><?php echo esc_html( $store_info['store_name'] ); ?></a>
        </div>
        <?php
    }
}

if (!function_exists('matico_add_vendor_info_on_product_single_page')) {
    function matico_add_vendor_info_on_product_single_page() {
        global $product;
        $vendor       = dokan_get_vendor_by_product( $product );
		//echo '<pre>'; print_r( $vendor ); echo '</pre>';
        if(!$vendor->id){
            return;
        }
        $store_info   = $vendor->get_shop_info();
		//echo '<pre>'; print_r( $store_info); echo '</pre>';
		$GPhCnumber = get_user_meta($vendor->id,'dokan_company_id_number',true);
		 $seller = get_user_by( 'id', $vendor->id ); 
        $store_rating = $vendor->get_rating();
        $show_vendor_info = dokan_get_option( 'show_vendor_info', 'dokan_appearance', 'off' );
		 $superintendent_name  = get_user_meta( $vendor->id , 'dokan_custom_superintendent_name', true );
		
        if ( 'on' === $show_vendor_info ) {
            ?>
            <div class="dokan-vendor-info-wrap">
                <div class="dokan-vendor-image">
                    <img src="<?php echo esc_url( $vendor->get_avatar() ); ?>" alt="<?php echo esc_attr( $store_info['store_name'] ); ?>">
                </div>
                <div class="dokan-vendor-info">
                    <div class="dokan-vendor-name">
                    <?php   /*   <h5>Name of superintendent pharmacist : <?php echo esc_html( $store_info['store_name'] ); ?></h5>  */ ?>
                        <?php apply_filters( 'dokan_product_single_after_store_name', $vendor ); ?>
                    </div>
                    <div class="dokan-vendor-rating">
                        <?php echo wp_kses_post( dokan_generate_ratings( $store_rating['rating'], 5 ) ); ?>
                    </div>
					<div class="Vendor_Inprmation_custom">
						<ul class="list-unstyled" style="margin:0px;">
						
							<li class="seller-name">
								<Strong>Name of shop:</Strong>
								<span class="details">	
									<?php echo esc_html( $store_info['store_name'] ); ?>
								</span>
							</li>
							<?php /* <li class="seller-name">
								<Strong>Name of seller:</Strong>
								<span class="details">	
									<?php echo esc_html( $seller->display_name ); ?>
								</span>
							</li> */ ?>
							
							
							<?php if( $superintendent_name) {?>
							
							<li class="seller-name">
								<Strong>Name of superintendent pharmacist:</Strong>
								<span class="details">	
									<?php echo esc_html( $superintendent_name ); ?>
								</span>
							</li>
							
							<?php  } ?>
							<?php if($GPhCnumber){ ?>
							<li class="seller-name">
								<Strong>GPhC registraion number:</Strong>
								<span class="details">	
									<?php echo $GPhCnumber; ?>
								</span>
							</li>
							<?php  } ?>
							
						<?php if(isset($store_info['address']) && !empty($store_info['address'])) { ?>	
						<li class="store-address">
							<span><b>Address:</b></span>
							<span class="details">
								<?php echo esc_attr( $store_info['address']['street_1'].' '.$store_info['address']['street_2'] ); ?> <br> 
								<?php echo esc_attr( $store_info['address']['city'] ); ?><br> <?php echo esc_attr( $store_info['address']['zip'] ); ?><br> 
								<?php echo esc_attr( $store_info['address']['country'] ); ?> <br> <?php echo esc_attr( $store_info['address']['state'] ); ?>
							</span>
						</li>
						<?php } ?>
						</ul>
					</div>
					<br/>
					
                   <a class="dokan-button-vendor" href="<?php echo esc_attr( $vendor->get_shop_url() ); ?>">
                        <?php echo esc_html__('view seller details', 'matico')?>
                    </a>
                </div>
            </div>
            <?php
        }
        else{
            return;
        }
		
		
    }
}