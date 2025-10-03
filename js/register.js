$(document).ready(function () {
    $('#register-form').submit(function (e) {
        e.preventDefault();

        // Show spinner
        $('#loading-spinner').show();

        // Collect form data
        let name = $('#name').val().trim();
        let email = $('#email').val().trim();
        let password = $('#password').val().trim();
        let country = $('#country').val().trim();
        let city = $('#city').val().trim();
        let phone_number = $('#phone_number').val().trim();
        let role = $('input[name="role"]').val(); // hidden input
        let image = $('#image')[0].files[0];

        // Basic validation
        if (!name || !email || !password || !country || !city || !phone_number) {
            $('#loading-spinner').hide();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all required fields!',
            });
            return;
        }

        // Email validation
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            $('#loading-spinner').hide();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Email',
                text: 'Please enter a valid email address!',
            });
            return;
        }

        // Password validation
        if (password.length < 6 || !/[a-z]/.test(password) || !/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
            $('#loading-spinner').hide();
            Swal.fire({
                icon: 'error',
                title: 'Weak Password',
                text: 'Password must be at least 6 characters and contain uppercase, lowercase, and a number.',
            });
            return;
        }

        // Phone validation (7-15 digits)
        let phoneRegex = /^[0-9]{7,15}$/;
        if (!phoneRegex.test(phone_number)) {
            $('#loading-spinner').hide();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Phone Number',
                text: 'Contact number must be between 7–15 digits.',
            });
            return;
        }

        // Build FormData for file upload
        let formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('password', password);
        formData.append('country', country);
        formData.append('city', city);
        formData.append('phone_number', phone_number);
        formData.append('role', role);
        if (image) {
            formData.append('image', image);
        }

        // AJAX request
        $.ajax({
            url: '../actions/register_customer_action.php',
            type: 'POST',
            data: formData,
            processData: false, // important for file upload
            contentType: false,
            success: function (response) {
                $('#loading-spinner').hide();

                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then(() => {
                        window.location.href = '../login/login.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    });
                }
            },
            error: function () {
                $('#loading-spinner').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred! Please try again later.',
                });
            }
        });
    });
});
