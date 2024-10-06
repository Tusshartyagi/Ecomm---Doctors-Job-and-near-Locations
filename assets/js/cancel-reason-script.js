jQuery(document).ready(function($) {
    // Save the cancel reason via AJAX
    $('#cancel_reason').on('change', function() {
        var order_id = $('#post_ID').val();
        var cancel_reason = $(this).val();
        var security = $('#cancel_reason_nonce').val();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'save_cancel_reason',
                order_id: order_id,
                cancel_reason: cancel_reason,
                security: security
            },
            success: function(response) {
				$('.cancel_reason_success').show();
				setTimeout(function() {
					$('.cancel_reason_success').fadeOut('fast');
				}, 10000);  
                console.log(response);
            },
            error: function(error) {
                console.error(error.responseText);
            }
        });
    });
});
