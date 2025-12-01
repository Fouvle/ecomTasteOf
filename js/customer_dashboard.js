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
    window.openPaymentModal = function(bookingId, vendorName, amount) {
        $('#payBookingId').val(bookingId);
        $('#payVendor').text(vendorName);
        $('#payAmount').text('₵' + amount);
        openModal('paymentModal');
    }

    $('#paymentForm').submit(function(e) {
        e.preventDefault();
        const data = $(this).serialize();
        
        Swal.fire({
            title: 'Processing Payment...',
            text: 'Please check your phone for the prompt.',
            didOpen: () => Swal.showLoading()
        });

        $.post('../actions/manage_booking_action.php', data, function(res) {
            if(res.status === 'success') {
                Swal.fire('Paid!', 'Booking confirmed.', 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json');
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