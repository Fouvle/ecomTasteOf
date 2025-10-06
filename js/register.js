$(document).ready(function () {
    $('#register-form').submit(async function (e) {
        e.preventDefault();

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

        try {
            // Send data asynchronously using fetch API
            const response = await fetch('../actions/register_customer_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    customer_name: name,
                    customer_email: email,
                    customer_pass: password,
                    customer_country: country,
                    customer_city: city,
                    customer_contact: phone_number,
                }),
            });

            const result = await response.json();

            if (result.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: result.message,
                }).then((res) => {
                    if (res.isConfirmed) {
                        // Redirect based on role
                        if (result.role == 1) {
                            window.location.href = '../admin/admin_dashboard.php';
                        } else {
                            window.location.href = '../login/login.php';
                        }
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: result.message,
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'An error occurred! Please try again later.',
            });
        }
    });
});