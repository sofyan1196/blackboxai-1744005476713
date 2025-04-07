// Initialize tooltips
$(function () {
    $('[data-bs-toggle="tooltip"]').tooltip()
});

// Handle quantity buttons
$(document).on('click', '.plus-btn', function() {
    const input = $(this).siblings('.quantity-input');
    input.val(parseInt(input.val()) + 1);
});

$(document).on('click', '.minus-btn', function() {
    const input = $(this).siblings('.quantity-input');
    if (parseInt(input.val()) > 1) {
        input.val(parseInt(input.val()) - 1);
    }
});

// Add to cart functionality
$(document).on('click', '.add-to-cart', function(e) {
    e.preventDefault();
    const productId = $(this).data('id');
    const quantity = $(this).closest('.card').find('.quantity-input').val();
    
    $.ajax({
        url: '/api/add-to-cart.php',
        method: 'POST',
        data: {
            product_id: productId,
            quantity: quantity
        },
        success: function(response) {
            if (response.success) {
                // Update cart count
                $('#cart-count').text(response.cart_count);
                
                // Show success message
                const toast = new bootstrap.Toast(document.getElementById('cartToast'));
                toast.show();
            }
        }
    });
});

// Floating nav active state
$(window).scroll(function() {
    const scroll = $(window).scrollTop();
    if (scroll >= 100) {
        $('.navbar.fixed-bottom').addClass('scrolled');
    } else {
        $('.navbar.fixed-bottom').removeClass('scrolled');
    }
});

// Product modal handler
$(document).ready(function() {
    $('#productModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const productId = button.data('id');
        
        $.get('/api/get-product.php', {id: productId}, function(data) {
            if (data.success) {
                const product = data.product;
                $('#modalProductName').text(product.name);
                $('#modalProductRegion').text(product.region);
                $('#modalProductDescription').text(product.description);
                $('#modalProductPrice').text('$' + parseFloat(product.price).toFixed(2));
                $('#modalProductQuota').text(product.quota);
                $('#modalProductValidity').text(product.validity + ' Hari');
                $('#modalQrCode').attr('src', product.qr_code);
                $('#downloadQr').attr('href', product.qr_code);
                $('#modalUsageLink').attr('href', product.usage_link);
                $('#modalBuyNow').attr('href', 'checkout.php?product_id=' + productId);
            }
        });
    });
});