<?php

/**
 *  Dokan Dashboard Template
 *
 *  Dokan Main Dashboard template for Front-end
 *
 *  @since 2.4
 *
 *  @package dokan
 */
$author_id = get_current_user_id();
$args = array('numberposts' => -1, 'post_type' => 'job', 'post_status' => 'publish', 'author' => $author_id);
$job_posts = get_posts($args);
$userServisCount = count($job_posts);

if (isset($_POST) && !empty($_POST)) {
	// Handle job addition
	if (isset($_POST['job_add']) && $_POST['job_add'] === 'Publish') {
		if ($userServisCount < 10000) {
			$post_title = $_POST['job_title'];
			$post_content = $_POST['post_content'];
			$select_position_post = $_POST['select_position'];
			$reg_miles_km = $_POST['reg_miles_km'];
			$reg_post_code = $_POST['reg_post_code'];
			$lat = $_POST['reg_post_code_latitude'];
			$long = $_POST['reg_post_code_longitude'];
			$cost_per_hour = $_POST['cost_per_hour'];
			$professional_registration_number = $_POST['professional_registration_number'];
			$qualifications = $_POST['qualifications'];
			$post_status = $_POST['job_status'];

			$new_job = array(
				'post_type' => 'job',
				'post_title' => $post_title,
				'post_content' => $post_content,
				'post_status' => $post_status,
				'post_author' => $author_id
			);
			$new_job_id = wp_insert_post($new_job);
			$post_id = $new_job_id;

			update_post_meta($post_id, 'select_position', $select_position_post);
			update_post_meta($post_id, 'reg_post_code', $reg_post_code);
			update_post_meta($post_id, 'reg_miles_km', $reg_miles_km);
			update_post_meta($post_id, 'cost_per_hour', $cost_per_hour);
			update_post_meta($post_id, 'professional_registration_number', $professional_registration_number);
			update_post_meta($post_id, 'qualifications', $qualifications);

			$term_slugs = $_POST['select_position']; // Array of term slugs
			$taxonomy = 'job_category'; // Replace with your custom taxonomy if needed

			if ($term_slugs == 'other') {
				$term_ids = array();
				$term_name = $_POST['enter_other_position'];
				$taxonomy = 'job_category';
				$new_terms_id = wp_insert_term($term_name, $taxonomy);
				$term_ids[] = $new_terms_id['term_id'];

				if (isset($new_terms_id->error_data)) {
					$term_ids[] = $new_terms_id->error_data['term_exists'];
					wp_set_post_terms($post_id, $term_ids, $taxonomy);
				} else {
					wp_set_post_terms($post_id, $term_ids, $taxonomy);
				}
				update_post_meta($post_id, 'select_position', str_replace(' ', '-', $term_name));
			} else {
				$term_ids = array();
				$term_slugs = array($term_slugs);
				foreach ($term_slugs as $term_slug) {
					$term = get_term_by('slug', $term_slug, $taxonomy);
					if ($term) {
						$term_ids[] = $term->term_id;
					}
				}
				wp_set_post_terms($post_id, $term_ids, $taxonomy);
			}

			if ($new_job_id) {
				echo '<h5 class="custom_success_message">Your job has been added</h5>';

				global $wpdb;
				$table_name = $wpdb->prefix . 'near_job'; // Replace 'your_custom_table' with your actual table name

				$data_to_insert = array(
					'lat' => $lat,
					'lon' => $long,
					'post_id' => $new_job_id,
				);

				$wpdb->insert($table_name, $data_to_insert);

				// Notify users
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
					$select_position = get_user_meta($user_id, 'select_position', true);

					if (!empty($targetLatitude) && !empty($targetLongitude)) {
						if ($select_position == $select_position_post) {
							global $wpdb;
							$table_name = $wpdb->prefix . 'near_job';
							$sql = "SELECT ID, lat, lon, post_id, (6371 * acos(cos(radians($targetLatitude)) * cos(radians(lat)) * cos(radians(lon) - radians($targetLongitude)) + sin(radians($targetLatitude)) * sin(radians(lat)))) AS distance FROM $table_name WHERE post_id = $new_job_id ORDER BY distance";
							$query = $wpdb->prepare($sql);
							$results = $wpdb->get_results($query);

							if ($results) {
								foreach ($results as $result) {
									if ($result->distance < $reg_miles_km) {
										$post_content .= '<br><a href="https://allchemists.co.uk/my-account/chat/?current_user_id=' . $user_id . '&&re_user=' . get_current_user_id() . '">Chat Link</a>';
										$post_content .= '<div class="cost_per_hour"><label>Hourly Rate : </label>' . $cost_per_hour . '</div>';
										$to = $user_email;
										$subject = $post_title;
										$message = $post_content;
										$headers = array('Content-Type: text/html; charset=UTF-8');

										// Send the email
										$result = wp_mail($to, $subject, $message, $headers);

										if ($result) {
											// Email sent successfully
										} else {
											// Error sending email
										}
									}
								}
							}
						}
					}
				}
			}
		} else {
			echo '<h5 class="custom_error_message">You can not add more than 10 jobs</h5>';
		}
	}

	// Handle job update
	if (isset($_POST['job_update']) && $_POST['job_update'] === 'Update') {
		$post_title = $_POST['job_title'];
		$post_content = $_POST['post_content'];
		$post_status = $_POST['job_status'];
		$new_job = array(
			'post_type' => 'job',
			'ID' => $_REQUEST['edit'],
			'post_content' => $post_content,
			'post_title' => $post_title,
			'post_status' => $post_status,
		);

		$result = wp_update_post($new_job, true);

		$post_id = $_GET['edit'];
		$select_position_post = $_POST['select_position'];
		$reg_post_code = $_POST['reg_post_code'];
		$reg_miles_km = $_POST['reg_miles_km'];
		$cost_per_hour = $_POST['cost_per_hour'];
		$professional_registration_number = $_POST['professional_registration_number'];
		$qualifications = $_POST['qualifications'];

		update_post_meta($post_id, 'reg_miles_km', $reg_miles_km);
		update_post_meta($post_id, 'select_position', $select_position_post);
		update_post_meta($post_id, 'reg_post_code', $reg_post_code);
		update_post_meta($post_id, 'cost_per_hour', $cost_per_hour);
		update_post_meta($post_id, 'professional_registration_number', $professional_registration_number);
		update_post_meta($post_id, 'qualifications', $qualifications);

		$term_slugs = $_POST['select_position']; // Array of term slugs
		$taxonomy = 'job_category'; // Replace with your custom taxonomy if needed

		if ($term_slugs == 'other') {
			$term_ids = array();
			$term_name = $_POST['enter_other_position'];
			$taxonomy = 'job_category';
			$new_terms_id = wp_insert_term($term_name, $taxonomy);
			$term_ids[] = $new_terms_id['term_id'];
			if (isset($new_terms_id->error_data)) {
				$term_ids[] = $new_terms_id->error_data['term_exists'];
				wp_set_post_terms($post_id, $term_ids, $taxonomy);
			} else {
				wp_set_post_terms($post_id, $term_ids, $taxonomy);
			}
			update_post_meta($post_id, 'select_position', str_replace(' ', '-', $term_name));
		} else {
			$term_ids = array();
			$term_slugs = array($term_slugs);
			foreach ($term_slugs as $term_slug) {
				$term = get_term_by('slug', $term_slug, $taxonomy);
				if ($term) {
					$term_ids[] = $term->term_id;
				}
			}
			wp_set_post_terms($post_id, $term_ids, $taxonomy);
		}

		echo '<h5 class="custom_success_message">Your job has been updated</h5>';
	}

	// Handle job deletion
	if (isset($_POST['delete_job']) && $_POST['delete_job'] === 'Delete') {
		$post_id = $_POST['post_id'];
		if ($post_id) {
			wp_delete_post($post_id, true);
			global $wpdb;
			$table_name = $wpdb->prefix . 'near_job';
			$wpdb->delete($table_name, array('post_id' => $post_id));
			echo '<h5 class="custom_success_message">Your job has been deleted</h5>';
		} else {
			echo '<h5 class="custom_error_message">Job ID is missing</h5>';
		}
	}
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
	do_action('dokan_dashboard_content_before');
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
		do_action('dokan_help_content_inside_before');
		?>

		<div class="dokan-dashboard-content dokan-product-listing" style="width:100%;">
			<article class="dokan-product-listing-area">

				<div class="dokan-dashboard-product-listing-wrapper">
					<?php if ($_REQUEST['add_new'] == 1) { ?>
						<div class="addjobWrapper">
							<form method="POST" class="dokan-form-container">

								<div class="dokan-form-group">
									<label>job Name</label>
									<input class="dokan-form-control" name="job_title" id="job-title" type="text" placeholder="job name.." value="" required>
								</div>

								<div class="dokan-form-group">

									<div class="dokan-product-short-description">
										<label for="post_excerpt" class="form-label"><?php esc_html_e('job Description', 'dokan-lite'); ?></label>
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
													'editor_class'  => 'job_content',
												]
											)
										);
										?>
									</div>

								</div>

								<div class="dokan-form-group">
									<label>Select Position</label>
									<select name="select_position" id="select_position" class="dokan-form-control">
										<?php

										$terms = get_terms([
											'taxonomy'   => 'job_category',
											'hide_empty' => false, // Set to true if you want to exclude terms with no posts
										]);

										// Check if terms were retrieved
										if ($terms && !is_wp_error($terms)) {
											foreach ($terms as $term) {
												echo '<option value="' . $term->slug . '">' . $term->name . '</option>';
											}
										}
										echo '<option value="other">other</option>';
										?>
									</select>

									<input class="dokan-form-control" style="display: none;" disabled name="enter_other_position" id="enter_other_position" type="text" placeholder="Enter Other Position" value="" autocomplete="off" required>
								</div>

								<div class="dokan-form-group">
									<label>Enter post code...</label>
									<input class="dokan-form-control" name="reg_post_code" id="reg_post_code" type="text" placeholder="Enter post code..." value="" autocomplete="off" required>
									<input type="hidden" name="reg_post_code_latitude" id="reg_post_code_latitude">
									<input type="hidden" name="reg_post_code_longitude" id="reg_post_code_longitude">
								</div>

								<div class="dokan-form-group">
									<label>Hourly Rate</label>
									<input class="dokan-form-control" name="cost_per_hour" id="cost_per_hour" type="text" placeholder="Hourly Rate.." value="" required>
								</div>

								<div class="dokan-form-group">
									<label>Qualifications</label>
									<input class="dokan-form-control" name="qualifications" id="qualifications" type="text" placeholder="Qualifications.." value="" required>
								</div>

								<div class="dokan-form-group">
									<label>Enter Miles</label>
									<input class="dokan-form-control" name="reg_miles_km" id="reg_miles_km" type="number" placeholder="Enter miles...." value="<?php echo get_post_meta($post_id, 'reg_miles_km', true); ?>" autocomplete="off" required>
								</div>
								
								<div class="dokan-form-group">
									<label for="job_status">Job Status:</label>
									<select id="job_status" name="job_status">
										<option value="open">Active</option>
										<option value="closed">Inactive</option>
									</select>
								</div>

								<div class="dokan-form-group">
									<input type="submit" id="job_add" class="" name="job_add" value="Publish" />
								</div>
							</form>


						</div>

					<?php  } elseif ($_REQUEST['edit'] != '') {
						$job_id = $_REQUEST['edit'];
						$post_id = $job_id;
						$content_post = get_post($job_id);
						$content = $content_post->post_content;
						$job_status = get_post_status($job_id);
					?>


						<div class="addjobWrapper">
							<form method="POST" class="dokan-form-container">

								<div class="dokan-form-group">
									<label>job Name</label>
									<input class="dokan-form-control" name="job_title" id="job-title" type="text" placeholder="job name.." value="<?php echo get_the_title($_REQUEST['edit']); ?>" required>
								</div>

								<div class="dokan-form-group">

									<div class="dokan-product-short-description">
										<label for="post_excerpt" class="form-label"><?php esc_html_e('job Description', 'dokan-lite'); ?></label>
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
													'editor_class'  => 'job_content',
												]
											)
										);
										?>
									</div>

								</div>

								<div class="dokan-form-group">
									<label>job Name</label>
									<select name="select_position" id="select_position" class="dokan-form-control">
										<?php

										$terms = get_terms([
											'taxonomy'   => 'job_category',
											'hide_empty' => false, // Set to true if you want to exclude terms with no posts
										]);

										// Check if terms were retrieved
										if ($terms && !is_wp_error($terms)) {
											foreach ($terms as $term) {

												$select_position = get_post_meta($post_id, 'select_position', true);

												if ($term->slug == $select_position) {
													echo '<option selected value="' . $term->slug . '">' . $term->name . '</option>';
												} else {
													echo '<option value="' . $term->slug . '">' . $term->name . '</option>';
												}
											}
										}
										echo '<option value="other">other</option>';
										?>
									</select>
									<input class="dokan-form-control" style="display: none;" disabled name="enter_other_position" id="enter_other_position" type="text" placeholder="Enter Other Position" value="" autocomplete="off" required>
								</div>
								<div class="dokan-form-group">
									<label>Enter post code...</label>
									<input class="dokan-form-control" name="reg_post_code" id="reg_post_code" type="text" placeholder="Enter post code..." value="<?php echo get_post_meta($post_id, 'reg_post_code', true); ?>" autocomplete="off" required>
									<input type="hidden" name="reg_post_code_latitude" id="reg_post_code_latitude">
									<input type="hidden" name="reg_post_code_longitude" id="reg_post_code_longitude">
								</div>
								<div class="dokan-form-group">
									<label>Hourly Rate</label>
									<input class="dokan-form-control" name="cost_per_hour" id="cost_per_hour" type="text" placeholder="Hourly Rate.." value="<?php echo get_post_meta($post_id, 'cost_per_hour', true); ?>" required>
								</div>
								<div class="dokan-form-group">
									<label>Qualifications</label>
									<input class="dokan-form-control" name="qualifications" id="qualifications" type="text" placeholder="Qualifications.." value="<?php echo get_post_meta($post_id, 'qualifications', true); ?>" required>
								</div>
								<div class="dokan-form-group">
									<label>Enter Miles</label>
									<input class="dokan-form-control" name="reg_miles_km" id="reg_miles_km" type="number" placeholder="Enter miles...." value="<?php echo get_post_meta($post_id, 'reg_miles_km', true); ?>" autocomplete="off" required>
								</div>
								<div class="dokan-form-group">
									<label for="job_status">Job Status:</label>
									<select id="job_status" name="job_status">
										<option value="open" <?php selected($job_status, 'open'); ?>>Active</option>
										<option value="closed" <?php selected($job_status, 'closed'); ?>>Inactive</option>
									</select>
								</div>
								<div class="dokan-form-group">
									<input type="submit" id="job_update" class="" name="job_update" value="Update" />
								</div>
							</form>
						</div>
					<?php } else { ?>

						<div class="listWrapper">
							<div class="customHeading">
								<h3>Job List</h3>
							</div>
							<?php if ($userServisCount < 10000): ?>
								<div class="customButoon">
									<a href="<?php echo site_url(); ?>/dashboard/job/?add_new=1">Add New Job</a>
								</div>
							<?php endif; ?>

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
								<tbody id="job-list">
									<?php
									// Define query arguments
									$args = array(
										'post_type' => 'job',
										'author' => $author_id,
										'posts_per_page' => 10,
										'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
									);
									$the_query = new WP_Query($args);

									// The Loop
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
										echo "<tr><td colspan='5'>No job found</td></tr>";
									}
									wp_reset_postdata();
									?>
								</tbody>
							</table>

							<!-- Load More Button -->
							<div class="load-more-container" style="text-align:center">
								<button id="load-more" data-page="1" data-url="<?php echo admin_url('admin-ajax.php'); ?>" data-nonce="<?php echo wp_create_nonce('load_more_jobs'); ?>">
									Load More
								</button>
							</div>
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
		do_action('dokan_dashboard_content_inside_after');
		?>


	</div><!-- .dokan-dashboard-content -->

	<?php
	/**
	 *  dokan_dashboard_content_after hook
	 *
	 *  @since 2.4
	 */
	do_action('dokan_dashboard_content_after');
	?>

</div><!-- .dokan-dashboard-wrap -->
<style>
	.customHeading {
		width: 50%;
		float: left;

	}

	.customButoon {
		padding: 10px 20px;
		border-radius: 9px;

		color: #fff;
		width: 18%;
		background: #4DC42A;
		text-align: center;
		float: right;
	}

	.customButoon a {
		color: #fff;
	}

	/* Pagination Container */
	.pagination {
		display: flex;
		justify-content: center;
		/* Center the pagination */
		margin: 20px 0;
		/* Space around pagination */
	}

	/* Pagination Links */
	.pagination a,
	.pagination span {
		display: inline-block;
		padding: 8px 12px;
		margin: 0 2px;
		border: 1px solid #ddd;
		border-radius: 3px;
		text-decoration: none;
		color: #333;
		font-size: 14px;
		transition: background-color 0.3s, color 0.3s;
	}

	/* Active Page */
	.pagination .current {
		background-color: #0073aa;
		/* Active page background */
		color: #fff;
		/* Active page text color */
		border-color: #0073aa;
		/* Active page border color */
	}

	/* Pagination Hover */
	.pagination a:hover {
		background-color: #0073aa;
		color: #fff;
		border-color: #0073aa;
	}

	/* Disable Link */
	.pagination .disabled {
		color: #ccc;
		border-color: #ccc;
		cursor: not-allowed;
	}

	.custom_success_message {

		background: aliceblue;
		color: green;
		border: 1px solid;
		padding: 10px;
		text-align: center;
	}

	.custom_error_message {

		background: blanchedalmond;
		color: red;
		border: 1px solid;
		padding: 10px;
		text-align: center;
	}
</style>
<script>
	function deleteItem(obj) {

		var urlString = 'https://allchemists.co.uk/dashboard/job/?delete=';
		var checkstr = confirm('are you sure you want to delete this?');
		if (checkstr == true) {
			//	alert(obj);
			//var urlString = jQuery(this).attr('data-url');	
			window.location.replace(urlString + obj);


			// do your code
		} else {
			return false;
		}
	}
</script>

<script>
	jQuery(document).ready(function($) {
    $('#load-more').on('click', function() {
        var button = $(this);
        var page = button.data('page');
        var ajaxurl = button.data('url');
        var nonce = button.data('nonce');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_jobs',
                page: page,
                nonce: nonce
            },
            success: function(response) {
                if (response) {
                    $('#job-list').append(response);
                    button.data('page', page + 1);
                } else {
                    button.remove();
                }
            }
        });
    });
});
</script>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		$('#reg_post_code').on('keyup', function() {
			var postcode = $(this).val();

			if (postcode) {
				// Make a request to Google Maps Geocoding API
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode({
					'address': postcode
				}, function(results, status) {
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

		$('#select_position').on('change', function() {
			// Your custom code to execute when the select value changes
			var selectedValue = $(this).val();

			if (selectedValue === 'other') {
				$('#enter_other_position').show();
				$('#enter_other_position').prop('disabled', false);
			} else {
				$('#enter_other_position').hide();
				$('#enter_other_position').prop('disabled', true);
			}

			// You can perform additional actions based on the selected value
			// For example, make an AJAX request, show/hide elements, etc.
		});
	});
</script>