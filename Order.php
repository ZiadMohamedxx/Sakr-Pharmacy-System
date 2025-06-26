<?php
require_once 'customer.php';

require_once 'ObserverInterface.php';


class Order{
    public string $orderId;
    public string $customerId;
    public DateTime $orderDate;
    public string $status;
    public string $paymentId;
    public string $addressId;
    public float $totalAmount;

    private array $observers = [];

    public function __construct($orderId, $customerId, $orderDate, $status, $paymentId, $addressId, $totalAmount) {
        $this->orderId = $orderId;
        $this->customerId = $customerId;
        $this->orderDate = new DateTime($orderDate);
        $this->status = $status;
        $this->paymentId = $paymentId;
        $this->addressId = $addressId;
        $this->totalAmount = $totalAmount;
    }

    //observer 1
    public function attach(ObserverInterface $observer) {
        $this->observers[] = $observer;
    }

    //observer 2
    public function detach(ObserverInterface $observer) {
        $index = array_search($observer, $this->observers);
        if ($index !== false) {
            unset($this->observers[$index]);
        }
    }

    //observer 3
    public function notify($message) {
        foreach ($this->observers as $observer) {
            $observer->update($message, $this->customerId);
        }
    }
    public function updateStatus($newStatus) 
    {
        $this->status = $newStatus;
        $this->notify("Order status updated to: $newStatus");
    }

    public function cancelOrder() 
    {
        $this->status = "Cancelled";
        $this->notify("Order has been cancelled.");
    }

    public function getOrderDetails() {
        return [
            'orderId' => $this->orderId,
            'customerId' => $this->customerId,
            'orderDate' => $this->orderDate->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'paymentId' => $this->paymentId,
            'addressId' => $this->addressId,
            'totalAmount' => $this->totalAmount
        ];
    }
}

?>
