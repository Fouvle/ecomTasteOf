$(document).ready(function() {
    let selectedCategoryId = null;

    // Fetch and display all user categories
    function loadCategories() {
        $.ajax({
            url: "../actions/fetch_category_action.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                const tbody = $("#categoryTable tbody");
                tbody.empty();

                if (response.status === 'error') {
                    tbody.append(`<tr><td colspan="4" class="text-center text-danger">${response.message}</td></tr>`);
                    return;
                }

                if (response.length === 0) {
                    tbody.append(`<tr><td colspan="4" class="text-center">No categories found. Add your first category above!</td></tr>`);
                    return;
                }

                response.forEach((cat, index) => {
                    const statusBadge = cat.is_approved == 1
                        ? "<span class='badge bg-success'>Approved</span>"
                        : cat.is_approved == 2
                            ? "<span class='badge bg-danger'>Rejected</span>"
                            : "<span class='badge bg-warning text-dark'>Pending</span>";

                    tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${cat.category_name}</td>
                            <td>${statusBadge}</td>
                            <td>
                                <button class="btn btn-info btn-sm view-category-btn" 
                                        data-id="${cat.category_id}" 
                                        data-name="${cat.category_name}">
                                    View / Edit
                                </button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching categories:', error);
                const tbody = $("#categoryTable tbody");
                tbody.html(`<tr><td colspan="4" class="text-center text-danger">Failed to load categories. Please try again.</td></tr>`);
            }
        });
    }

    // Handle adding a new category
    $("#addCategoryForm").submit(function(e) {
        e.preventDefault();
        const categoryName = $("#newCategoryName").val().trim();
        
        if (categoryName === "") {
            alert("Please enter a category name.");
            return;
        }

        $.ajax({
            url: "../actions/add_category_action.php",
            type: "POST",
            data: { category_name: categoryName },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $("#newCategoryName").val("");
                    loadCategories();
                    alert("Category added successfully!");
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error adding category:', error);
                alert("Error adding category. Please try again.");
            }
        });
    });

    // Open modal for edit
    $(document).on("click", ".view-category-btn", function() {
        selectedCategoryId = $(this).data("id");
        $("#catId").val(selectedCategoryId);
        $("#catName").val($(this).data("name"));

        const modal = new bootstrap.Modal(document.getElementById("categoryModal"));
        modal.show();
    });

    // Update category
    $("#saveCategoryBtn").click(function() {
        const categoryName = $("#catName").val().trim();
        
        if (!categoryName) {
            alert("Please enter a category name.");
            return;
        }

        $.ajax({
            url: "../actions/update_category_action.php",
            type: "POST",
            data: $("#editCategoryForm").serialize(),
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $("#categoryModal").modal("hide");
                    loadCategories();
                    alert("Category updated successfully!");
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating category:', error);
                alert("Error updating category. Please try again.");
            }
        });
    });

    // Delete category
    $("#deleteCategoryBtn").click(function() {
        if (!confirm("Are you sure you want to delete this category? This action cannot be undone.")) {
            return;
        }

        $.ajax({
            url: "../actions/delete_category_action.php",
            type: "POST",
            data: { category_id: selectedCategoryId },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $("#categoryModal").modal("hide");
                    loadCategories();
                    alert("Category deleted successfully!");
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error deleting category:', error);
                alert("Error deleting category. Please try again.");
            }
        });
    });

    // Initial load
    loadCategories();
});