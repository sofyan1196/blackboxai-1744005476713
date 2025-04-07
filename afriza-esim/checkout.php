<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/classes/Product.php';
require_once __DIR__ . '/includes/classes/Order.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$product = new Product($pdo);
$order = new Order($pdo);

// Get product details
$productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$productDetails = $product->getProduct($productId);

if (!$productDetails) {
    header('Location: products.php');
    exit;
}

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $orderId = $order->createOrder(
            $_SESSION['user_id'],
            $productId,
            $_POST['quantity'],
            $_POST['payment_method']
        );
        
        header('Location: payment.php?order_id=' . $orderId);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="mb-4">Checkout</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Ringkasan Pesanan</h5>
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <img src="assets/images/flags/<?= strtolower($productDetails['region']) ?>.png" 
                                 alt="<?= $productDetails['region'] ?>" class="img-fluid">
                        </div>
                        <div class="col-md-6">
                            <h6><?= htmlspecialchars($productDetails['name']) ?></h6>
                            <p class="text-muted mb-1"><?= htmlspecialchars($productDetails['region']) ?></p>
                            <p class="mb-1"><?= htmlspecialchars($productDetails['quota']) ?></p>
                            <p>Masa aktif: <?= htmlspecialchars($productDetails['validity']) ?> hari</p>
                        </div>
                        <div class="col-md-3 text-end">
                            <h5 class="text-primary">$<?= number_format($productDetails['price'], 2) ?></h5>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Detail Pembelian</h5>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah</label>
                            <div class="input-group" style="width: 150px;">
                                <button class="btn btn-outline-secondary minus-btn" type="button">-</button>
                                <input type="number" id="quantity" name="quantity" class="form-control text-center" 
                                       value="1" min="1" required>
                                <button class="btn btn-outline-secondary plus-btn" type="button">+</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Pembayaran</label>
                            <h4 id="totalAmount" class="text-primary">
                                $<?= number_format($productDetails['price'], 2) ?>
                            </h4>
                            <input type="hidden" id="productPrice" value="<?= $productDetails['price'] ?>">
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="" selected disabled>Pilih metode...</option>
                                <option value="bca">Transfer Bank - BCA</option>
                                <option value="bni">Transfer Bank - BNI</option>
                                <option value="bri">Transfer Bank - BRI</option>
                                <option value="mandiri">Transfer Bank - Mandiri</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Quantity controls
$('.plus-btn').click(function() {
    const input = $(this).siblings('.quantity-input');
    input.val(parseInt(input.val()) + 1);
    calculateTotal();
});

$('.minus-btn').click(function() {
    const input = $(this).siblings('.quantity-input');
    if (parseInt(input.val()) > 1) {
        input.val(parseInt(input.val()) - 1);
        calculateTotal();
    }
});

// Calculate total amount
function calculateTotal() {
    const quantity = parseInt($('#quantity').val());
    const price = parseFloat($('#productPrice').val());
    const total = (quantity * price).toFixed(2);
    $('#totalAmount').text('$' + total);
}

// Initial calculation
$(document).ready(function() {
    calculateTotal();
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>