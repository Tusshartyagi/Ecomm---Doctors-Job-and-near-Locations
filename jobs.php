<?php
/* Template Name: Jobs */
get_header();
?>

<style type="text/css">
    .pagination {
        display: inline-block;
        width: 100%;
    }
</style>

<?php

$custom_post_type = 'job';
$per_page = wp_is_mobile() ? 6 : 9;
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

$args = array(
    'post_type'      => $custom_post_type,
    'posts_per_page' => $per_page,
    'paged'          => $paged,
    'meta_query'     => array(),
);

$postcode = isset($_GET['reg_post_code']) ? sanitize_text_field($_GET['reg_post_code']) : '';
$radius = isset($_GET['miles']) ? floatval($_GET['miles']) : 0;

if ($postcode) {
    if ($radius) {
        global $wpdb;

        // Retrieve latitude and longitude of the selected postcode
        $sql = $wpdb->prepare(
            "SELECT mnj.lon, mnj.lat
             FROM {$wpdb->prefix}near_job AS mnj
             INNER JOIN {$wpdb->prefix}postmeta AS pm ON mnj.post_id = pm.post_id
             WHERE pm.meta_key = 'reg_post_code' AND pm.meta_value = %s",
            $postcode
        );

        $location = $wpdb->get_row($sql);

        if ($location) {
            $latitude_postcode = floatval($location->lat);
            $longitude_postcode = floatval($location->lon);

            if ($latitude_postcode && $longitude_postcode) {
                $radius_in_meters = $radius * 1609.34; // Convert miles to meters

                // Use Haversine formula to filter postcodes within the radius
                $sql = $wpdb->prepare("
                    SELECT DISTINCT pm.meta_value AS postcode, 6371000 * acos(
                        cos(radians(%f)) * cos(radians(mnj.lat)) * cos(radians(mnj.lon) - radians(%f)) +
                        sin(radians(%f)) * sin(radians(mnj.lat))
                    ) AS distance
                    FROM {$wpdb->prefix}posts p
                    INNER JOIN {$wpdb->prefix}near_job mnj ON p.ID = mnj.post_id
                    INNER JOIN {$wpdb->prefix}postmeta pm ON mnj.post_id = pm.post_id
                    WHERE p.post_type = %s
                    AND p.post_status = 'publish'
                    AND pm.meta_key = 'reg_post_code'
                    HAVING distance < %d
                ", $latitude_postcode, $longitude_postcode, $latitude_postcode, $custom_post_type, $radius_in_meters);

                $postcodes = $wpdb->get_col($sql);

                // Output postcodes to the console for debugging
                echo "<script>console.log('Postcodes within radius: " . implode(', ', $postcodes) . "');</script>";

                if (!empty($postcodes)) {
                    // Ensure `meta_query` is constructed to handle multiple postcodes
                    $args['meta_query'][] = array(
                        'key'       => 'reg_post_code',
                        'value'     => $postcodes,
                        'compare'   => 'IN'
                    );
                    $args['meta_query']['relation'] = 'AND';
                } else {
                    echo '<p>No jobs found within the specified radius.</p>';
                }
            } else {
                echo '<p>Could not retrieve coordinates for the selected postcode.</p>';
            }
        } else {
            echo '<p>Invalid postcode or no coordinates found.</p>';
        }
    } else {
        // If no radius is provided, filter by the exact postcode
        $args['meta_query'][] = array(
            'key'       => 'reg_post_code',
            'value'     => $postcode,
            'compare'   => '='
        );
        $args['meta_query']['relation'] = 'AND';
    }
}

// Debug: Print the WP_Query arguments
echo "<script>console.log('WP_Query Arguments: " . json_encode($args) . "');</script>";

if (isset($_GET['job_category']) && !empty($_GET['job_category'])) {
    $position = sanitize_text_field($_GET['job_category']);
    $args['meta_query'][] = array(
        'key'       => 'select_position',
        'value'     => $position,
        'compare'   => '='
    );
    $args['meta_query']['relation'] = 'AND';
}

$custom_query = new WP_Query($args);

// Debug: Print SQL query executed by WP_Query
global $wp_query;
echo "<script>console.log('SQL Query: " . $wp_query->request . "');</script>";

// Fetch all postcodes for the dropdown filter
$all_args = array(
    'post_type'      => 'job',
    'posts_per_page' => -1,
);
$posts_with_meta = get_posts($all_args);
$reg_post_codes = array();

foreach ($posts_with_meta as $post) {
    $post_code = get_post_meta($post->ID, 'reg_post_code', true);
    if (!in_array($post_code, $reg_post_codes)) {
        $reg_post_codes[] = $post_code;
    }
}
?>

<style type="text/css">
    .page-template-jobs .pagination {
        display: inline-flex;
        width: auto;
        flex-wrap: nowrap;
        align-content: space-around;
        align-items: center;
        justify-content: space-between;
    }

    .pagination_top {
        display: block;
        text-align: center;
        width: 100%;
    }

    .page-template-jobs .pagination_top a.page-numbers {
        text-decoration: none;
        color: #000;
        float: left;
        padding: 8px 16px;
    }

    .pagination_top span.current {
        background-color: #4CAF50;
        color: #FFF;
        padding: 4px 10px;
    }
</style>

<div id="dokan-seller-listing-wrap" class="grid-view">
    <form id="position-filter-form" class="custome-form-filter">
        <span class="custome-job-category">
            <label for="job-category-filter"><b>Filter by Job Category:</b></label>
            <select id="job-category-filter" name="job_category" style=" width: 23%;">
                <option value="">Select Job Category</option>
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'job_category',
                    'hide_empty' => false,
                ));
                foreach ($categories as $category) {
                    $selected = isset($_GET['job_category']) && $_GET['job_category'] === $category->slug ? 'selected' : '';
                    echo '<option value="' . $category->slug . '" ' . $selected . '>' . $category->name . '</option>';
                }
                ?>
            </select>
        </span>
        <span class="custome-job-city-filter">
            <label for="job-city-filter"><b>Filter by Postcode:</b></label>
            <select id="job-city-filter" name="reg_post_code" style="width: 23%;">
                <option value="">Select Postcode</option>
                <?php
                foreach ($reg_post_codes as $postcode) {
                    $selected = isset($_GET['reg_post_code']) && $_GET['reg_post_code'] === $postcode ? 'selected' : '';
                    echo '<option value="' . $postcode . '" ' . $selected . '>' . $postcode . '</option>';
                }
                ?>
            </select>
        </span>
        <span class="custome-job-miles-filter">
            <label for="job-miles-filter"><b>Filter by Miles:</b></label>
            <input type="number" id="job-miles-filter" name="miles" placeholder="Enter miles (1 Miles = 1.609 KM)" style="width: 23%;">
        </span>
        <button type="submit">Filter</button>
    </form>

    <div class="seller-listing-content">
        <ul class="dokan-seller-wrap">
            <?php
            if ($custom_query->have_posts()) :
                while ($custom_query->have_posts()) : $custom_query->the_post();
                    $post_id = get_the_ID();
                    $post_url = get_permalink($post_id);
                    $post_date = get_the_date();
                    $author_id = get_post_field('post_author', $post_id);
                    $author_email = get_the_author_meta('user_email', $author_id);

                    global $wpdb;
                    $table_name = $wpdb->prefix . 'user_rating';
                    $rating_datas = $wpdb->get_results("SELECT user_rating FROM {$table_name} WHERE hk_job_id={$post_id}");
                    $total_ratings = array();
                    $num_ratings = count($rating_datas);
                    foreach ($rating_datas as $rating_data) {
                        $total_ratings[] = $rating_data->user_rating;
                    }
                    $average_rating = !empty($total_ratings) ? array_sum($total_ratings) / $num_ratings : 0;

                    $qualifications = get_post_meta($post_id, 'qualifications', true);
                    $reg_miles_km = get_post_meta($post_id, 'reg_miles_km', true);
                    $cost_per_hour = get_post_meta($post_id, 'cost_per_hour', true);
                    $reg_post_code = get_post_meta($post_id, 'reg_post_code', true);
            ?>
                    <li class="dokan-single-seller woocommerce coloum-3 no-banner-img hk_jobs">
                        <div class="store-wrapper">
                            <a href="<?php echo $post_url; ?>" class="store-banner-link">
                                <div class="store-header">
                                    <div class="store-banner">
                                        <img decoding="async" src="https://allchemists.co.uk/wp-content/plugins/dokan-lite/assets/images/default-store-banner.png">
                                    </div>
                                </div>
                            </a>
                            <div class="store-content default-store-banner hk_job_box_header">
                                <div class="store-data-container">
                                    <div class="featured-favourite"></div>
                                    <input type="hidden" class="job_id" name="job_id" value="<?php echo $post_id; ?>">
                                    <input type="hidden" name="qualifications" value="<?php echo $qualifications; ?>">
                                    <input type="hidden" name="reg_miles_km" value="<?php echo $reg_miles_km; ?>">
                                    <input type="hidden" name="cost_per_hour" value="<?php echo $cost_per_hour; ?>">
                                    <input type="hidden" name="reg_post_code" value="<?php echo $reg_post_code; ?>">
                                    <input type="hidden" name="posted_date" value="<?php echo $post_date; ?>">

                                    <div class="store-data">
                                        <h2 class="job_title"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h2>
                                    </div>
                                    <div class="jober_average_rating">
                                        <span class="hk_average_rating">
                                            <?php if ($average_rating != 0) { ?>
                                                <span><?php echo number_format($average_rating, 1); ?></span> <i class="fas fa-star"></i>
											(<?php echo $num_ratings; ?>)
                                            <?php } ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="store-footer">
                                <div class="seller-avatar">
                                    <?php echo get_avatar($author_id, 32); ?><span><?php echo esc_html(get_the_author_meta('display_name', $author_id)); ?></span>
                                </div>
                                <span class="dashicons dashicons-email-alt dokan-btn-theme dokan-btn-round hk_job_mail_popup" data-job-id="<?php echo $post_id; ?>"></span>
                                <input type="checkbox" class="job-checkbox" name="selected_jobs[]" value="<?php echo $post_id; ?>" style="margin: 5px;margin-top: 10px;">
                            </div>
                        </div>
                    </li>

            <?php
                endwhile;

                echo "<div class='pagination_top'>";
				$total_pages = $custom_query->max_num_pages;
				if ($total_pages > 1) {
					echo '<div class="pagination">';
					echo paginate_links(array(
						'base'    => add_query_arg('paged', '%#%'),
						'format'  => '',
						'current' => max(1, get_query_var('paged')),
						'total'   => $total_pages,
						'add_args' => array(
							'job_category' => isset($_GET['job_category']) ? $_GET['job_category'] : '',
							'reg_post_code' => isset($_GET['reg_post_code']) ? $_GET['reg_post_code'] : '',
							'miles' => isset($_GET['miles']) ? $_GET['miles'] : '',
						),
					));
					echo '</div>';
				}
				echo '</div>';

            endif;

            ?>
        </ul> <!-- .dokan-seller-wrap -->
    </div>
</div>
<div id="job_popup" class="job_popup">
    <div id="job_popup-content" class="job_popup-content">

    </div>
    <div class="all_reviews">
    </div>

    <?php if (is_user_logged_in()) {  ?>
        <form id="hk_reviewForm" method="post">
            <h3>Leave a Review</h3>
            <div class="rating">
                <!-- Star Rating Inputs -->
                <?php
                for ($i = 5; $i >= 1; $i--) {
                    echo '<input type="radio" id="star' . $i . '" name="user_rating" class="radio-btn" value="' . $i . '" required>';
                    echo '<label for="star' . $i . '"></label>';
                }
                ?>
            </div><br>
            <textarea id="hk_reviewDescription" name="hk_reviewDescription" placeholder="Write your review here..." required></textarea><br>
            <input type="text" id="hk_reviewerName" name="hk_reviewerName" placeholder="Your Name" required><br>
            <input type="hidden" id="hk_job_id" name="hk_job_id"><br>
            <button type="submit">Submit Review</button>
        </form>
    <?php } ?>
    <!-- <button id="hk_send_mail" class="send_mail" onclick="hk_sendmail()">Send Mail</button> -->
    <button id="job_close-popup" onclick="job_closePopup()">Close</button>
</div>

<div id="job_mail_popup" class="job_mail_popup">
    <!-- <button id="close-popup" onclick="jobclosePopupmail()">Close</button> -->

    <div id="popup-content" class="popup-content">
        <!-- Add a form inside the popup -->
        <form id="contactForm" enctype="multipart/form-data">
            <!-- <button id="close-popup" onclick="closePopupmail()">Close</button> -->

            <label for="userName">Name:</label>
            <input type="text" id="userName" name="userName" required><br>

            <input type="hidden" name="hk_job_id" id="hk_job_id">

            <label for="hk_qualification">Qualification:</label>
            <input type="text" id="hk_qualification" name="hk_qualification" required><br>

            <?php
            $content = ''; // Pre-populated content can go here
            $editor_id = 'userMessage'; // HTML ID attribute value for the textarea and TinyMCE. Must be unique.
            $args = array(
                'media_buttons' => false, // This setting removes the media button.
                'textarea_name' => 'userMessage', // Set custom name.
                // 'textarea_rows' => get_option('default_post_edit_rows', 10), //Determine the number of rows.
                // 'quicktags' => false, // Remove view as HTML button.
            );
            wp_editor($content, $editor_id, $args);
            ?>

            <br><label for="hk_file_attach">File attach:</label>
            <input type="file" id="hk_file_attach" name="hk_file_attach" accept=".pdf, .doc, .docx" required>

            <?php if (is_user_logged_in()) {

                echo '<input type="button" id="hk_job_send_mail" value="Send Mail" onclick="hk_job_sendmail()">';
            } else {
                echo '<div>You are not logged in.</div>';
            }   ?>
        </form>
    </div>
    <button id="close-popup" onclick="jobclosePopupmail()">Close</button>
</div>

<div id="overlay" class="overlay" onclick="job_closePopup()"></div>
<script>
    function job_closePopup() {
        document.getElementById('job_popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
		document.body.classList.remove('no-scroll'); // Enable scrolling
    }
    document.getElementById('overlay').addEventListener('click', function() {
        job_closePopup();
    });

    function jobclosePopupmail() {
        document.getElementById('job_mail_popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
		document.body.classList.remove('no-scroll'); // Enable scrolling
    }
    document.getElementById('overlay').addEventListener('click', function() {
        jobclosePopupmail();
    });

    function hk_job_sendmail() {
    // Get form values
    var userName = document.getElementById('userName').value.trim();
    var hk_qualification = document.getElementById('hk_qualification').value.trim();
    var userMessage = tinyMCE.get('userMessage').getContent();
    var fileInput = document.getElementById('hk_file_attach');
    var file = fileInput.files[0];

    // Collect all selected job IDs
    var selectedJobs = [];
    document.querySelectorAll('.job-checkbox:checked').forEach(function(checkbox) {
        selectedJobs.push(checkbox.value);
    });
	// Validate form fields
    if (userName === '') {
        alert('Please enter your name.');
        return;
    }

    if (hk_qualification === '') {
        alert('Please enter your qualification.');
        return;
    }

    if (userMessage === '') {
        alert('Please enter a message.');
        return;
    }

    if (!file) {
        alert('Please attach a file.');
        return;
    }

    if (selectedJobs.length === 0) {
        alert('Please select at least one job.');
        return;
    }
    // Validate form fields
    if (userName === '' || hk_qualification === '' || !file || selectedJobs.length === 0) {
        alert('Please fill in all fields, select a file, and choose at least one job.');
        return;
    }

    var formData = new FormData();
    formData.append('action', 'hk_job_send_email');
    formData.append('userName', userName);
    formData.append('hk_qualification', hk_qualification);
    formData.append('userMessage', userMessage);
    formData.append('hk_job_ids', selectedJobs.join(',')); // Send comma-separated job IDs
    formData.append('file', file);

    jQuery.ajax({
        url: admin_ajax.ajaxurl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                alert('Email sent successfully');
                jobclosePopupmail(); // Close the popup
            } else {
                alert('Failed to send email: ' + response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Failed to send email:', xhr.responseText);
            alert('Failed to send email. Please try again.');
        }
    });
}

</script>
<?php
// Restore original post data
wp_reset_postdata();

//do_action( 'matico_sidebar' );
get_footer();