$(document).ready(function() {

    // --- ADD CATEGORY ---
    $('#add-category-form').submit(function(e) {
        e.preventDefault();

        let categoryName = $('#category_name').val().trim();

        if (categoryName === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Category name cannot be empty!'
            });
            return;
        }

        $.ajax({
            url: '../actions/add_category_action.php',
            type: 'POST',
            dataType: 'json',
            data: { category_name: categoryName },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                });
            }
        });
    });

    // --- UPDATE CATEGORY ---
    $('.update-category-form').submit(function(e) {
        e.preventDefault();

        let form = $(this);
        let categoryId = form.find('input[name="category_id"]').val();
        let categoryName = form.find('input[name="category_name"]').val().trim();

        if (categoryName === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Category name cannot be empty!'
            });
            return;
        }

        $.ajax({
            url: '../actions/update_category_action.php',
            type: 'POST',
            dataType: 'json',
            data: { category_id: categoryId, category_name: categoryName },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                });
            }
        });
    });

    // --- DELETE CATEGORY ---
    $('.delete-category-btn').click(function() {
        let categoryId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This category will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../actions/delete_category_action.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { category_id: categoryId },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });

    // --- APPROVE CATEGORY ---
    $('.approve-category-btn').click(function() {
        let categoryId = $(this).data('id');

        $.ajax({
            url: '../actions/approve_category_action.php',
            type: 'POST',
            data: { cat_id: categoryId },
            success: function() {
                Swal.fire('Approved!', 'Category has been approved.', 'success').then(() => location.reload());
            },
            error: function() {
                Swal.fire('Error', 'Something went wrong!', 'error');
            }
        });
    });

    // --- REJECT CATEGORY ---
    $('.reject-category-btn').click(function() {
        let categoryId = $(this).data('id');

        $.ajax({
            url: '../actions/reject_category_action.php',
            type: 'POST',
            data: { cat_id: categoryId },
            success: function() {
                Swal.fire('Rejected!', 'Category has been rejected.', 'error').then(() => location.reload());
            },
            error: function() {
                Swal.fire('Error', 'Something went wrong!', 'error');
            }
        });
    });

});
