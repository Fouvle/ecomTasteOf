// js/vendor_register.js

function updateProgress(step) {
    const totalSteps = 4;
    // Calculate percentage: (step - 1) / (totalSteps - 1) * 100
    // Step 1 = 0%, Step 4 = 100%
    const percentage = ((step - 1) / (totalSteps - 1)) * 100;
    
    document.getElementById('progressFill').style.width = percentage + '%';
    
    // Update circles
    document.querySelectorAll('.step').forEach(s => {
        const sNum = parseInt(s.getAttribute('data-step'));
        if (sNum <= step) {
            s.classList.add('active');
        } else {
            s.classList.remove('active');
        }
    });
}

function nextStep(currentStep) {
    // 1. Validate fields in the current step
    const currentSection = document.getElementById(`step${currentStep}`);
    const requiredInputs = currentSection.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    // Remove previous error highlights
    requiredInputs.forEach(input => input.style.borderColor = '#d1d5db');

    for (let input of requiredInputs) {
        if (!input.value.trim()) {
            input.style.borderColor = '#ef4444'; // Red border
            input.focus();
            isValid = false;
            
            // Simple toast notification
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: 'Please fill in all required fields.',
                showConfirmButton: false,
                timer: 3000
            });
            return; // Stop at first invalid field
        }
    }

    // Special validation for checkboxes in Step 1 and Step 3
    if (currentStep === 1) {
        const cuisines = document.querySelectorAll('input[name="cuisine_type[]"]:checked');
        if (cuisines.length === 0) {
            Swal.fire({ icon:'warning', text: 'Please select at least one Cuisine Type.' });
            return;
        }
    }
    
    if (currentStep === 3) {
        const days = document.querySelectorAll('input[name="operating_days[]"]:checked');
        if (days.length === 0) {
            Swal.fire({ icon:'warning', text: 'Please select at least one Operating Day.' });
            return;
        }
    }

    // 2. Hide current, Show next
    document.getElementById(`step${currentStep}`).style.display = 'none';
    document.getElementById(`step${currentStep + 1}`).style.display = 'block';
    
    // 3. Update Progress
    updateProgress(currentStep + 1);
    window.scrollTo(0, 0);
}

function prevStep(currentStep) {
    document.getElementById(`step${currentStep}`).style.display = 'none';
    document.getElementById(`step${currentStep - 1}`).style.display = 'block';
    
    updateProgress(currentStep - 1);
    window.scrollTo(0, 0);
}

// Handle Form Submission
$(document).ready(function() {
    $('#vendorForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        Swal.fire({
            title: 'Submitting Application...',
            text: 'Processing your business details',
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
                        title: 'Success!',
                        text: 'Vendor account created. Please login.',
                        confirmButtonColor: '#ea580c'
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Error',
                        text: response.message,
                        confirmButtonColor: '#ea580c'
                    });
                }
            },
            error: function() {
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