$(document).ready(function () {
    $('#bookingForm').submit(function (e) {
        e.preventDefault();

        const formData = $(this).serialize();

        // Show Loading
        Swal.fire({
            title: 'Processing...',
            text: 'Sending your reservation request',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        $.ajax({
            url: '../actions/book_action.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Booking Sent!',
                        text: response.message,
                        confirmButtonColor: '#ea580c',
                        confirmButtonText: 'View My Bookings'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'my_bookings.php';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Booking Failed',
                        text: response.message,
                        confirmButtonColor: '#ea580c'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Could not connect to the server.',
                    confirmButtonColor: '#ea580c'
                });
            }
        });
    });
});