$(document).ready(function() {
    $('#register-form').submit(function(e) {
        e.preventDefault();

        let name = $('#name').val();
        let email = $('#email').val();
        let password = $('#password').val();
        let phone_number = $('#phone_number').val();
        let role = $('input[name="role"]:checked').val();

        if (!name || !email || !password || !phone_number || !role) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });
            return;
        } 
        else if (password.length < 6 || !password.match(/[a-z]/) || !password.match(/[A-Z]/) || !password.match(/[0-9]/)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password must be at least 6 characters long and contain at least one lowercase letter, one uppercase letter, and one number!',
            });
            return;
        }

        $.ajax({
            url: '../actions/register_customer_action.php',
            type: 'POST',
            dataType: 'json',
            data: {
                name: name,
                email: email,
                password: password,
                phone_number: phone_number,
                role: role
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (response.role == 1) {
                                // Redirect admin
                                window.location.href = '../admin/admin_dashboard.php';
                            } else {
                                // Redirect customer
                                window.location.href = '../login/login.php';
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
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
