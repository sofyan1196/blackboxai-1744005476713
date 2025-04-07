<?php
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Belanja eSIM dengan Mudah</h1>
                <p class="lead mb-4">Dapatkan akses internet di seluruh dunia tanpa kartu fisik. Aktifkan langsung di smartphone Anda!</p>
                <a href="products.php" class="btn btn-light btn-lg px-4">Beli Sekarang</a>
            </div>
            <div class="col-lg-6">
                <img src="assets/images/hero-image.png" alt="eSIM Illustration" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Product Section -->
<section class="product-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Produk Populer</h2>
        <div class="row">
            <?php
            require_once __DIR__ . '/includes/classes/Product.php';
            $product = new Product($pdo);
            $products = $product->getPopularProducts(4);
            
            foreach ($products as $item): ?>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($item['region']) ?></p>
                        <p class="card-text"><?= htmlspecialchars($item['description']) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">$<?= number_format($item['price'], 2) ?></span>
                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                               data-bs-target="#productModal" data-id="<?= $item['id'] ?>">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Cara Order</h2>
        <div class="row text-center">
            <div class="col-md-3">
                <div class="step mb-3">
                    <div class="step-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-search fa-2x"></i>
                    </div>
                    <h4 class="mt-3">1. Pilih Produk</h4>
                    <p>Cari eSIM sesuai kebutuhan Anda</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step mb-3">
                    <div class="step-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-cart-plus fa-2x"></i>
                    </div>
                    <h4 class="mt-3">2. Checkout</h4>
                    <p>Lakukan pembayaran dengan mudah</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step mb-3">
                    <div class="step-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-qrcode fa-2x"></i>
                    </div>
                    <h4 class="mt-3">3. Terima eSIM</h4>
                    <p>Dapatkan QR code via email</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step mb-3">
                    <div class="step-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-wifi fa-2x"></i>
                    </div>
                    <h4 class="mt-3">4. Aktifkan</h4>
                    <p>Scan QR code dan mulai internetan</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
