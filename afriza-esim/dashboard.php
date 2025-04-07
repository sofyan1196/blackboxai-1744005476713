<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/classes/Order.php';
require_once __DIR__ . '/includes/classes/User.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user = new User($pdo);
$order = new Order($pdo);

// Get user data
$userData = $user->getUser($_SESSION['user_id']);
$userOrders = $order->getUserOrders($_SESSION['user_id']);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="avatar-circle bg-primary text-white d-inline-flex align-items-center justify-content-center">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="card-title"><?= htmlspecialchars($userData['username']) ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($userData['email']) ?></p>
                    
                    <div class="d-grid gap-2">
                        <a href="profile.php" class="btn btn-outline-primary">
                            <i class="fas fa-user-edit me-2"></i> Edit Profil
                        </a>
                        <a href="logout.php" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-history me-2"></i> Riwayat Pembelian
                    </h5>
                    
                    <?php if (count($userOrders) > 0): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID Pesanan</th>
                                        <th>Produk</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($userOrders as $order): ?>
                                    <tr>
                                        <td><?= $order['id'] ?></td>
                                        <td>
                                            <?= htmlspecialchars($order['product_name']) ?>
                                            <br>
                                            <small class="text-muted"><?= htmlspecialchars($order['region']) ?></small>
                                        </td>
                                        <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                        <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                        <td>
                                            <span class="badge bg-<?= 
                                                $order['payment_status'] === 'paid' ? 'success' : 
                                                ($order['payment_status'] === 'pending' ? 'warning' : 'info') 
                                            ?>">
                                                <?= ucfirst(str_replace('_', ' ', $order['payment_status'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="order-detail.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-shopping-cart fa-3x text-muted"></i>
                            </div>
                            <h5>Belum ada riwayat pembelian</h5>
                            <p class="text-muted">Silakan kunjungi halaman produk untuk melakukan pembelian</p>
                            <a href="products.php" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i> Beli Produk
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>