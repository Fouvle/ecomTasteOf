$(document).ready(function() {
    
    // Initial Fetch
    fetchVendors();

    function fetchVendors() {
        $.ajax({
            url: '../actions/fetch_all_vendors.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const container = $('#vendor-container');
                container.empty();

                if (response.status === 'success' && response.data.length > 0) {
                    response.data.forEach(vendor => {
                        // Image Handling: Use vendor's specific image or a generic food placeholder
                        const profileImg = vendor.customer_image ? '../'+vendor.customer_image : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(vendor.business_name) + '&background=ea580c&color=fff';
                        
                        // Placeholder cover image (since we don't have a cover image in DB, we use a nice stock photo)
                        const coverImg = 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';

                        const card = `
                            <div class="vendor-card">
                                <div class="vendor-img-wrapper">
                                    <img src="${coverImg}" class="vendor-img" alt="Cover Image">
                                    <img src="${profileImg}" class="vendor-logo" alt="${vendor.business_name}">
                                </div>
                                <div class="vendor-content">
                                    <div class="vendor-name">${vendor.business_name}</div>
                                    <div class="vendor-meta">
                                        <i class="fas fa-map-marker-alt"></i> ${vendor.customer_city || 'Ghana'}
                                    </div>
                                    <div class="vendor-desc">
                                        ${vendor.business_description || 'Authentic local dishes and experiences.'}
                                    </div>
                                    <div class="action-row">
                                        <span style="font-size:0.85rem; color:#ea580c; font-weight:bold;">Verified Vendor <i class="fas fa-check-circle"></i></span>
                                        <!-- Link to single_vendor page. 
                                             Note: You may need to ensure ../views/single_vendor.php handles 'vendor_id' lookups 
                                             or links via product_id if that is how your system is set up. -->
                                        <a href="single_vendor.php?vendor_id=${vendor.vendor_id}" class="btn btn-outline" style="padding:0.4rem 1rem; font-size:0.85rem;">View Profile</a>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.append(card);
                    });
                } else {
                    container.html(`
                        <div style="grid-column:1/-1; text-align:center; padding:3rem;">
                            <h3>No vendors found</h3>
                            <p style="color:gray;">Check back later for new additions.</p>
                        </div>
                    `);
                }
            },
            error: function() {
                $('#vendor-container').html('<p style="color:red; text-align:center;">Failed to load vendors.</p>');
            }
        });
    }
});