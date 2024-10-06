<?php
/* Template Name: Jober Template */
get_header();
?>

<style type="text/css">
    .pagination {
        display: inline-block;
        width: 100%;
    }
</style>

<?php
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$per_page = 9; // Number of jobers per page
if (wp_is_mobile()) {
    $per_page = 6;
}

$args = array(
    'role'      => 'jobers',
    'orderby'   => 'nicename',
    'order'     => 'ASC',
    'paged'     => $paged,
    'number'    => $per_page,
    'meta_query' => array(),
);

$postcode = isset($_GET['reg_post_code']) ? sanitize_text_field($_GET['reg_post_code']) : '';
$radius = isset($_GET['miles']) ? floatval($_GET['miles']) : 0;

if ($postcode) {
    if ($radius) {
        global $wpdb;

        // Retrieve latitude and longitude for the selected postcode
        $location_sql = $wpdb->prepare(
            "SELECT um_lat.meta_value AS latitude, um_lon.meta_value AS longitude
             FROM {$wpdb->prefix}usermeta um_lat
             INNER JOIN {$wpdb->prefix}usermeta um_lon ON um_lat.user_id = um_lon.user_id
             WHERE um_lat.meta_key = 'reg_post_code_latitude'
             AND um_lon.meta_key = 'reg_post_code_longitude'
             AND um_lat.user_id IN (
                 SELECT user_id 
                 FROM {$wpdb->prefix}usermeta 
                 WHERE meta_key = 'reg_post_code' 
                 AND meta_value = %s
             )",
            $postcode
        );
        $location_postcode = $wpdb->get_row($location_sql);

        if ($location_postcode) {
            $latitude_postcode = floatval($location_postcode->latitude);
            $longitude_postcode = floatval($location_postcode->longitude);
            $radius_in_meters = $radius * 1609.34; // Convert miles to meters

            // Use Haversine formula to find all postcodes within the radius
            $sql = $wpdb->prepare("
                SELECT DISTINCT um_postcode.meta_value AS postcode, 6371000 * acos(
                    cos(radians(%f)) * cos(radians(um_lat.meta_value)) * cos(radians(um_lon.meta_value) - radians(%f)) +
                    sin(radians(%f)) * sin(radians(um_lat.meta_value))
                ) AS distance
                FROM {$wpdb->prefix}usermeta um_lat
                INNER JOIN {$wpdb->prefix}usermeta um_lon ON um_lat.user_id = um_lon.user_id
                INNER JOIN {$wpdb->prefix}usermeta um_postcode ON um_lat.user_id = um_postcode.user_id
                WHERE um_lat.meta_key = 'reg_post_code_latitude'
                AND um_lon.meta_key = 'reg_post_code_longitude'
                AND um_postcode.meta_key = 'reg_post_code'
                HAVING distance < %d
            ", $latitude_postcode, $longitude_postcode, $latitude_postcode, $radius_in_meters);

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
                echo '<p>No jobers found within the specified radius.</p>';
            }
        } else {
            echo '<p>Could not retrieve coordinates for the selected postcode.</p>';
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

if (isset($_GET['job_category']) && !empty($_GET['job_category'])) {
    $position = sanitize_text_field($_GET['job_category']);
    $args['meta_query'][] = array(
        'key'       => 'select_position',
        'value'     => $position,
        'compare'   => '='
    );

    $args['meta_query']['relation'] = 'AND';
}

// Custom query to retrieve jobers with pagination
$users_query = new WP_User_Query($args);
$users = $users_query->get_results();

// Get all postcodes for dropdown
$args_all_users = array(
    'role' => 'jobers',
    'fields' => 'ID', // Retrieve only user IDs
);
$all_users = get_users($args_all_users);
$reg_post_codes = array();

// Loop through all users to collect unique 'reg_post_code' values
foreach ($all_users as $user_id) {
    $reg_post_code = get_user_meta($user_id, 'reg_post_code', true); // Get 'reg_post_code' for each user
    if (!empty($reg_post_code) && !in_array($reg_post_code, $reg_post_codes)) {
        $reg_post_codes[] = $reg_post_code;
    }
}
?>

<div id="dokan-seller-listing-wrap" class="grid-view">
    <form id="position-filter-form" class="custome-form-filter">
        <span class="custome-job-category">
            <label for="job-category-filter"><b>Filter by Job Category:</b></label>
            <select id="job-category-filter" name="job_category" style=" width: 23%;">
                <option value="">Select Job Category</option>
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'job_category', // Replace 'job_category_taxonomy' with your actual taxonomy name
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
                // Populate dropdown with unique 'reg_post_code' values
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

            foreach ($users as $user) {
                $user_id = $user->ID;
                $jober_name = $user->display_name;
                $jober_mail = $user->user_email;
                $user_info = get_userdata($user_id);
                $joined_date = date('F j, Y', strtotime($user_info->user_registered));
                $avatar = get_avatar($user->user_email, 96);
                $jober_qualification = get_user_meta($user_id, 'jober_qualification', true);
                $jober_position = get_user_meta($user_id, 'select_position', true);

                $table_name = $wpdb->prefix . 'user_rating';
                $rating_datas = $wpdb->get_results("SELECT user_rating from {$table_name} WHERE hk_jober_id={$user_id}");
                $total_ratings = array();
                $num_ratings = count($rating_datas);
                foreach ($rating_datas as $rating_data) {
                    $total_ratings[] = $rating_data->user_rating;
                }
                $average_rating = 0;
                if (!empty($total_ratings)) {
                    $average_rating = array_sum($total_ratings) / $num_ratings;
                }
            ?>
                <li class="dokan-single-seller woocommerce coloum-3 no-banner-img hk-jober-single-data">
                    <div class="store-wrapper">
                        <div class="store-header">
                            <div class="store-banner">
                                <img decoding="async" src="https://allchemists.co.uk/wp-content/plugins/dokan-lite/assets/images/default-store-banner.png">
                            </div>
                        </div>
                        <input type="hidden" name="user_id" value="<?php echo $user_id ?>" class="jober_id" id="jober_id">
                        <div class="store-content default-store-banner hk_jober_box_header">
                            <div class="store-data-container">
                                <div class="featured-favourite"></div>
                                <div class="store-data">
                                    <h2><?php echo $jober_position; ?></h2>
                                    <?php if (!empty($jober_qualification)) { ?>
                                        <h6><?php echo 'Qualification : ' . $jober_qualification; ?></h6>
                                    <?php } ?>
                                    <input type="hidden" name="jober_joined_date" value="<?php echo $joined_date; ?>">
                                </div>
                                <div class="jober_average_rating">
                                    <span class="hk_average_rating">
                                        <?php if ($average_rating != 0) { ?>
                                            <span><?php echo number_format($average_rating, 1); ?><span> <i class="fas fa-star"></i>
                                                    (<?php echo $num_ratings; ?>)
                                                <?php } ?>
                                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="store-footer">
                            <div class="seller-avatar">
                                <?php echo $avatar; ?>
                                <span class="custome-author-name"><?php echo esc_html($jober_name); ?></span>
                            </div>
                            <!-- Add Checkbox for Selecting Jobers -->
                            <span class="dashicons dashicons-email-alt dokan-btn-theme dokan-btn-round hk_send_mail_button"></span>
                            <input type="checkbox" class="select-jober" value="<?php echo $user_id; ?>" name="select_jober[]" style="margin: 5px;margin-top: 10px;" />
                        </div>
                    </div>
                </li>

            <?php } ?>

            <div class="pagination_top">
                <?php
                // Pagination
                $total_users = $users_query->get_total();
                $total_pages = ceil($total_users / $per_page);
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
                ?>
            </div>
        </ul> <!-- .dokan-seller-wrap -->
    </div>
</div>
<div id="popup" class="popup">
    <div id="popup-content" class="popup-content">

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
            <input type="hidden" id="hk_jober_id" name="hk_jober_id"><br>
            <button type="submit">Submit Review</button>
        </form>
    <?php } ?>
    <p class='review_save_message'></p>
    <!-- <button id="hk_send_mail" class="send_mail" onclick="hk_sendmail()">Send Mail</button> -->
    <button id="close-popup" onclick="closePopup()">Close</button>
</div>
<div id="mail_popup" class="mail_popup">
    <!-- <button id="close-popup" onclick="closePopupmail()">Close</button> -->

    <div id="popup-content" class="popup-content">
        <!-- Add a form inside the popup -->
        <form id="contactForm">
            <!-- <button id="close-popup" onclick="closePopupmail()">Close</button> -->

            <label for="userName">Job Name:</label>
            <input type="text" id="userName" name="userName" required><br>

            <!-- <label for="userEmail">Enter mail:</label>
            <input type="text" id="userEmail" name="userEmail" required> -->
            <input type="hidden" name="hk_jober_id" id="hk_jober_id">

            <label for="hk_postcode">Enter post code:</label>
            <input type="text" id="hk_postcode" name="hk_postcode" required><br>

            <label for="hk_costperhuor">Hourly Rate:</label>
            <input type="text" id="hk_costperhuor" name="hk_costperhuor" required><br>

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
            <!-- <label for="userMessage">Message:</label> -->
            <!-- <textarea id="userMessage" name="userMessage" required></textarea> -->

            <?php if (is_user_logged_in()) {

                echo '<input type="button" id="hk_send_mail" value="Send Mail" onclick="hk_sendmail()">';
            } else {
                echo '<div>You are not logged in.</div>';
            }    ?>
        </form>
    </div>
    <button id="close-popup" onclick="closePopupmail()">Close</button>
</div>

<div id="overlay" class="overlay" onclick="closePopup()"></div>
<script>
    function closePopup() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
        document.body.classList.remove('no-scroll'); // Enable scrolling
    }
    document.getElementById('overlay').addEventListener('click', function() {
        closePopup();
    });

    function closePopupmail() {
        document.getElementById('mail_popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
        document.body.classList.remove('no-scroll'); // Enable scrolling
    }
    document.getElementById('overlay').addEventListener('click', function() {
        closePopupmail();
    });

    function hk_sendmail() {
        var selectedJobers = [];
        jQuery('input[name="select_jober[]"]:checked').each(function() {
            selectedJobers.push(jQuery(this).val());
        });

        if (selectedJobers.length === 0) {
            alert("Please select at least one jober.");
            return;
        }

        var formData = {
            action: 'hk_send_email',
            hk_jober_ids: selectedJobers,
            userName: jQuery('#userName').val(),
            hk_postcode: jQuery('#hk_postcode').val(),
            hk_costperhuor: jQuery('#hk_costperhuor').val(),
            hk_qualification: jQuery('#hk_qualification').val(),
            userMessage: tinymce.get('userMessage').getContent() // Get content from TinyMCE editor
        };

        jQuery.post(ajaxurl, formData, function(response) {
            if (response.success) {
                alert(response.data); // Display success message
            } else {
                alert(response.data); // Display error message
            }
        }).fail(function() {
            alert('Failed to send emails.');
        });
    }


    function hk_sendmailllll() {
        var jober_name = jQuery('.seller-avatar span').text();
        console.log(jober_name);
        var jober_id = jQuery('#popup_jober_id').text();
        console.log(jober_id);
        jQuery.ajax({
            url: admin_ajax.ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: {
                jober_name: jober_name,
                jober_id: jober_id,
                action: 'hk_send_email' // Action hook defined in PHP function
            },
            success: function(response) {
                // Display the response from the server (optional)
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle errors (optional)
                console.error(xhr.responseText);
            }
        });
    }
</script>
<?php
// Restore original post data
wp_reset_postdata();

//do_action( 'matico_sidebar' );
get_footer();
?>