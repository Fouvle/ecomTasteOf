$(document).ready(function () {
    const formTitle = $('#form-title');
    const submitBtn = $('#submitBtn');
    const cancelEditBtn = $('#cancelEditBtn');
    const productImageInput = $('#productImage');

    function showMessage(icon, title, text) {
        Swal.fire({ icon, title, text });
    }

    // --- Core Product Retrieval ---
    function loadProducts() {
        $.ajax({
            url: "../actions/fetch_product_action.php",
            method: "GET",
            dataType: "json",
            success: function (response) {
                const tbody = $("#productTable tbody");
                tbody.empty();

                if (response.status === 'error') {
                    tbody.append(`<tr><td colspan="6" class="text-center text-danger">${response.message}</td></tr>`);
                    return;
                }

                if (response.data.length === 0) {
                    tbody.append(`<tr><td colspan="6" class="text-center">No products found. Add one above!</td></tr>`);
                    return;
                }

                response.data.forEach(p => {
                    // Assuming the stored path is relative to the project root (e.g., uploads/u40/p6/image.png)
                    const imagePath = p.product_image ? `../${p.product_image}` : '../images/placeholder.png'; 
                    tbody.append(`
                        <tr>
                            <td><img src="${imagePath}" class="product-img" alt="${p.product_title}"></td>
                            <td>${p.product_title}</td>
                            <td>GHS ${parseFloat(p.product_price).toFixed(2)}</td>
                            <td>${p.cat_name}</td>
                            <td>${p.brand_name}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-product-btn" 
                                        data-id="${p.product_id}" 
                                        data-cat="${p.product_cat}"
                                        data-brand="${p.product_brand}"
                                        data-title="${p.product_title}"
                                        data-price="${p.product_price}"
                                        data-desc="${p.product_desc}"
                                        data-keywords="${p.product_keywords}"
                                        data-img="${p.product_image}">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: function () {
                $("#productTable tbody").html('<tr><td colspan="6" class="text-center text-danger">Failed to load products.</td></tr>');
            }
        });
    }

    // --- Image Upload Logic (Called after product data is successfully saved) ---
    function uploadProductImage(productId, fileInput) {
        if (fileInput.files.length === 0) {
            // No new image selected on update
            return Promise.resolve({ status: 'success', message: 'No new image provided.' });
        }
        
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('product_image', fileInput.files[0]);

        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../actions/upload_product_image_action.php',
                type: 'POST',
                data: formData,
                contentType: false, 
                processData: false, 
                dataType: 'json',
                success: resolve,
                error: reject
            });
        });
    }

    // --- CREATE / UPDATE Form Submission Handler ---
    $('#productForm').submit(async function (e) {
        e.preventDefault();

        const isEdit = $('#productId').val() !== "";
        let url = isEdit ? '../actions/update_product_action.php' : '../actions/add_product_action.php';
        
        // Validation check for image on CREATE (Image is required for a new product)
        if (!isEdit && productImageInput[0].files.length === 0) {
            showMessage('error', 'Required Field', 'Please select a product image for a new product.');
            return;
        }

        try {
            // 1. Submit non-file form data first
            let formData = $(this).serializeArray().reduce((obj, item) => {
                obj[item.name] = item.value;
                return obj;
            }, {});

            const dataResponse = await $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: formData
            });

            if (dataResponse.status !== 'success') {
                showMessage('error', 'Data Save Failed', dataResponse.message);
                return;
            }

            // Get the product ID (newly created or existing)
            const productId = dataResponse.product_id;
            
            // 2. Upload image if a file was selected/required
            if (productImageInput[0].files.length > 0 || !isEdit) {
                const imageResponse = await uploadProductImage(productId, productImageInput[0]);

                if (imageResponse.status !== 'success') {
                     // Show warning if image fails but product data saved successfully
                     console.error("Image Upload Error:", imageResponse.message);
                     showMessage('warning', 'Partial Success', dataResponse.message + " (Image update failed: " + imageResponse.message + ")");
                     
                } else {
                    showMessage('success', 'Success', dataResponse.message);
                }
            } else {
                 showMessage('success', 'Success', dataResponse.message);
            }

            // 3. Reset form and reload products
            resetForm();
            loadProducts();

        } catch (error) {
            console.error('AJAX Error:', error);
            showMessage('error', 'System Error', 'An error occurred. Check the console.');
        }
    });
    
    // --- Edit Button Handler (Loads data into the form) ---
    $(document).on("click", ".edit-product-btn", function() {
        const data = $(this).data();
        
        // 1. Set hidden ID and update form fields
        $('#productId').val(data.id);
        $('#productTitle').val(data.title);
        $('#productPrice').val(data.price);
        $('#productCategory').val(data.cat);
        $('#productBrand').val(data.brand);
        $('#productDescription').val(data.desc);
        $('#productKeywords').val(data.keywords);
        
        // 2. Update UI for Edit Mode
        formTitle.text('Edit Product (ID: ' + data.id + ')');
        submitBtn.text('Save Changes');
        cancelEditBtn.removeClass('d-none');
        
        // 3. Update Image Hint (file input is optional on edit)
        productImageInput.removeAttr('required');
        $('#currentImageHint').text(`Current Image: ${data.img ? data.img.split('/').pop() : 'None'}. Select a file to replace it.`);
        
        // Scroll to the top to show the form
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    });

    // --- Reset Form Function ---
    function resetForm() {
        $('#productForm')[0].reset();
        $('#productId').val('');
        formTitle.text('Add New Product');
        submitBtn.text('Add Product');
        cancelEditBtn.addClass('d-none');
        productImageInput.attr('required', 'required');
        $('#currentImageHint').text('Existing image will be replaced on update.');
    }
    
    // --- Cancel Edit Button ---
    cancelEditBtn.click(resetForm);

    // Initial load
    loadProducts();
});