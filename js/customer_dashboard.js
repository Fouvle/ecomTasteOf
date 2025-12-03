$(document).ready(function() {
    
    // Sidebar Navigation
    $('.nav-item').click(function(e) {
        const target = $(this).data('target');

        // If this nav-item does NOT have a data-target, allow normal navigation (redirect)
        if (typeof target === 'undefined' || target === '') {
            // Let the anchor behave normally (no preventDefault)
            return;
        }

        // Otherwise handle in-page tab switching
        e.preventDefault();
        $('.nav-item').removeClass('active');
        $(this).addClass('active');
        
        $('.view-section').removeClass('active');
        $('#view-' + target).addClass('active');
    });

    // Load reviews when user opens the reviews tab
    let reviewsLoaded = false;
    function loadReviews() {
        if (reviewsLoaded) return;
        $('#reviews-container').html('<i class="fas fa-spinner fa-spin"></i> Loading reviews...');
        $.get('../actions/get_review.php', function(res) {
            if (res.status === 'success') {
                const reviews = res.data;
                if (!reviews || reviews.length === 0) {
                    $('#reviews-container').html('<p style="color:gray;">You haven\'t written any reviews yet.</p>');
                    reviewsLoaded = true;
                    return;
                }

                let out = '';
                reviews.forEach(r => {
                    const created = new Date(r.created_at);
                    const dateStr = created.toLocaleString();
                    // Build star rating
                    let stars = '';
                    for (let i=1;i<=5;i++) stars += (i <= parseInt(r.rating) ? '★' : '☆');

                    out += `
                        <div class="review-item" style="border:1px solid #e5e7eb;padding:1rem;border-radius:8px;margin-bottom:0.75rem;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.4rem;">
                                <div style="font-weight:600;">${escapeHtml(r.business_name || 'Vendor')}</div>
                                <div style="color:#f59e0b;letter-spacing:1px;">${stars}</div>
                            </div>
                            <div style="color:#6b7280;font-size:0.9rem;margin-bottom:0.5rem;">${escapeHtml(r.review_text || '')}</div>
                            <div style="font-size:0.8rem;color:#9ca3af;">${dateStr}</div>
                        </div>
                    `;
                });

                $('#reviews-container').html(out);
                reviewsLoaded = true;
            } else {
                $('#reviews-container').html('<p style="color:red;">Could not load reviews.</p>');
            }
        }, 'json').fail(function() {
            $('#reviews-container').html('<p style="color:red;">Request failed while fetching reviews.</p>');
        });
    }

    // Expose to global scope so other ready handlers can call it
    // window.loadReviews = loadReviews;

    // Small helper to escape text when inserting into HTML
    function escapeHtml(unsafe) {
        if (!unsafe && unsafe !== 0) return '';
        return String(unsafe)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Helper: Modal Controls
    window.closeModal = function(id) { document.getElementById(id).style.display = 'none'; }
    window.openModal = function(id) { document.getElementById(id).style.display = 'block'; }

    // --- PAYMENT LOGIC ---
   
// Open Modal (Optional: You can skip modal and go straight to pay if preferred)
window.openPaymentModal = function(bookingId, vendorName, amount) {
    // We can use the existing modal but change the form action
    $('#payBookingId').val(bookingId);
    $('#payVendor').text(vendorName);
    $('#payAmount').text('₵' + amount);
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

// Also trigger loading reviews if the page was opened directly with reviews active
$(function(){
    // If reviews view is active on load, fetch reviews
    if ($('#view-reviews').hasClass('active')) loadReviews();
    // Bind to nav click to load when switched
    $('.nav-item[data-target="reviews"]').on('click', function(){ loadReviews(); });
});
