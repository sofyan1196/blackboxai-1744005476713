<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/classes/Order.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$order = new Order($pdo);
$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$orderDetails = $order->getOrder($orderId);

// Validate order ownership
if (!$orderDetails || $orderDetails['user_id'] != $_SESSION['user_id']) {
    header('Location: dashboard.php');
    exit;
}

// Handle payment proof upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['payment_proof'])) {
    try {
        // Validate file
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($_FILES['payment_proof']['type'], $allowedTypes)) {
            throw new Exception('Hanya file JPG, PNG, atau PDF yang diperbolehkan');
        }
        
        if ($_FILES['payment_proof']['size'] > $maxSize) {
            throw new Exception('Ukuran file maksimal 2MB');
        }

        // Generate unique filename
        $extension = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
        $filename = 'payment_' . $orderId . '_' . time() . '.' . $extension;
        $targetPath = __DIR__ . '/../uploads/payments/' . $filename;

        // Move uploaded file
        if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $targetPath)) {
            // Update payment record
            $stmt = $this->pdo->prepare("
                UPDATE payments 
                SET payment_proof = ?, status = 'pending_verification' 
                WHERE order_id = ?
            ");
            $stmt->execute([$filename, $orderId]);
            
            // Update order status
            $order->updateOrderStatus($orderId, 'pending_verification');
            
            $success = 'Bukti pembayaran berhasil diupload. Admin akan memverifikasi pembayaran Anda.';
        } else {
            throw new Exception('Gagal mengupload file. Silakan coba lagi.');
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="mb-4">Pembayaran</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Detail Pesanan</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>ID Pesanan:</strong> <?= $orderId ?></p>
                            <p class="mb-1"><strong>Produk:</strong> <?= htmlspecialchars($orderDetails['product_name']) ?></p>
                            <p class="mb-1"><strong>Region:</strong> <?= htmlspecialchars($orderDetails['region']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Jumlah:</strong> <?= $orderDetails['quantity'] ?></p>
                            <p class="mb-1"><strong>Total:</strong> $<?= number_format($orderDetails['total_amount'], 2) ?></p>
                            <p class="mb-1"><strong>Status:</strong> 
                                <span class="badge bg-<?= 
                                    $orderDetails['payment_status'] === 'paid' ? 'success' : 
                                    ($orderDetails['payment_status'] === 'pending' ? 'warning' : 'info') 
                                ?>">
                                    <?= ucfirst(str_replace('_', ' ', $orderDetails['payment_status'])) ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($orderDetails['payment_status'] === 'pending'): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Instruksi Pembayaran</h5>
                        
                        <div class="mb-4">
                            <h6>Transfer Bank <?= strtoupper($orderDetails['payment_method']) ?></h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-1"><strong>Nomor Rekening:</strong> 1234 5678 9012 3456</p>
                                <p class="mb-1"><strong>Atas Nama:</strong> PT Afriza eSIM</p>
                                <p class="mb-1"><strong>Jumlah Transfer:</strong> $<?= number_format($orderDetails['total_amount'], 2) ?></p>
                            </div>
                        </div>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label">Upload Bukti Transfer</label>
                                <input class="form-control" type="file" id="payment_proof" name="payment_proof" required>
                                <div class="form-text">Format: JPG, PNG, atau PDF (maks. 2MB)</div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Kirim Bukti Pembayaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>