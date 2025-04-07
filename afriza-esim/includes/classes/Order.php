<?php
class Order {
    private $pdo;
    private $orderTable = 'orders';
    private $orderDetailsTable = 'order_details';
    private $paymentTable = 'payments';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create new order
    public function createOrder($userId, $productId, $quantity, $paymentMethod) {
        try {
            $this->pdo->beginTransaction();

            // Get product price
            $product = $this->pdo->prepare("SELECT price FROM products WHERE id = ?");
            $product->execute([$productId]);
            $productData = $product->fetch(PDO::FETCH_ASSOC);

            if (!$productData) {
                throw new Exception('Product not found');
            }

            $price = $productData['price'];
            $totalAmount = $price * $quantity;

            // Create order
            $order = $this->pdo->prepare("
                INSERT INTO {$this->orderTable} 
                (user_id, total_amount, payment_status) 
                VALUES (?, ?, 'pending')
            ");
            $order->execute([$userId, $totalAmount]);
            $orderId = $this->pdo->lastInsertId();

            // Add order details
            $orderDetails = $this->pdo->prepare("
                INSERT INTO {$this->orderDetailsTable} 
                (order_id, product_id, quantity, price) 
                VALUES (?, ?, ?, ?)
            ");
            $orderDetails->execute([$orderId, $productId, $quantity, $price]);

            // Create payment record
            $payment = $this->pdo->prepare("
                INSERT INTO {$this->paymentTable} 
                (order_id, amount, payment_method, status) 
                VALUES (?, ?, ?, 'pending')
            ");
            $payment->execute([$orderId, $totalAmount, $paymentMethod]);

            $this->pdo->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Get order by ID
    public function getOrder($orderId) {
        $stmt = $this->pdo->prepare("
            SELECT o.*, od.*, p.name as product_name, p.region, p.quota, p.validity
            FROM {$this->orderTable} o
            JOIN {$this->orderDetailsTable} od ON o.id = od.order_id
            JOIN products p ON od.product_id = p.id
            WHERE o.id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get user orders
    public function getUserOrders($userId) {
        $stmt = $this->pdo->prepare("
            SELECT o.*, p.name as product_name, p.region, od.quantity, od.price
            FROM {$this->orderTable} o
            JOIN {$this->orderDetailsTable} od ON o.id = od.order_id
            JOIN products p ON od.product_id = p.id
            WHERE o.user_id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update order status
    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->pdo->prepare("
            UPDATE {$this->orderTable} 
            SET payment_status = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$status, $orderId]);
    }
}
?>