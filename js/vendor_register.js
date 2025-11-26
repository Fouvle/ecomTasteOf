function nextStep(currentStep) {
    // 1. Validate current step fields
    const currentSection = document.getElementById(`step${currentStep}`);
    const inputs = currentSection.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'red';
            isValid = false;
        } else {
            input.style.borderColor = '#d1d5db';
        }
    });

    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Missing Information',
            text: 'Please fill in all required fields marked with *',
            confirmButtonColor: '#ea580c'
        });
        return;
    }

    // 2. Move UI
    document.getElementById(`step${currentStep}`).style.display = 'none';
    document.getElementById(`step${currentStep + 1}`).style.display = 'block';
    
    // Update Progress Bar
    document.querySelector(`.step[data-step="${currentStep + 1}"]`).classList.add('active');
    window.scrollTo(0, 0);
}

function prevStep(currentStep) {
    document.getElementById(`step${currentStep}`).style.display = 'none';
    document.getElementById(`step${currentStep - 1}`).style.display = 'block';
    
    // Update Progress Bar
    document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
    window.scrollTo(0, 0);
}

// Handle Submission
$(document).ready(function() {
    $('#vendorForm').on('submit', function(e) {
        e.preventDefault();

        // Gather Data
        const formData = new FormData(this);

        Swal.fire({
            title: 'Submitting Application...',
            text: 'Please wait while we process your details.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        $.ajax({
            url: '../actions/register_vendor_action.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome to TasteConnect!',
                        text: 'Your vendor application has been submitted successfully.',
                        confirmButtonColor: '#ea580c'
                    }).then(() => {
                        window.location.href = 'login.php'; // Redirect to standard login
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: response.message,
                        confirmButtonColor: '#ea580c'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Could not reach the server. Please try again.',
                    confirmButtonColor: '#ea580c'
                });
            }
        });
    });
});