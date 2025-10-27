$(document).ready(function () {
    const productList = $('#product-list');
    const paginationLinks = $('#pagination-links');
    const searchInput = $('#searchQuery');
    const categoryFilter = $('#categoryFilter');
    const brandFilter = $('#brandFilter');

    let currentPage = 1;
    let currentQuery = '';
    let currentCatId = 0;
    let currentBrandId = 0;

    // --- Core Product Fetching Function ---
    function fetchProducts() {
        productList.html('<div class="col-12 text-center text-muted py-5">Loading experiences...</div>');
        paginationLinks.empty();

        const params = {
            page: currentPage,
            query: currentQuery,
            cat_id: currentCatId,
            brand_id: currentBrandId
        };

        $.ajax({
            url: '../actions/product_actions.php',
            method: 'GET',
            dataType: 'json',
            data: params,
            success: function (response) {
                productList.empty();
                
                if (response.status !== 'success' || response.data.length === 0) {
                    productList.html('<div class="col-12 text-center text-muted py-5">No products found matching your criteria.</div>');
                    return;
                }

                // Render Products
                response.data.forEach(p => {
                    const imagePath = p.product_image ? `../${p.product_image}` : '../images/placeholder.png';
                    productList.append(`
                        <div class="col">
                            <div class="card product-card h-100">
                                <a href="single_product.php?id=${p.product_id}">
                                    <img src="${imagePath}" class="card-img-top product-image" alt="${p.product_title}">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">${p.product_title}</h5>
                                    <p class="card-text text-muted small mb-1">
                                        ${p.cat_name} | ${p.brand_name}
                                    </p>
                                    <p class="price mt-auto mb-2">GHS ${parseFloat(p.product_price).toFixed(2)}</p>
                                    <a href="single_product.php?id=${p.product_id}" class="btn btn-sm btn-outline-primary mb-2">View Details</a>
                                    <button class="btn btn-sm btn-success add-to-cart-btn" data-id="${p.product_id}">
                                        Add to Cart (Placeholder)
                                    </button>
                                </div>
                            </div>
                        </div>
                    `);
                });

                // Render Pagination
                renderPagination(response.pages, response.currentPage);
            },
            error: function () {
                productList.html('<div class="col-12 text-center text-danger py-5">Failed to fetch products. Please try again.</div>');
            }
        });
    }

    // --- Pagination Renderer ---
    function renderPagination(totalPages, currentPage) {
        paginationLinks.empty();
        if (totalPages <= 1) return;

        // Previous button
        paginationLinks.append(`
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link page-nav-link" href="#" data-page="${currentPage - 1}">Previous</a>
            </li>
        `);

        // Page buttons (simplified for brevity)
        for (let i = 1; i <= totalPages; i++) {
            paginationLinks.append(`
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link page-nav-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }

        // Next button
        paginationLinks.append(`
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link page-nav-link" href="#" data-page="${currentPage + 1}">Next</a>
            </li>
        `);
    }

    // --- Event Handlers ---
    
    // Pagination click handler
    $(document).on('click', '.page-nav-link', function(e) {
        e.preventDefault();
        const newPage = parseInt($(this).data('page'));
        if (newPage > 0 && newPage <= parseInt(paginationLinks.find('.page-item:last-child .page-nav-link').data('page'))) {
             currentPage = newPage;
             fetchProducts();
        }
    });

    // Search and Filter Change Handler (for instant updates)
    function handleFilterChange() {
        currentPage = 1;
        currentQuery = searchInput.val();
        currentCatId = parseInt(categoryFilter.val());
        currentBrandId = parseInt(brandFilter.val());

        // Clear other filters if search query is active
        if (currentQuery) {
            categoryFilter.val(0);
            brandFilter.val(0);
            currentCatId = 0;
            currentBrandId = 0;
        } else {
             // Clear other filters if a specific filter is selected
             if (currentCatId > 0) {
                brandFilter.val(0);
                currentBrandId = 0;
            } else if (currentBrandId > 0) {
                categoryFilter.val(0);
                currentCatId = 0;
            }
        }

        fetchProducts();
    }
    
    // Bind search input (debounce for performance)
    let searchTimer;
    searchInput.on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(handleFilterChange, 500);
    });

    // Bind category/brand dropdowns
    categoryFilter.on('change', handleFilterChange);
    brandFilter.on('change', handleFilterChange);
    
    // Reset Filters button
    $('#resetFilters').on('click', function() {
        searchInput.val('');
        categoryFilter.val(0);
        brandFilter.val(0);
        handleFilterChange();
    });

    // Initial load
    fetchProducts();
});