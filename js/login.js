$(document).ready(function() {
    $('#login-form').submit(function(e) {
        e.preventDefault();

        let email = $('#email').val().trim();
        let password = $('#password').val().trim();

        // Regex for email validation
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Validate inputs
        if (email === '' || password === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });
            return;
        } else if (!emailRegex.test(email)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Email',
                text: 'Please enter a valid email address!',
            });
            return;
        } else if (password.length < 6) {
            Swal.fire({
                icon: 'error',
                title: 'Weak Password',
                text: 'Password must be at least 6 characters long!',
            });
            return;
        }

        // Send data asynchronously to login_customer_action.php
        $.ajax({
            url: '../actions/login_customer_action.php',
            type: 'POST',
            dataType: 'json',
            data: { email: email, password: password },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome ' + response.name,
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect based on role
                            if (response.role === 'admin') {
                                window.location.href = 'admin_dashboard.php';
                            } else {
                                window.location.href = 'customer_view.php';
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message,
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred! Please try again later.',
                });
            }
        });
    });
});
