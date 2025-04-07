<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/classes/Order.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$order = new Order($pdo);
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$orderDetails = $order->getOrder($orderId);

// Validate order ownership
if (!$orderDetails || $orderDetails['user_id'] != $_SESSION['user_id']) {
    header('Location: dashboard.php');
    exit;
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Detail Pesanan #<?= $orderId ?></h2>
                <a href="dashboard.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Informasi Produk</h5>
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <p class="form-control-static"><?= htmlspecialchars($orderDetails['product_name']) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Region</label>
                                <p class="form-control-static"><?= htmlspecialchars($orderDetails['region']) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kuota</label>
                                <p class="form-control-static"><?= htmlspecialchars($orderDetails['quota']) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Masa Aktif</label>
                                <p class="form-control-static"><?= htmlspecialchars($orderDetails['validity']) ?> Hari</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Detail Pesanan</h5>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Pesanan</label>
                                <p class="form-control-static"><?= date('d M Y H:i', strtotime($orderDetails['created_at'])) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <p class="form-control-static"><?= $orderDetails['quantity'] ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga Satuan</label>
                                <p class="form-control-static">$<?= number_format($orderDetails['price'], 2) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Total Pembayaran</label>
                                <p class="form-control-static">$<?= number_format($orderDetails['total_amount'], 2) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <p class="form-control-static">
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
            </div>

            <?php if ($orderDetails['payment_status'] === 'paid' && $orderDetails['qr_code_path']): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-4">Aktivasi eSIM</h5>
                        <div class="row align-items-center">
                            <div class="col-md-6 text-center">
                                <img src="<?= $orderDetails['qr_code_path'] ?>" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                                <a href="<?= $orderDetails['qr_code_path'] ?>" download class="btn btn-primary">
                                    <i class="fas fa-download me-2"></i> Download QR Code
                                </a>
                            </div>
                            <div class="col-md-6">
                                <h6>Cara Aktivasi:</h6>
                                <ol class="ps-3">
                                    <li>Buka pengaturan eSIM di perangkat Anda</li>
                                    <li>Pilih "Tambah Paket Data"</li>
                                    <li>Scan QR code di samping</li>
                                    <li>Ikuti instruksi di layar</li>
                                </ol>
                                <div class="mt-3">
                                    <a href="<?= $orderDetails['usage_link'] ?>" target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-external-link-alt me-2"></i> Panduan Penggunaan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>