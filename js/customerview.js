$(document).ready(function() {
    let selectedCategoryId = null;

    //  Fetch and display all user categories
    function loadCategories() {
        $.ajax({
            url: "../actions/fetch_category_action.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                const tbody = $("#categoryTable tbody");
                tbody.empty();

                if (response.length === 0) {
                    tbody.append(`<tr><td colspan="4" class="text-center">No categories found.</td></tr>`);
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
            error: function() {
                alert("Failed to fetch categories.");
            }
        });
    }

    //  Handle adding a new category
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
                alert(response.message);
                if (response.status === "success") {
                    $("#newCategoryName").val("");
                    loadCategories();
                }
            },
            error: function() {
                alert("Error adding category.");
            }
        });
    });

    //  Open modal for edit
    $(document).on("click", ".view-category-btn", function() {
        selectedCategoryId = $(this).data("id");
        $("#catId").val($(this).data("id"));
        $("#catName").val($(this).data("name"));

        const modal = new bootstrap.Modal(document.getElementById("categoryModal"));
        modal.show();
    });

    //  Update category
    $("#saveCategoryBtn").click(function() {
        $.ajax({
            url: "../actions/update_category_action.php",
            type: "POST",
            data: $("#editCategoryForm").serialize(),
            dataType: "json",
            success: function(response) {
                alert(response.message);
                if (response.status === "success") {
                    $("#categoryModal").modal("hide");
                    loadCategories();
                }
            },
            error: function() {
                alert("Error updating category.");
            }
        });
    });

    //  Delete category
    $("#deleteCategoryBtn").click(function() {
        if (!confirm("Are you sure you want to delete this category?")) return;

        $.ajax({
            url: "../actions/delete_category_action.php",
            type: "POST",
            data: { category_id: selectedCategoryId },
            dataType: "json",
            success: function(response) {
                alert(response.message);
                if (response.status === "success") {
                    $("#categoryModal").modal("hide");
                    loadCategories();
                }
            },
            error: function() {
                alert("Error deleting category.");
            }
        });
    });

    // Initial load
    loadCategories();
});
