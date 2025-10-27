$(document).ready(function() {
    let selectedBrandId = null;

    //Helper function to display messages
    function displayMessage(icon, title, text) {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonText: 'OK'
        });
    }

   // --- 1. RETRIEVE: Fetch and display all user brands (grouped by category) ---
    function loadBrands() {
        $.ajax({
            url: "../actions/fetch_brand_action.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                const accordion = $("#brandAccordion");
                accordion.empty();

                if (response.status === 'error') {
                    accordion.append(`<p class="text-center text-danger">${response.message}</p>`);
                    return;
                }

                const groupedBrands = response.data;
                if (Object.keys(groupedBrands).length === 0) {
                    accordion.append(`<p class="text-center text-muted">No brands found. Add your first brand above!</p>`);
                    return;
                }
                
                let accordionHtml = '';

                // Iterate through categories (groups)
                for (const catName in groupedBrands) {
                    const brands = groupedBrands[catName];
                    const catId = brands[0] ? brands[0].cat_id : 'default';
                    const collapseId = `collapse-${catId}-${catName.replace(/\s+/g, '-')}`;

                    let brandListHtml = brands.map(brand => `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${brand.brand_name}</span>
                            <div>
                                <button class="btn btn-sm btn-outline-info edit-brand-btn" 
                                        data-id="${brand.brand_id}" 
                                        data-name="${brand.brand_name}">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-brand-btn" 
                                        data-id="${brand.brand_id}">
                                    Delete
                                </button>
                            </div>
                        </li>
                    `).join('');

                    accordionHtml += `
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                                    ${catName} (${brands.length} Brands)
                                </button>
                            </h2>
                            <div id="${collapseId}" class="accordion-collapse collapse" data-bs-parent="#brandAccordion">
                                <div class="accordion-body p-0">
                                    <ul class="list-group list-group-flush">
                                        ${brandListHtml}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `;
                }

                accordion.html(accordionHtml);

            },
            error: function() {
                accordion.html('<p class="text-center text-danger">Failed to load brands. Please check your network.</p>');
            }
        });
    }

    // --- 2. CREATE: Handle adding a new brand ---
    $("#addBrandForm").submit(function(e) {
        e.preventDefault();
        const brandName = $("#newBrandName").val().trim();
        const catId = $("#brandCategory").val();
        
        if (brandName === "" || catId === "") {
            showMessage('error', 'Oops...', 'Please enter a brand name and select a category.');
            return;
        }

        $.ajax({
            url: "../actions/add_brand_action.php",
            type: "POST",
            data: { brand_name: brandName, cat_id: catId },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $("#newBrandName").val("");
                    $("#brandCategory").val("");
                    loadBrands();
                    showMessage('success', 'Success', response.message);
                } else {
                    showMessage('error', 'Error', response.message);
                }
            },
            error: function() {
                showMessage('error', 'Oops...', 'Error adding brand. Please try again.');
            }
        });
    });
    
    // --- 3. UPDATE: Open modal and save changes ---
    $(document).on("click", ".edit-brand-btn", function() {
        selectedBrandId = $(this).data("id");
        $("#brandId").val(selectedBrandId);
        $("#brandName").val($(this).data("name"));

        const modal = new bootstrap.Modal(document.getElementById("brandModal"));
        modal.show();
    });

    // Save Brand changes
    $("#saveBrandBtn").click(function() {
        const brandName = $("#brandName").val().trim();
        
        if (!brandName) {
            showMessage('error', 'Oops...', 'Please enter a brand name.');
            return;
        }

        $.ajax({
            url: "../actions/update_brand_action.php",
            type: "POST",
            data: $("#editBrandForm").serialize(),
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $("#brandModal").modal("hide");
                    loadBrands();
                    showMessage('success', 'Success', response.message);
                } else {
                    showMessage('error', 'Error', response.message);
                }
            },
            error: function() {
                showMessage('error', 'Oops...', 'Error updating brand. Please try again.');
            }
        });
    });

    // --- 4. DELETE: Delete brand ---
    $(document).on("click", ".delete-brand-btn", function() {
        const brandId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This brand will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../actions/delete_brand_action.php",
                    type: "POST",
                    data: { brand_id: brandId },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            loadBrands();
                            Swal.fire('Deleted!', response.message, 'success');
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

    // Initial load
    loadBrands();
});