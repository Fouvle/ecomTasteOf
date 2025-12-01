$(document).ready(function() {
    
    // Sidebar Navigation
    $('.nav-item').click(function(e) {
        e.preventDefault();
        $('.nav-item').removeClass('active');
        $(this).addClass('active');
        
        const target = $(this).data('target');
        $('.view-section').removeClass('active');
        $('#view-' + target).addClass('active');
    });

    // Helper: Modal Controls
    window.closeModal = function(id) { document.getElementById(id).style.display = 'none'; }
    window.openModal = function(id) { document.getElementById(id).style.display = 'block'; }

    // --- PAYMENT LOGIC ---
   
// Open Modal (Optional: You can skip modal and go straight to pay if preferred)
window.openPaymentModal = function(bookingId, vendorName, amount) {
    // We can use the existing modal but change the form action
    $('#payBookingId').val(bookingId);
    $('#payVendor').text(vendorName);
    $('#payamount').text('₵' + amount);
    // Store exact amount for the API call
    $('#paymentForm').data('amount', amount);
    openModal('paymentModal');
}

$('#paymentForm').submit(function(e) {
    e.preventDefault();
    
    const bookingId = $('#payBookingId').val();
    const amount = $(this).data('amount');

    Swal.fire({
        title: 'Initiating Payment...',
        text: 'Connecting to Paystack Secure Gateway',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    // Call Initialize Action
    $.ajax({
        url: '../actions/initialize_payment.php',
        type: 'POST',
        data: JSON.stringify({ booking_id: bookingId, amount: amount }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(res) {
            if(res.status === 'success') {
                // Redirect to Paystack
                window.location.href = res.authorization_url;
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Payment AJAX Error:', status, error);
            console.error('Response:', xhr.responseText);
            console.error('Status Code:', xhr.status);
            Swal.fire('Error', 'Could not initialize payment. Status: ' + xhr.status + '. Check console.', 'error');
        }
    });
});

    // --- CANCEL LOGIC ---
    window.cancelBooking = function(id) {
        Swal.fire({
            title: 'Cancel Reservation?',
            text: "This cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../actions/manage_booking_action.php', { action: 'delete', booking_id: id }, function(res) {
                    if(res.status === 'success') location.reload();
                    else Swal.fire('Error', 'Could not cancel.', 'error');
                }, 'json');
            }
        });
    }

    // --- REVIEW LOGIC ---
    window.openReviewModal = function(bkId, vId, vName) {
        $('#reviewBookingId').val(bkId);
        $('#reviewVendorId').val(vId);
        $('#reviewVendorName').text(vName);
        openModal('reviewModal');
    }

    $('#reviewForm').submit(function(e) {
        e.preventDefault();
        $.post('../actions/add_review_action.php', $(this).serialize(), function(res) {
            if(res.status === 'success') {
                Swal.fire('Thank You!', 'Review submitted.', 'success').then(() => {
                    closeModal('reviewModal');
                    location.reload();
                });
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json');
    });

    // --- DETAILS LOGIC ---
    window.viewDetails = function(id) {
        $('#detailsContent').html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        openModal('detailsModal');
        
        // Fetch via AJAX (assuming action exists or using inline data if simple)
        // For prototype, just parsing row data or simple message
        // Ideally: $.get('../actions/get_booking_details.php?id='+id, ...)
        
        // Simulating fetch for now based on known data or simple text
        $('#detailsContent').html(`
            <strong>Booking ID:</strong> #${id}<br>
            <br>
            Please arrive 10 minutes early.<br>
            Contact vendor if you are running late.
        `);
    }
});
