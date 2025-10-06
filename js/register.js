$(document).ready(function () {
    $('#register-form').submit(function (e) {
        e.preventDefault(); // Prevent the default form submission

        let name = $('#name').val();
        let email = $('#email').val();
        let password = $('#password').val();
        let country = $('#country').val();
        let city = $('#city').val();
        let phone_number = $('#contact').val();

        // Validate required fields
        if (!name || !email || !password || !country || !city || !phone_number) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });
            return;
        }

        // Validate password strength
        if (password.length < 6 || !password.match(/[a-z]/) || !password.match(/[A-Z]/) || !password.match(/[0-9]/)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password must be at least 6 characters long and contain at least one lowercase letter, one uppercase letter, and one number!',
            });
            return;
        }

        // Send data asynchronously using jQuery AJAX
        $.ajax({
            url: '../actions/register_customer_action.php',
            type: 'POST',
            data: {
                customer_name: name,
                customer_email: email,
                customer_pass: password,
                customer_country: country,
                customer_city: city,
                customer_contact: phone_number,
            },
            dataType: 'json',
            success: function (result) {
                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.message,
                    }).then(() => {
                        // Redirect to login page after successful registration
                        window.location.href = '../login/login.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: result.message,
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred! Please try again later.',
                });
            },
        });
    });
});