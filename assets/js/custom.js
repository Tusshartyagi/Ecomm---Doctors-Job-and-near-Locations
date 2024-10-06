/**

 * This is custom js file for British Chemist site.

 */



(function ($) {

	jQuery(document).ready(function ($) {
		$('#questions_form').submit(function () {
			var error = '';
			$('.answer_box').each(function(index, value) {
				var textareaval = jQuery(this).val();
				if(textareaval == ''){
					jQuery(this).addClass('errorFreeText');
					error = 'exit';
				} else {
					jQuery(this).removeClass('errorFreeText');
				} 
			}); 
			if (error  === '') {
				return true;
			} else {
				jQuery(".required_error").show();
				return false;
			}
		});
	});
	

	jQuery('#reg_billing_dob, #consultation_dob, #patient_consultation_dob' ).datepicker({

		changeYear: true,

		defaultDate: (new Date(1950, 1, 1)),

		yearRange: '1900:' + new Date().getFullYear(),

		dateFormat: 'dd-mm-yy'

	}); 

	$(document).on({

		click: function () { 

			let target = $(this);

			let id = target.attr('data-id');


			$('#server_error_' + id).hide();



			if (target.attr('data-value') == 0) {

				$('#error_' + id).show();
				$('#free_text_' + id).show();

			} else {

				$('#error_' + id).hide();
				$('#free_text_' + id).hide();

			}

		}

	}, '.radio_answer');


		
	jQuery('body').delegate('#add_patient', 'change', function () {  
	if(jQuery('#add_patient').is(':checked')){


	jQuery(".patientForm").show();
	jQuery(".requiredFields").attr("required","required");
	} else {
	jQuery(".requiredFields").removeAttr("required","required");
	jQuery(".patientForm").hide();
	} });


	$('body').delegate('#autofill_register_details', 'change', function () {

		if ($(this).is(':checked')) {

			$.ajax({

				url: woocommerce_params.ajax_url,

				type: "GET",

				data: { action: "autofill_register_details" },

				success: function (response) {

					var json = $.parseJSON(response);

					$('#consultation_first_name').val(json.first_name);

					$('#consultation_last_name').val(json.last_name);

					$('#consultation_dob').val(json.dob);

					$('#consultation_phone').val(json.phone);

	

					$('#consultation_address').val(json.address);

					$('#consultation_postcode').val(json.postcode);

					$('#consultation_country').val(json.country);

					$('#consultation_address_1').val(json.address_1);

					$('#consultation_address_2').val(json.address_2);

					$('#consultation_city').val(json.city);

					$('#consultation_state').val(json.state);

					$('#consultation_door_num').val(json.door_number);

				}

			});

		} else {

			$('#consultation_first_name, #consultation_last_name, #consultation_dob, #consultation_phone').val('');

			$('#consultation_address, #consultation_postcode, #consultation_country, #consultation_address_1').val('');

			$('#consultation_address_2, #consultation_city, #consultation_state, #consultation_door_num').val('');

		}

	});



/* 	$('body').delegate('#use_prescription_details', 'change', function () {

		if ($(this).is(':checked')) { */


			$.ajax({

				url: woocommerce_params.ajax_url,

				type: "GET",

				data: { action: "use_prescription_details" },

				success: function (response) {

					var json = $.parseJSON(response);

					if (json.flag == 'true') {

						$('#billing_first_name').val(json.first_name);

						$('#billing_last_name').val(json.last_name);



						$('#billing_postcode').val(json.postcode);

						$('#billing_phone').val(json.phone);

						$('#billing_country').val(json.country_code);

						$('#billing_country').trigger('change');

						if (json.address_1 == '') {

							$('#billing_address_1').val(json.address_2);

						} else {

							$('#billing_address_1').val(json.address_1);

							$('#billing_address_2').val(json.address_2);

						}

						$('#billing_city').val(json.city);

						$('#billing_door_number').val(json.door_number);

						if (json.email_address != 'false' && json.email_address != '') {

							$('#billing_email').val(json.email_address);

						}

						setTimeout(function () {

							jQuery('#billing_state').val(json.state);

							jQuery('#billing_state').trigger('change');

						}, 1500);



					}

				}

			});

		/* } else {

			$('#billing_first_name, #billing_last_name, #billing_address_1, #billing_address_2, #billing_door_number').val('');

			$('#billing_postcode, #billing_phone, #billing_city, #autofill_checkout_field, #billing_state').val('');

		} 

	});*/



	initCustomAutocomplete();



})(jQuery);







var autofill, place , autofill_set, place_patient;



function initCustomAutocomplete() {

	var wcaf = {"autofill_for_shipping":"1","selectedCountry":["GB"],"enable_billing_company_name":"","enable_shipping_company_name":"","enable_billing_phone":"","locationImage":"Location Image","uploadImage":"Upload Image"};

	if (jQuery('#consultation_address').length > 0) {



		autofill = new google.maps.places.Autocomplete(document.getElementById('consultation_address'));



		autofill.setComponentRestrictions({

			'country': wcaf.selectedCountry

		});



		autofill.addListener('place_changed', fillInPrescriptionAddress);

	}
	
	
	
	if (jQuery('#patient_consultation_address').length > 0) {



		autofill_set = new google.maps.places.Autocomplete(document.getElementById('patient_consultation_address'));



		autofill_set.setComponentRestrictions({

			'country': wcaf.selectedCountry

		});



		autofill_set.addListener('place_changed', fillInPrescriptionAddress_patient);

	}
	
	



	if (jQuery('#autofill_checkout_field').length > 0) {

		autofill = new google.maps.places.Autocomplete(document.getElementById('autofill_checkout_field'));



		autofill.setComponentRestrictions({

			'country': wcaf.selectedCountry

		});

		autofill.addListener('place_changed', fillInBillingsAddress);

	}

}



function fillInBillingsAddress() {

	place = autofill.getPlace();

	jQuery('#billing_postcode').val('');

	jQuery('#billing_address_2').val('');

	jQuery('#billing_address_1').val('');

	jQuery('#billing_city').val('');

	//console.log(place); alert(place);

	const addressComponent = autoFillParseAddress(place.address_components);



	jQuery('#billing_country').val(addressComponent.country);

	jQuery('#billing_country').trigger('change');

	if (addressComponent.complete_address_1 == '') {

		jQuery('#billing_address_1').val(addressComponent.complete_address_2);

	} else {

		jQuery('#billing_address_1').val(addressComponent.complete_address_1);

		jQuery('#billing_address_2').val(addressComponent.complete_address_2);

	}

	jQuery('#billing_city').val(addressComponent.district);

	jQuery('#billing_postcode').val(addressComponent.postal_code);

	setTimeout(function () {

		jQuery('#billing_state').val(addressComponent.state);

		jQuery('#billing_state').trigger('change');

	}, 1500);

}



function fillInPrescriptionAddress() {



	place = autofill.getPlace();

	jQuery('#consultation_country').val('');

	jQuery('#consultation_postcode').val('');

	jQuery('#consultation_address_1').val('');

	jQuery('#consultation_address_2').val('');

	jQuery('#consultation_city').val('');

	//console.log(place); //alert(place);

	const addressComponent = autoFillParseAddress(place.address_components);



	jQuery('#consultation_country').val(addressComponent.country);

	if (addressComponent.complete_address_1 == '') {

		jQuery('#consultation_address_1').val(addressComponent.complete_address_2);

	} else {

		jQuery('#consultation_address_1').val(addressComponent.complete_address_1);

		jQuery('#consultation_address_2').val(addressComponent.complete_address_2);

	}



	jQuery('#consultation_city').val(addressComponent.district);

	jQuery('#consultation_postcode').val(addressComponent.postal_code);
	

	setTimeout(function () {

		jQuery('#consultation_state').val(addressComponent.state);

	}, 1500);

}



function fillInPrescriptionAddress_patient() {



	place_patient = autofill_set.getPlace();
	jQuery('#patient_consultation_postcodes').val('');


	const addressComponent_patient = autoFillParseAddress(place_patient.address_components);



	jQuery('#patient_consultation_postcode').val(addressComponent_patient.postal_code);



}


document.addEventListener("DOMContentLoaded", function() {
    var jobers = document.querySelectorAll('.hk-jober-single-data');
    var hk_jober_box_headers = document.querySelectorAll('.hk_jober_box_header');

    hk_jober_box_headers.forEach(function(hk_jober_box_header, index) {
        hk_jober_box_header.addEventListener('click', function() {
            var jober = this; // Current header element
            openPopup_header(jober, jobers, index);
        });
    });

    var jober_mail_sends = document.querySelectorAll('.hk_send_mail_button');
    jober_mail_sends.forEach(function(jober_mail_send) {
        jober_mail_send.addEventListener('click', function() {
            var jober = this; // Current mail send button
            openPopup_mail(jober);
        });
    });

    function openPopup_mail(jober_mail_send) {
    // Extract data from the clicked element
    var jober = jober_mail_send.closest('.hk-jober-single-data');

    // Debugging: Check if `jober` is selected correctly
    if (!jober) {
        console.error('Jober not found');
        return;
    }

    var position = jober.querySelector('.store-data h2') ? jober.querySelector('.store-data h2').textContent : 'No position available';
    var name = jober.querySelector('.seller-avatar span') ? jober.querySelector('.seller-avatar span').textContent : 'No name available';

    var qualificationElement = jober.querySelector('.store-data h6');
    var qualification = qualificationElement ? qualificationElement.textContent : 'No qualification provided';
    
    var jober_id = jober.querySelector('.jober_id') ? jober.querySelector('.jober_id').value : 'No ID available';
    var sellerAvatarHTML = jober.querySelector('.seller-avatar') ? jober.querySelector('.seller-avatar').innerHTML : 'No avatar available';

    // Retrieve the joined date
    var joinedDateElement = jober.querySelector('input[name="jober_joined_date"]');
    var joinedDate = joinedDateElement ? joinedDateElement.value : 'Date not available';

    document.getElementById('hk_jober_id').value = jober_id;

    // Display the popup with the constructed content
    document.getElementById('popup-content').innerHTML = `
        <h2>${position}</h2>
        <div class="seller-avatar">${sellerAvatarHTML}</div>
        <p>${qualification}</p>
        <p>Joined on: ${joinedDate}</p>
        <p id="popup_jober_id" style="display: none;">${jober_id}</p>
    `;
    document.getElementById('mail_popup').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
	document.body.classList.add('no-scroll'); // Disable scrolling
}


    function openPopup_header(jober, jobers, index) {
        // Extract data from the clicked element
        var position = jobers[index].querySelector('.store-data h2').textContent;
        var name = jobers[index].querySelector('.seller-avatar span').textContent;
        // var rating = jobers[index].querySelector('.hk_average_rating span').textContent;

        var qualificationElement = jobers[index].querySelector('.store-data h6');
        var qualification = qualificationElement ? qualificationElement.textContent : 'No qualification provided';
		var joinedDateElement = jober.querySelector('input[name="jober_joined_date"]');
    	var joinedDate = joinedDateElement ? joinedDateElement.value : 'Date not available';
        var jober_id = jobers[index].querySelector('.jober_id').value;
        var sellerAvatarHTML = jobers[index].querySelector('.seller-avatar').innerHTML;

        document.getElementById('hk_jober_id').value = jober_id;

        // Construct HTML for the popup content
        var popupContent = `
            <h2>${position}</h2>
            <div class="seller-avatar">${sellerAvatarHTML}</div>
            <p>${qualification}</p>
			<p>Joined on: ${joinedDate}</p>
            <p id="popup_jober_id" style="display: none;">${jober_id}</p>
        `;
        jQuery.ajax({
        	url: admin_ajax.ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: 'jober_id=' + jober_id + '&action=hk_review_data',
            success: function(response) {
                jQuery('.all_reviews').html(response);
            }

        });
        // Display the popup with the constructed content
        document.getElementById('popup-content').innerHTML = popupContent;
        document.getElementById('popup').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
		document.body.classList.add('no-scroll'); // Disable scrolling
    }

    var hk_job_box_headers = document.querySelectorAll('.hk_job_box_header');
    var hk_jobs = document.querySelectorAll('.hk_jobs');
    var seller_avatars = document.querySelectorAll('.seller-avatar');

    hk_job_box_headers.forEach(function(hk_job_box_header, index) {
        hk_job_box_header.addEventListener('click', function() {
            var job = this; // Current header element
            openPopup_header_job(job, hk_jobs, index);
        });
    });

    seller_avatars.forEach(function(seller_avatar, index) {
        seller_avatar.addEventListener('click', function() {
            var job = this.closest('.hk_jobs'); // Find the parent job element
            var jobIndex = Array.from(hk_jobs).indexOf(job);
            openPopup_header_job(job, hk_jobs, jobIndex);
        });
    });

    function openPopup_header_job(job, hk_jobs, index){
    	var job_title = hk_jobs[index].querySelector('.job_title a').textContent;
    	var jobAvatarHTML = hk_jobs[index].querySelector('.seller-avatar').innerHTML;
    	var qualifications = hk_jobs[index].querySelector('input[name="qualifications"]').value;
    	var reg_miles_km = hk_jobs[index].querySelector('input[name="reg_miles_km"]').value;
	    var cost_per_hour = hk_jobs[index].querySelector('input[name="cost_per_hour"]').value;
	    var reg_post_code = hk_jobs[index].querySelector('input[name="reg_post_code"]').value;
		var posted_date = hk_jobs[index].querySelector('input[name="posted_date"]').value; 
	    var job_id = hk_jobs[index].querySelector('input[name="job_id"]').value;

    	var popupContent = `
            <h2>${job_title}</h2>
            <div class="seller-avatar">${jobAvatarHTML}</div>
            <div class="qualifications">Qualification : ${qualifications}</div>
            <div class="reg_miles_km">Miles : ${reg_miles_km}</div>
            <div class="cost_per_hour">Hourly Rate : ${cost_per_hour}</div>
            <div class="reg_post_code">Postcode : ${reg_post_code}</div>
			<div class="posted_date">Posted on : ${posted_date}</div>
            <div class="job_id" style="Display:none">${job_id}</div>
        `;

        jQuery.ajax({
        	url: admin_ajax.ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: 'hk_job_id=' + job_id + '&action=hk_review_data',
            success: function(response) {
                jQuery('.all_reviews').html(response);
            }

        });

        document.getElementById('job_popup-content').innerHTML = popupContent;

    	document.getElementById('job_popup').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
		document.body.classList.add('no-scroll'); // Disable scrolling
		
    }

    var job_mail_sends = document.querySelectorAll('.hk_job_mail_popup');
    job_mail_sends.forEach(function(job_mail_send, index) {
        job_mail_send.addEventListener('click', function() {
            var job = this; // Current mail send button
            openPopup_mail_job(job, hk_jobs, index);
        });
    });

    function openPopup_mail_job(job_mail_send, hk_jobs, index) {
    	// console.log(job_mail_send);

    	var job_id = hk_jobs[index].querySelector('.job_id').value;
    	document.getElementById('hk_job_id').value = job_id;

        document.getElementById('job_mail_popup').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
		document.body.classList.add('no-scroll'); // Disable scrolling
    }

});

jQuery(document).ready(function($) {
    $('#position-filter-form').on('submit', function(e) {
        // e.preventDefault();
        var jobCategory = $('#job-category-filter').val();
        var jobCity = $('#job-city-filter').val(); // Get selected job city
        var url = window.location.href.split('?')[0]; // Get the current URL without query parameters
        var params = new URLSearchParams(window.location.search);
        params.set('job_category', jobCategory); // Set job category filter
        params.set('reg_post_code', jobCity); // Set job city filter
        window.location.href = url + '?' + params.toString();
    });

    $('#hk_reviewForm').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        var hk_jober_id = $('#popup_jober_id').text();
        var hk_job_id = $('.job_id').text();
        // Serialize form data
        var formData = $(this).serialize();
        formData += '&hk_jober_id=' + hk_jober_id;
        formData += '&hk_job_id=' + hk_job_id;
        console.log(formData);

        var form = this;

        $.ajax({
            url: ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: formData + '&action=hk_save_user_review',
            success: function(response) {
                if (response.success) {
	                $('.review_save_message').text(response.message);
	                form.reset();

	                setTimeout(function() {
		                $('.review_save_message').text('');
		            }, 5000);
	            } else {
	                // If the response_data indicates failure
	                console.error('Failed to save review:', response_data.message); // Log error message
	                alert('Failed to save review. Please try again.'); // Show error message
	            }
            },
            error: function(xhr, status, error) {
                console.error('Failed to save review:', xhr.responseText); // Log error message
                alert('Failed to save review. Please try again.'); // Show error message
            }
        });
    });
});


// jQuery(document).ready(function($) {
//     // Function to update posts per page based on screen width
//     function updatePostsPerPage() {
//         var perPage;
//         if (window.innerWidth <= 768) {
//             perPage = 4; // Number of jobers per page for mobile
//         } else {
//             perPage = 9; // Default number of jobers per page for larger screens
//         }

//         // AJAX call to update posts per page
//         $.ajax({
//             url: ajaxurl,
//             type: 'POST',
//             data: {
//                 action: 'update_posts_per_page',
//                 per_page: perPage
//             },
//             success: function(response) {
//                 console.log('Posts per page updated successfully.');
//                 // Reload the page to reflect the changes
//                 location.reload();
//             },
//             error: function(xhr, status, error) {
//                 console.error('Error updating posts per page:', error);
//             }
//         });
//     }

//     // Call updatePostsPerPage on document ready and window resize
//     $(document).ready(updatePostsPerPage);
//     $(window).resize(updatePostsPerPage);
// });