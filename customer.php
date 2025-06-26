<?php
require_once 'user.php';
require_once 'db.php';

class Customer extends User
{
    private $address;
    private $phoneNumber;
    private $loyaltyPoints;
    private $orderHistory = [];
    private $cart = [];
    private $pdo;

    public function __construct($userId, $fname, $lname, $email, $password, $address, $phoneNumber, $loyaltyPoints = 0)
    {
        parent::__construct($userId, $fname, $lname, $email, $password, $address);
        $this->address = $address;
        $this->phoneNumber = $phoneNumber;
        $this->loyaltyPoints = $loyaltyPoints;
        global $pdo;
        $this->pdo = $pdo;


    }

    public function browseProducts() 
    {
        try 
        {
            $stmt = $this->pdo->query("SELECT * FROM products");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $products;
        } catch (PDOException $e) 
        {
            return "Error: " . $e->getMessage();
        }
    }

    public function addToCart($productId, $quantity) 
    {
        try 
        {
            $cartId = uniqid(); 
            $stmt = $this->pdo->prepare("INSERT INTO cart_items (cart_id, customer_id, product_id, quantity) VALUES (?, ?, ?, ?)");
            $stmt->execute([$cartId, $this->userId, $productId, $quantity]);
            return "Product added to cart.";
        } catch (PDOException $e)
        {
            return "Add to cart error: " . $e->getMessage();
        }
    }

    public function placeOrders() 
    {
        try 
        {
            $orderId = uniqid();
            //y = year
            //m= month
            //d=day
            //h=hour
            //i=mins
            //s=seconds
            $now = date("Y-m-d H:i:s");

            // hakhod el cart items mn el database
            $stmt = $this->pdo->prepare("SELECT * FROM cart_items WHERE customer_id = ?");
            $stmt->execute([$this->userId]);
            $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($cartItems))
            {
                return "Cart is empty.";
            }

            // total el cart
            $total = 0;
            foreach ($cartItems as $item) 
            {
                $productId = $item['product_id'];
                $qty = $item['quantity'];

                $priceStmt = $this->pdo->prepare("SELECT price FROM products WHERE product_id = ?");
                $priceStmt->execute([$productId]);
                $product = $priceStmt->fetch(PDO::FETCH_ASSOC);

                if ($product) 
                {
                    $total += $product['price'] * $qty;
                }
            }

            // insert order fe table el database
            $stmt = $this->pdo->prepare("INSERT INTO orders (order_id, customer_id, order_date, status, total_amount) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$orderId, $this->userId, $now, "Pending", $total]);

            // insert el order items fe table el database
            foreach ($cartItems as $item) 
            {
                $productId = $item['product_id'];
                $qty = $item['quantity'];

                $priceStmt = $this->pdo->prepare("SELECT price FROM products WHERE product_id = ?");
                $priceStmt->execute([$productId]);
                $product = $priceStmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    $price = $product['price'];
                    $orderItemId = uniqid();
                    $insertItem = $this->pdo->prepare("INSERT INTO order_items (order_item_id, order_id, product_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
                    $insertItem->execute([$orderItemId, $orderId, $productId, $qty, $price]);
                }
            }

            // hanfadi el cart 
            $clearCart = $this->pdo->prepare("DELETE FROM cart_items WHERE customer_id = ?");
            $clearCart->execute([$this->userId]);

            return "Order placed successfully with ID: $orderId. Total: $total EGP.";

        } catch (PDOException $e) 
        {
            return "Order error: " . $e->getMessage();
        }
    }

    public function uploadPrescription($imageUrl) 
    {
        try 
        {
            //y = year
            //m= month
            //d=day
            //h=hour
            //i=mins
            //s=seconds
            $now = date("Y-m-d H:i:s");
            $status = "Pending";
            $stmt = $this->pdo->prepare("INSERT INTO prescriptions (customer_id, Uploded_Date, Image_URL, Status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$this->userId, $now, $imageUrl, $status]);
            return "Prescription uploaded.";
        } catch (PDOException $e) 
        {
            return "Upload failed: " . $e->getMessage();
        }
    }

    public function trackOrder() 
    {
        try 
        {
            $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE customer_id = ?");
            $stmt->execute([$this->userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e)
        {
            return "Track error: " . $e->getMessage();
        }
    
    }
}
?>
