<?php
require_once 'Database.php';
require_once 'user.php';
class Admin extends user
{
  private $adminLevel;
  private $products = [];
  private $orders = [];

  public function __construct($userId, $fname, $lname, $email, $password, $adminLevel)
  {
    parent::__construct($userId, $fname, $lname, $email, $password, 'admin');
    $this->adminLevel = $adminLevel;
  }

  public function addProduct($productId, $productName, $price, $quantity, $category, $img)
  {
    $conn = Database::getInstance()->getConnection();

    try {
      $stmt = $conn->prepare("INSERT INTO products (ID, name, price, quantity, category, img) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->execute([$productId, $productName, $price, $quantity, $category, $img]);
      return "Product '$productName' added successfully.";
    } catch (PDOException $e) {
      return "Error: " . $e->getMessage();
    }
  }

  public function updateProduct($productId, $newName, $newPrice, $newquantity, $newcategory, $newimg)
  {
    $conn = Database::getInstance()->getConnection();
    try {
      $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, quantity = ?, category = ?, img = ? WHERE ID = ?");
      $stmt->execute([$newName, $newPrice, $productId, $newquantity, $newcategory, $newimg]);

      if ($stmt->rowCount() > 0) {
        return "Product '$productId' updated successfully.";
      } else {
        return "No changes made or product not found.";
      }
    } catch (PDOException $e) {
      return "Error: " . $e->getMessage();
    }
  }

  public function deleteProduct($productId)
  {
    $conn = Database::getInstance()->getConnection();
    try {
      $stmt = $conn->prepare("DELETE FROM products WHERE ID = ?");
      $stmt->execute([$productId]);

      if ($stmt->rowCount() > 0) {
        return "Product '$productId' deleted.";
      } else {
        return "Product not found.";
      }
    } catch (PDOException $e) {
      return "Error: " . $e->getMessage();
    }
  }

  public function viewReports()
  {
    // reports[];
  }

  public function manageInventory()
  {
    $conn = Database::getInstance()->getConnection();

    try {
      $stmt = $conn->query("SELECT * FROM products");
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (count($products) == 0) {
        return "Inventory is empty.";
      }

      $output = "Product List:\n";
      foreach ($products as $product) {
        $output .= "- {$product['ID']} | {$product['name']} | {$product['price']} EGP\n";
      }
      return $output;
    } catch (PDOException $e) {
      return "Error: " . $e->getMessage();
    }
  }

  public function viewOrders($orderItem, $orderId)
  {
    echo "Order #$orderId contains item: $orderItem\n";
  }

  public function updateOrdersStatus($orderItem, $orderId, $orderStatus)
  {
    $this->orders[$orderId] = [
      'item' => $orderItem,
      'status' => $orderStatus
    ];
    echo "Order #$orderId for item '$orderItem' updated to status: $orderStatus\n";
  }

  public function cancelOrders($orderItem, $orderId)
  {
    $this->orders[$orderId] = [
      'item' => $orderItem,
      'status' => 'cancelled'
    ];
    echo "Order #$orderId for item '$orderItem' has been cancelled.\n";
  }
}
?>
<?php
try {
  $conn = Database::getInstance()->getConnection();
} catch (PDOException $e) {
  die("Connection failed: {$e->getMessage()}");
}
$products = [];
$sql = "SELECT * FROM products";
$search = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($search)) {
  $sql .= " WHERE category LIKE :search";
  $stmt = $conn->prepare($sql);
  $stmt->execute(['search' => "%$search%"]);
} else {
  $stmt = $conn->query($sql);
}

$result = $stmt;

if ($result) {
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $products[] = [
      'id' => $row['ID'],
      'name' => $row['name'],
      'price' => $row['price'] . " LE",
      'category' => $row['category'],
      'quantity' => $row['quantity'],
      'image' => $row['img'],
    ];
  }
}

?>