// js/discovery.js
$(document).ready(function() {
    
    // Initial Fetch
    fetchProducts();

    // Event Listeners
    $('#searchInput').on('keyup', debounce(fetchProducts, 500));
    $('#locationFilter').on('change', fetchProducts);
    $('#priceRange').on('input', function() {
        $('#priceValue').text('₵' + $(this).val());
        debounce(fetchProducts, 300)(); // Immediate visual update, delayed fetch
    });
    $('#priceRange').on('change', fetchProducts); // Fetch on release
    $('.cat-checkbox').on('change', fetchProducts);
    
    $('#reset-filters').on('click', function(e) {
        e.preventDefault();
        $('#searchInput').val('');
        $('#locationFilter').val('');
        $('#priceRange').val(500);
        $('#priceValue').text('₵500');
        $('.cat-checkbox').prop('checked', false);
        fetchProducts();
    });

    // Main Fetch Function
    function fetchProducts() {
        const query = $('#searchInput').val();
        const location = $('#locationFilter').val();
        const maxPrice = $('#priceRange').val();
        
        const categories = [];
        $('.cat-checkbox:checked').each(function() {
            categories.push($(this).val());
        });

        // Show Loading
        $('#products-container').html('<div style="grid-column:1/-1; text-align:center; padding:3rem;"><i class="fas fa-spinner fa-spin fa-2x" style="color:#ea580c;"></i></div>');

        $.ajax({
            url: '../actions/search_products.php',
            method: 'POST',
            dataType: 'json',
            data: {
                query: query,
                location: location,
                max_price: maxPrice,
                categories: categories
            },
            success: function(response) {
                const container = $('#products-container');
                container.empty();

                if (response.status === 'success' && response.data.length > 0) {
                    response.data.forEach(item => {
                        // Use uploaded image or placeholder
                        const imgPath = item.product_image ? '../'+item.product_image : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60';
                        
                        const card = `
                            <div class="product-card">
                                <img src="${imgPath}" class="card-img" alt="${item.product_title}">
                                <div class="card-content">
                                    <h3 class="card-title">${item.product_title}</h3>
                                    <div class="card-vendor">
                                        <i class="fas fa-store"></i> ${item.business_name}
                                    </div>
                                    <div class="card-vendor">
                                        <i class="fas fa-map-marker-alt"></i> ${item.customer_city}
                                    </div>
                                    <div style="margin-top:0.5rem; font-size:0.8rem; color:gray;">
                                        ${item.product_desc.substring(0, 60)}...
                                    </div>
                                    <div class="card-price">₵${parseFloat(item.product_price).toFixed(2)}</div>
                                    
                                    <a href="single_vendor.php?id=${item.product_id}" class="btn btn-primary" style="text-align:center; margin-top:1rem; font-size:0.9rem;">View Item</a>
                                </div>
                            </div>
                        `;
                        container.append(card);
                    });
                } else {
                    container.html(`
                        <div style="grid-column:1/-1; text-align:center; padding:3rem;">
                            <i class="fas fa-search" style="font-size:2rem; color:#ccc; margin-bottom:1rem;"></i>
                            <h3>No results found</h3>
                            <p style="color:gray;">Try adjusting your filters.</p>
                        </div>
                    `);
                }
            },
            error: function() {
                $('#products-container').html('<p style="color:red; text-align:center;">Failed to load products.</p>');
            }
        });
    }

    // Debounce Helper (prevents too many requests while typing)
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }
});