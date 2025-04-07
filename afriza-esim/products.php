<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/classes/Product.php';

$product = new Product($pdo);
$region = isset($_GET['region']) ? $_GET['region'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';

// Get products based on filters
if ($region) {
    $products = $product->getProductsByRegion($region);
} else {
    $products = $product->getAllProducts();
}

// Apply sorting
if ($sort === 'price_asc') {
    usort($products, function($a, $b) {
        return $a['price'] <=> $b['price'];
    });
} elseif ($sort === 'price_desc') {
    usort($products, function($a, $b) {
        return $b['price'] <=> $a['price'];
    });
}
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Daftar Produk eSIM</h2>
        </div>
        <div class="col-md-6 text-end">
            <div class="dropdown d-inline-block me-2">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="regionDropdown" data-bs-toggle="dropdown">
                    <?= $region ? 'Region: ' . ucfirst($region) : 'Semua Region' ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="products.php">Semua Region</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="products.php?region=asia">Asia</a></li>
                    <li><a class="dropdown-item" href="products.php?region=europe">Europe</a></li>
                    <li><a class="dropdown-item" href="products.php?region=global">Global</a></li>
                </ul>
            </div>
            <div class="dropdown d-inline-block">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown">
                    <?= 
                        $sort === 'price_asc' ? 'Harga Terendah' : 
                        ($sort === 'price_desc' ? 'Harga Tertinggi' : 'Urutkan')
                    ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="products.php?<?= $region ? 'region='.$region.'&amp;' : '' ?>sort=default">Default</a></li>
                    <li><a class="dropdown-item" href="products.php?<?= $region ? 'region='.$region.'&amp;' : '' ?>sort=price_asc">Harga Terendah</a></li>
                    <li><a class="dropdown-item" href="products.php?<?= $region ? 'region='.$region.'&amp;' : '' ?>sort=price_desc">Harga Tertinggi</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $item): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <img src="assets/images/flags/<?= strtolower($item['region']) ?>.png" alt="<?= $item['region'] ?>" width="60">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                                <p class="text-muted mb-2"><?= htmlspecialchars($item['region']) ?></p>
                                <p class="card-text"><?= htmlspecialchars($item['description']) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-primary me-2"><?= $item['quota'] ?></span>
                                        <span class="badge bg-success"><?= $item['validity'] ?> Hari</span>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-primary me-3">$<?= number_format($item['price'], 2) ?></span>
                                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                           data-bs-target="#productModal" data-id="<?= $item['id'] ?>">
                                            Beli Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">Tidak ada produk yang tersedia untuk region ini.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Include product detail modal
require_once __DIR__ . '/includes/modals/product-detail-modal.php';
require_once __DIR__ . '/includes/footer.php';
?>