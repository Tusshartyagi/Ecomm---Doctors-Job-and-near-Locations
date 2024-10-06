<?php
/**
 *  Dokan Dashboard Template
 *
 *  Dokan Main Dahsboard template for Fron-end
 *
 *  @since 2.4
 *
 *  @package dokan
 */
$author_id = get_current_user_id(); 
$args = array(  'numberposts' => -1 , 'post_type'=>'service', 'post_status' => 'Publish','author'=>$author_id);
$services_posts = get_posts( $args );
$userServisCount = count($services_posts);

 
 if(isset($_POST) && !empty($_POST)){
	 
	 if($_POST['service_add'] == 'Publish'){
		 
		
		if($userServisCount < 11) {
		 
		$post_title =  $_POST['service_title'];
		$post_content =  $_POST['post_content'];
		$post_status = 'Publish';
	
		$new_service = array(
				'post_type'=>'service', 
				'post_title' => $post_title,
				'post_content' => $post_content,
				'post_status' => $post_status,
				'post_author' => $author_id
			
			);
		$new_service_id = wp_insert_post($new_service);	 
		
		if($new_service_id) {
			echo '<h5 class="custom_success_message">Your service has been added</h5>';	
		}
		} else {
			echo '<h5 class="custom_error_message">You can not add more then 10 services </h5>';	
			
		}	
	 }
	 
	  if($_POST['service_update'] == 'Update'){
		$post_title =  $_POST['service_title'];
		$post_content =  $_POST['post_content'];		
		$post_status = 'Publish';
		$new_service = array(
				'post_type'=>'service', 
				'ID' => $_REQUEST['edit'],
				'post_content' => $post_content,
				'post_title' => $post_title,
				'post_status' => $post_status,
			
			
			);
			
		
        $result = wp_update_post($new_service, true);

        if (!is_wp_error($result)){
            echo '<h5 class="custom_success_message">Your service has been updated</h5>';	
        }	
		
	 }
	 
	
	 
	 
	
	 
	 
 }
  if($_REQUEST['delete'] != ''){
		 $post_id =  $_REQUEST['delete'];
	  	wp_delete_post( $post_id, $force_delete = false ); 
		$url  = site_url().'/dashboard/services';
		wp_safe_redirect( $url );
		exit;    	
		
	 }
 
 
?>
<div class="dokan-dashboard-wrap">
    <?php
        /**
         *  dokan_dashboard_content_before hook
         *
         *  @hooked get_dashboard_side_navigation
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_before' );
    ?>

    <div class="dokan-dashboard-content">

        <?php
            /**
             *  dokan_dashboard_content_before hook
             *
             *  @hooked show_seller_dashboard_notice
             *
             *  @since 2.4
             */
            do_action( 'dokan_help_content_inside_before' );
        ?>

	<div class="dokan-dashboard-content dokan-product-listing" style="width:100%;">
		<article class="dokan-product-listing-area">
			
			<div class="dokan-dashboard-product-listing-wrapper">
			<?php  if($_REQUEST['add_new'] == 1) { ?>
				<div class="addServiceWrapper">
					<form method="POST" class="dokan-form-container">
					
						<div class="dokan-form-group">
										<label>Service Name</label>
                                        <input class="dokan-form-control" name="service_title" id="service-title" type="text" placeholder="Service name.." value="" required>
                         </div>
						 
						 <div class="dokan-form-group">
										
                                   <div class="dokan-product-short-description">
                                <label for="post_excerpt" class="form-label"><?php esc_html_e( 'Service Description', 'dokan-lite' ); ?></label>
                                <?php
                                wp_editor(
                                    '',
                                    'post_content',
                                    apply_filters(
                                        'dokan_product_short_description',
                                        [
                                            'editor_height' => 500,
                                            'quicktags'     => false,
                                            'media_buttons' => false,
                                            'teeny'         => true,
                                            'editor_class'  => 'service_content',
                                        ]
                                    )
                                );
                                ?>
                            </div>

                         </div>
						
						<div class="dokan-form-group">
							<input type="submit" id="service_add" class="" name="service_add" value="Publish"/>
						</div>
						
					
					</form>
				
				
				</div>
				
				<?php  } elseif($_REQUEST['edit'] != ''){ 
					$service_id = $_REQUEST['edit'];
					$content_post = get_post($service_id);
					$content = $content_post->post_content;
				
				?>
					
					
							<div class="addServiceWrapper">
					<form method="POST" class="dokan-form-container">
					
						<div class="dokan-form-group">
										<label>Service Name</label>
                                        <input class="dokan-form-control" name="service_title" id="service-title" type="text" placeholder="Service name.." value="<?php echo get_the_title($_REQUEST['edit']); ?>" required>
                         </div>
						 
						 <div class="dokan-form-group">
										
                                   <div class="dokan-product-short-description">
                                <label for="post_excerpt" class="form-label"><?php esc_html_e( 'Service Description', 'dokan-lite' ); ?></label>
                                <?php
                                wp_editor(
                                    $content,
                                    'post_content',
                                    apply_filters(
                                        'dokan_product_short_description',
                                        [
                                            'editor_height' => 500,
                                            'quicktags'     => false,
                                            'media_buttons' => false,
                                            'teeny'         => true,
                                            'editor_class'  => 'service_content',
                                        ]
                                    )
                                );
                                ?>
                            </div>

                         </div>
						
						<div class="dokan-form-group">
							<input type="submit" id="service_update" class="" name="service_update" value="Update"/>
						</div>
						
					
					</form>
				
				
				</div>
					
					
					
					
				<?php } else { ?>
				
				
			
				<div class="listWrapper">
				
					<div class="customHeading">
					
					<h3>Service List</h3>
						</div>
						
						<?php if($userServisCount < 11) { ?>
					<div class="customButoon">
				<a href="<?php echo site_url(); ?>/dashboard/services/?add_new=1" class="">Add New Service</a>			
			</div>
						<?php  } ?>			
					<table class="dokan-table dokan-table-striped product-listing-table dokan-inline-editable-table" id="dokan-product-list-table">
						<thead>
							<tr>                                         
								<th>Id</th>
								<th>Name</th>
								<th>Date</th>
								<th>Short Description</th>        
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
							
								<?php 
								
									$author_id = get_current_user_id(); 
									// The Query
									$args = array(
										'post_type' => 'service',
										'author' => $author_id,
										'posts_per_page'         => '10',
										'post_status'            => array('publish')						
									);
									$the_query = new WP_Query( $args );
									// The Loop
									if ( $the_query->have_posts() ) {
								//wp_trim_words( get_the_content(), 55, $moreLink);
										while ( $the_query->have_posts() ) {
											$the_query->the_post();
											echo '<tr class="">';
											echo '<td>' . get_the_id() . '</td>';
											echo '<td>' . get_the_title() . '</td>';
											echo '<td>' . get_the_date() . '</td>';
											echo '<td>' . wp_trim_words(get_the_content(), 4, '...') . '</td>';
											echo '<td>
											<a class="dokan-label dokan-label-success" href="' .site_url().'/dashboard/services/?edit='.get_the_id().'">Edit</a>
									<a class="dokan-label dokan-label-success"  onClick="deleteItem('.get_the_id().')" href="javascript:void(0)">Delete</a>';
											echo '</tr>';
											
											
										}
								
									} else {
										echo "<tr><td> No Service Found</td><td></td><td></td><td></td><td></td></tr>";
										
									}
									/* Restore original Post Data */
									wp_reset_postdata();								
								?>
							
							</tr>
						</tbody>
					</table>
				</div>
				<?php  } ?>
			</div>
		</article>
	</div>

         <?php
            /**
             *  dokan_dashboard_content_inside_after hook
             *
             *  @since 2.4
             */
            do_action( 'dokan_dashboard_content_inside_after' );
        ?>


    </div><!-- .dokan-dashboard-content -->

    <?php
        /**
         *  dokan_dashboard_content_after hook
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_after' );
    ?>

</div><!-- .dokan-dashboard-wrap -->
<style>
.customHeading {
	   width: 50%;
	   float:left;
	
}
.customButoon {
padding: 10px 20px;
    border-radius: 9px;

    color: #fff;
    width: 18%;
    background: #4DC42A;
    text-align: center;
	float:right;
}
.customButoon a {
  color: #fff;
}

.custom_success_message{ 

    background: aliceblue;
    color: green;
    border: 1px solid;
    padding: 10px;
    text-align: center;
}

.custom_error_message{ 

    background: blanchedalmond;
    color: red;
    border: 1px solid;
    padding: 10px;
    text-align: center;
}
</style>
<script>
function deleteItem(obj){
	
	var urlString = 'https://allchemists.co.uk/dashboard/services/?delete=';
    var checkstr =  confirm('are you sure you want to delete this?');
    if(checkstr == true){ 
//	alert(obj);
	//var urlString = jQuery(this).attr('data-url');	
	window.location.replace(urlString+obj);
		
	
      // do your code
    }else{
    return false;
    }
  }
</script>