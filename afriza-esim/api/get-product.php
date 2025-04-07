<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/classes/Product.php';

try {
    // Validate input
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        throw new Exception('Invalid product ID');
    }

    $productId = (int)$_POST['id'];
    $product = new Product($pdo);
    $productData = $product->getProduct($productId);

    if (!$productData) {
        throw new Exception('Product not found');
    }

    // Return success response
    echo json_encode([
        'success' => true,
        'product' => [
            'id' => $productData['id'],
            'name' => $productData['name'],
            'description' => $productData['description'],
            'price' => $productData['price'],
            'region' => $productData['region'],
            'quota' => $productData['quota'],
            'validity' => $productData['validity'],
            'qr_code' => $productData['qr_code'],
            'usage_link' => $productData['usage_link']
        ]
    ]);

} catch (Exception $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>