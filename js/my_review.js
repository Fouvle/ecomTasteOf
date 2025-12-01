// js/my_reviews.js

// 1. Open Edit Modal (Fetches data from DB first)
function openEditModal(reviewId) {
    // Show loading state or clear previous values
    $('#edit_rating').val('');
    $('#edit_text').val('Loading...');
    
    // Fetch data from database
    $.ajax({
        url: '../actions/get_review.php',
        type: 'GET',
        data: { review_id: reviewId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Populate Modal Fields
                const review = response.data;
                $('#edit_review_id').val(review.review_id);
                $('#edit_rating').val(review.rating);
                $('#edit_text').val(review.review_text);
                
                // Show Modal
                document.getElementById('editReviewModal').style.display = 'block';
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Failed to fetch review details.', 'error');
        }
    });
}

// 2. Handle Edit Form Submission
$(document).ready(function() {
    $('#editReviewForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '../actions/manage_reviews_action.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if(res.status === 'success') {
                    document.getElementById('editReviewModal').style.display = 'none';
                    Swal.fire('Success', 'Review updated successfully.', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Connection failed.', 'error');
            }
        });
    });
});

// 3. Handle Delete
function deleteReview(id) {
    Swal.fire({
        title: 'Delete Review?',
        text: "Are you sure you want to remove this review?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('../actions/manage_reviews_action.php', { action: 'delete', review_id: id }, function(res) {
                if(res.status === 'success') {
                    $('#review-' + id).fadeOut();
                    Swal.fire('Deleted!', 'Review has been deleted.', 'success');
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        }
    });
}

// Helper to close modal
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}