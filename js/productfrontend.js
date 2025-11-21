$(document).ready(function () {
    // DOM Elements
    const productList = $('#product-list');
    const paginationLinks = $('#pagination-links');
    
    // Filter Inputs
    const searchInput = $('#searchQuery');
    const locationFilter = $('#locationFilter');
    const priceFilter = $('#priceFilter');
    // Note: Category filters are targeted dynamically by class .category-checkbox
    
    // State Variables
    let currentPage = 1;
    let currentQuery = searchInput.val() || ''; // Grab initial value if set by PHP
    
    // --- Core Product Fetching Function ---
    function fetchProducts() {
        // Show Loading State using the new CSS structure
        productList.html(`
            <div style="grid-column: 1/-1; text-align: center; padding: 4rem; color: var(--gray-text);">
                <i class="fas fa-spinner fa-spin fa-2x"></i><br>
                <span style="margin-top:10px; display:block;">Finding the best flavors for you...</span>
            </div>
        `);
        paginationLinks.empty();

        // 1. Gather Category IDs (Handle multiple checkboxes)
        const selectedCategories = [];
        $('.category-checkbox:checked').each(function() {
            selectedCategories.push($(this).val());
        });

        // 2. Build Parameters Object
        const params = {
            page: currentPage,
            query: currentQuery,
            // Send first category as cat_id for backward compatibility, 
            // or send 'categories' array if backend supports it. 
            // Here we send a comma-separated string if multiple, or single ID.
            cat_id: selectedCategories.length > 0 ? selectedCategories.join(',') : 0,
            location: locationFilter.val(),     // New Filter
            price_range: priceFilter.val(),     // New Filter
            // brand_id: $('#brandFilter').val() // Optional: If you use the hidden brand filter
        };

        // 3. AJAX Request
        $.ajax({
            url: '../actions/product_actions.php', // Ensure this path is correct relative to where the JS is loaded
            method: 'GET',
            dataType: 'json',
            data: params,
            success: function (response) {
                productList.empty();
                
                // Handle Error or Empty State
                if (response.status !== 'success' || !response.data || response.data.length === 0) {
                    productList.html(`
                        <div style="grid-column: 1/-1; text-align: center; padding: 4rem; background:white; border-radius:12px; border:1px solid var(--border-color);">
                            <i class="fas fa-utensils" style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                            <h3>No vendors found</h3>
                            <p style="color: var(--gray-text);">Try adjusting your filters or search query.</p>
                            <button id="clearEmptyState" class="btn btn-outline" style="margin-top:1rem;">Clear Filters</button>
                        </div>
                    `);
                    
                    $('#clearEmptyState').click(resetFilters);
                    return;
                }

                // Render Products using New CSS Card Structure
                response.data.forEach(p => {
                    // Image Fallback Logic
                    const imagePath = p.product_image ? `../${p.product_image}` : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60';
                    
                    // Calculate Star Rating (Random/Placeholder or real if available)
                    const rating = p.rating || (Math.random() * (5.0 - 3.5) + 3.5).toFixed(1);
                    
                    // Price Symbols
                    const priceVal = parseFloat(p.product_price);
                    let priceSymbols = '₵';
                    if(priceVal > 50) priceSymbols = '₵₵';
                    if(priceVal > 150) priceSymbols = '₵₵₵';

                    // HTML Template
                    const productHTML = `
                        <div class="card">
                            <div class="card-image-wrapper">
                                <img src="${imagePath}" alt="${p.product_title}">
                                <div class="badge-rating"><span class="star-icon">★</span> ${rating}</div>
                            </div>
                            
                            <div class="card-body">
                                <h3 class="card-title">${p.product_title}</h3>
                                <p class="card-desc">${p.product_desc || 'Experience authentic flavors with our special dishes.'}</p>
                                
                                <div class="card-meta">
                                    <span><i class="fas fa-map-marker-alt"></i> ${p.location || 'Accra'}</span> <!-- Backend needs to send location -->
                                    <span class="price-highlight">${priceSymbols}</span>
                                </div>
                                
                                <div class="tag-container">
                                    <span class="tag">${p.cat_name}</span>
                                    ${p.brand_name ? `<span class="tag" style="background:#f3f4f6; color:#4b5563;">${p.brand_name}</span>` : ''}
                                </div>
                                
                                <div style="margin-top: 1rem; display:flex; gap:0.5rem;">
                                    <a href="single_product.php?id=${p.product_id}" class="btn btn-primary" style="flex:1; font-size:0.85rem;">View Profile</a>
                                    <!-- Optional Cart Button -->
                                    <!-- <button class="btn btn-outline add-to-cart-btn" data-id="${p.product_id}" style="padding:0.6rem;"><i class="fas fa-plus"></i></button> -->
                                </div>
                            </div>
                        </div>
                    `;
                    
                    productList.append(productHTML);
                });

                // Render Pagination
                renderPagination(response.pages, response.currentPage);
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                productList.html(`
                    <div style="grid-column: 1/-1; text-align: center; color: var(--primary-orange); padding: 2rem;">
                        <i class="fas fa-exclamation-circle"></i> Failed to load vendors. Please try again.
                    </div>
                `);
            }
        });
    }

    // --- Pagination Renderer ---
    function renderPagination(totalPages, currentPage) {
        paginationLinks.empty();
        if (totalPages <= 1) return;

        // Previous
        paginationLinks.append(`
            <li class="${currentPage === 1 ? 'disabled' : ''}">
                <button class="pagination-link page-nav-link" data-page="${currentPage - 1}" ${currentPage === 1 ? 'disabled' : ''}>&laquo;</button>
            </li>
        `);

        // Pages
        for (let i = 1; i <= totalPages; i++) {
            paginationLinks.append(`
                <li>
                    <button class="pagination-link page-nav-link ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>
                </li>
            `);
        }

        // Next
        paginationLinks.append(`
            <li class="${currentPage === totalPages ? 'disabled' : ''}">
                <button class="pagination-link page-nav-link" data-page="${currentPage + 1}" ${currentPage === totalPages ? 'disabled' : ''}>&raquo;</button>
            </li>
        `);
    }

    // --- Event Handlers ---

    // 1. Search Input (Debounce)
    let searchTimer;
    searchInput.on('keyup', function() {
        clearTimeout(searchTimer);
        currentQuery = $(this).val();
        searchTimer = setTimeout(() => {
            currentPage = 1;
            fetchProducts();
        }, 400);
    });

    // 2. Filter Changes (Immediate Update)
    locationFilter.on('change', function() { currentPage = 1; fetchProducts(); });
    priceFilter.on('change', function() { currentPage = 1; fetchProducts(); });
    $(document).on('change', '.category-checkbox', function() { currentPage = 1; fetchProducts(); });

    // 3. Reset Filters
    function resetFilters() {
        searchInput.val('');
        locationFilter.val('');
        priceFilter.val('');
        $('.category-checkbox').prop('checked', false);
        
        currentQuery = '';
        currentPage = 1;
        fetchProducts();
    }
    $('#resetFilters').on('click', resetFilters);

    // 4. Pagination Click
    $(document).on('click', '.page-nav-link', function(e) {
        e.preventDefault();
        const newPage = parseInt($(this).data('page'));
        if (newPage > 0) {
            currentPage = newPage;
            $('html, body').animate({ scrollTop: 0 }, 'smooth');
            fetchProducts();
        }
    });

    // Initial Load
    fetchProducts();
});