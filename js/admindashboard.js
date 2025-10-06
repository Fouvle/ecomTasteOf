$(document).ready(function() {
    const messageBox = $("#messageBox");

    function showMessage(type, text) {
        messageBox
            .removeClass("d-none alert-success alert-danger")
            .addClass(type === "success" ? "alert-success" : "alert-danger")
            .text(text)
            .fadeIn();

        setTimeout(() => messageBox.fadeOut(), 3000);
    }

    // Approve Category
    $(document).on("click", ".approve-btn", function() {
        const categoryId = $(this).data("id");
        $.ajax({
            url: "../Actions/approve_category_action.php",
            type: "POST",
            data: { cat_id: categoryId },
            dataType: "json",
            success: function(response) {
                showMessage(response.status, response.message);
                if (response.status === "success") location.reload();
            },
            error: function(xhr, status, error) {
                showMessage("error", "Error approving category: " + error);
            }
        });
    });

    // Reject Category
    $(document).on("click", ".reject-btn", function() {
        const categoryId = $(this).data("id");
        $.ajax({
            url: "../Actions/reject_category_action.php",
            type: "POST",
            data: { cat_id: categoryId },
            dataType: "json",
            success: function(response) {
                showMessage(response.status, response.message);
                if (response.status === "success") location.reload();
            },
            error: function(xhr, status, error) {
                showMessage("error", "Error rejecting category: " + error);
            }
        });
    });
});
