<?php
require_once 'Order.php';
require_once 'product.php';

class OrderItem {
    public string $orderItemId;
    public string $orderId;
    public string $productId;
    public int $quantity;
    public float $price;

    public function __construct($orderItemId, $orderId, $productId, $quantity, $price) {
        $this->orderItemId = $orderItemId;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function getSubtotal() {
        return $this->quantity * $this->price;
    }
}

?>
