// js/my_reviews.js

// Open Edit Modal with Data
function openEditModal(id, rating, text) {
    document.getElementById('edit_review_id').value = id;
    document.getElementById('edit_rating').value = rating;
    document.getElementById('edit_text').value = text;
    document.getElementById('editReviewModal').style.display = 'block';
}

// Handle Delete
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

// Handle Edit Submission
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