<?php
// File: includes/modals/product-detail-modal.php
?>
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Detail Produk eSIM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="qr-code-container text-center mb-4">
                            <img id="modalQrCode" src="" alt="QR Code" class="img-fluid" style="max-height: 200px;">
                            <div class="mt-2">
                                <a href="#" id="downloadQr" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Download QR
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 id="modalProductName"></h4>
                        <p class="text-muted" id="modalProductRegion"></p>
                        <div class="mb-3">
                            <span class="badge bg-primary" id="modalProductQuota"></span>
                            <span class="badge bg-success ms-2" id="modalProductValidity"></span>
                        </div>
                        <p id="modalProductDescription"></p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-primary" id="modalProductPrice"></h5>
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary minus-btn" type="button">-</button>
                                <input type="number" class="form-control text-center quantity-input" value="1" min="1">
                                <button class="btn btn-outline-secondary plus-btn" type="button">+</button>
                            </div>
                        </div>
                        <a href="#" id="modalBuyNow" class="btn btn-primary w-100">
                            <i class="fas fa-shopping-cart"></i> Beli Sekarang
                        </a>
                        <div class="mt-3">
                            <a href="#" id="modalUsageLink" target="_blank" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-link"></i> Panduan Penggunaan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle modal show event
    $('#productModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var productId = button.data('id');
        
        // AJAX request to get product details
        $.ajax({
            url: '/api/get-product.php',
            method: 'POST',
            data: {id: productId},
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    var product = response.product;
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
            }
        });
    });

    // Quantity controls
    $('.plus-btn').click(function() {
        var input = $(this).siblings('.quantity-input');
        var value = parseInt(input.val());
        input.val(value + 1);
    });

    $('.minus-btn').click(function() {
        var input = $(this).siblings('.quantity-input');
        var value = parseInt(input.val());
        if (value > 1) {
            input.val(value - 1);
        }
    });
});
</script>