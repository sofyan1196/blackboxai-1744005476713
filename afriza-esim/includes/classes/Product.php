<?php
class Product {
    private $pdo;
    private $table = 'products';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get popular products
    public function getPopularProducts($limit = 4) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE status = 1 ORDER BY RAND() LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get product by ID
    public function getProduct($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get products by region
    public function getProductsByRegion($region) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE region = ? AND status = 1");
        $stmt->execute([$region]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all active products
    public function getAllProducts() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} WHERE status = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add new product
    public function addProduct($data) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} 
            (name, description, price, region, quota, validity, qr_code, usage_link) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['region'],
            $data['quota'],
            $data['validity'],
            $data['qr_code'],
            $data['usage_link']
        ]);
    }

    // Update product
    public function updateProduct($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET 
            name = ?, description = ?, price = ?, region = ?, 
            quota = ?, validity = ?, qr_code = ?, usage_link = ? 
            WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['region'],
            $data['quota'],
            $data['validity'],
            $data['qr_code'],
            $data['usage_link'],
            $id
        ]);
    }

    // Delete product (soft delete)
    public function deleteProduct($id) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET status = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>