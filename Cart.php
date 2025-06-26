<?php
require_once 'Database.php'; 
try {
    $pdo = Database::getInstance()->getConnection();

    // Handle delete item
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product_id'])) {
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE product_id = ?");
        $stmt->execute([$_POST['delete_product_id']]);
    }

    // Handle quantity update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product_id'], $_POST['action'])) {
        $product_id = $_POST['update_product_id'];
        $action = $_POST['action'];

        $stmt = $pdo->prepare("SELECT quantity FROM cart_items WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $item = $stmt->fetch();

        if ($item) {
            $currentQty = (int)$item['quantity'];
            if ($action === 'increase') {
                $newQty = $currentQty + 1;
            } elseif ($action === 'decrease' && $currentQty > 1) {
                $newQty = $currentQty - 1;
            } else {
                $newQty = $currentQty;
            }

            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE product_id = ?");
            $stmt->execute([$newQty, $product_id]);
        }
    }

    $sql = "SELECT 
            ci.product_id, 
            ci.quantity, 
            p.name, 
            p.img AS image, 
            p.price
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.ID";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Error fetching cart: " . $e->getMessage() . "</p>";
    $cartItems = [];
    $subtotal = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sakr</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/all.min.css" />
    <link rel="stylesheet" href="css/cart.css" />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <main>
      <div class="container">
        <div class="col-md-12">
          <div class="row">
            <div class="heading-shop-cart my-5">
              <h1>Shopping Cart</h1>
            </div>
          </div>

          <?php if (count($cartItems) > 0): ?>
            <?php foreach ($cartItems as $product): ?>
              <form method="POST">
              <div class="row d-flex justify-content-around border-bottom">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                  <div class="col-md-2 my-3">
                    <div class="image-product-cart">
                      <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="row">
                      <div class="product-name">
                        <h3 class="text-center"><?= htmlspecialchars($product['name']) ?></h3>
                      </div>
                    </div>
                    <div class="row">
                      <div class="product-ID">
                        <h5 class="lead text-center text-8e8e8e">ID: <?= htmlspecialchars($product['product_id']) ?></h5>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 d-flex align-items-center justify-content-between flex-nowrap">
                    <button name="action" value="decrease" type="submit" class="btn btn-sm btn-outline-secondary">−</button>
                    <input type="hidden" name="update_product_id" value="<?= $product['product_id'] ?>">
                    <h4 class="lead m-0 text-center"><?= $product['quantity'] ?></h4>
                    <button name="action" value="increase" type="submit" class="btn btn-sm btn-outline-secondary">+</button>
                  </div>
                  <div class="col-md-3">
                    <div class="product-price text-center">
                      <h4 class="text-center m-0">EGP <?= number_format($product['price'] * $product['quantity'], 2) ?></h4>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <button name="delete_product_id" value="<?= $product['product_id'] ?>" type="submit" class="btn btn-danger btn-sm">
                      <i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
              </div>
              </form>
            <?php endforeach; ?>
          <?php else: ?>
            <h2 class="my-5 text-center">Your cart is empty.</h2>
          <?php endif; ?>

          <div class="row my-3 d-flex justify-content-between align-items-center">
            <div class="col-md-3">
              <h3 class="lead fs-3 text-8e8e8e">Sub-Total: EGP <?= number_format($subtotal, 2) ?></h3>
            </div>
            <div class="col-md-3">
              <div class="return-to-products">
                <a class="btn rounded-0 btn-teal d-flex align-items-center justify-content-evenly flex-nowrap p-3" href="checkout.php" role="button">
                  <div class="return">
                    <h5 class="m-0">Check Out</h5>
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div class="row my-2">
            <div class="col-md-3">
              <div class="return-to-products">
                <a class="btn rounded-4 btn-primary d-flex align-items-center justify-content-evenly flex-nowrap p-3" href="Product.php" role="button">
                  <div class="arrow-return m-0 p-0 text-white">
                    <h5 class="m-0">
                      <i class="fa-solid fa-arrow-left fs-4"></i>
                    </h5>
                  </div>
                  <div class="return">
                    <h5 class="m-0">Return To Products</h5>
                  </div>
                </a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </main>
    <script src="js/bootstrap.bundle.min.js"></script>
  </body>
</html>
