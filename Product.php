<?php
require_once("Database.php");
class Product
{
    public $product_id;
    public $product_name;
    public $product_description;
    public $product_price;
    public $product_stock;
    public $isPrescriptionRequired;
    public $category_id;

    public function checkStock()
    {
        // Check if the product is in stock

        $id = $this->product_id;
        // hena howa hyb3at el status bta3et Prescription 
        $prescriptionNeeded = $this->isPrescriptionRequired;

        return $this->product_stock > 0;
    }

    public function updateDetails($name = null, $description = null, $price = null)
    {
        //nafs kalam el Prescription
        $category = $this->category_id;

        if ($name !== null) {
            $this->product_name = $name;
        }
        if ($description !== null) {
            $this->product_description = $description;
        }
        if ($price !== null) {
            $this->product_price = $price;
        }
    }

    public function applyDiscount($percentage)
    {
        //h3mel substract ll percentage el hattb3tly mn el product price
        if ($percentage > 0 && $percentage <= 100) {
            $this->product_price -= $this->product_price * ($percentage / 100);
        }
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
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($search)) {
    
    $sql .= " WHERE category LIKE :search OR name LIKE :search";
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sakr</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/all.min.css" />
    <link rel="stylesheet" href="css/product.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <main data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-smooth-scroll="true">
        <nav id="mynav" class="navbar navbar-expand-lg fixed-top bg-white">
            <div class="container">
                <a class="navbar-brand text-black sakr-heading" href="home.php">Sakr Pharma</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav d-flex justify-content-between align-items-center mt-3 mx-3">
                        <li class="nav-item p-2 m-0 p-0">
                            <a class="nav-link text-8e8e8e position-relative m-0 p-0" aria-current="page" href="home.php">HOME</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link text-8e8e8e position-relative active" href="Product.php">Products</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav d-flex justify-content-between align-items-center ms-auto mb-2 mb-lg-0">
                        <li class="nav-item p-2">
                            <a class="nav-link text-black position-relative" href="Cart.php"><span><i class="fa-solid fa-cart-shopping"></i></span></a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link text-black position-relative" href="userProfile.php"><i class="fa-solid fa-user"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="heading-All-Items position-relative">
    <h1 class="text-center text-black">
        <?php
        if (!empty($search)) {
            echo htmlspecialchars($search) . " Items";
        } else {
            echo "All Items";
        }
        ?>
    </h1>
</div>
        <div class="container">
            <div class="row mb-3 g-5">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4">
                        <div class="box rounded-3 h-100 w-100 shadow">
                            <div class="images-box border-bottom">
                                <img class="image-fluid" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                            </div>
                            <div class="content-box p-4">
                                <h5 class="adjust-h5"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <div class="paragraphs d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="adjust-p text-8e8e8e lead"><?php echo htmlspecialchars($product['price']); ?></p>
                                        <p class="adjust-p text-8e8e8e lead"><?php echo htmlspecialchars($product['category']); ?></p>
                                    </div>
                                    <div class="btn">
                                        <a href=""><button type="button" class="btn btn-lg btn-primary add-to-cart-btn" data-id="<?php echo $product['id']; ?>" data-name="<?php echo $product['name']; ?>" data-price="<?php echo $product['price']; ?>" data-image="<?php echo $product['image']; ?>">Add To Cart</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <script>
  document.addEventListener('DOMContentLoaded', function () {
    const cartBtns = document.querySelectorAll('.add-to-cart-btn');
    const cartCount = document.getElementById('cart-count');

    function getCart() {
      return JSON.parse(localStorage.getItem('cart')) || [];
    }

    function setCart(cart) {
      localStorage.setItem('cart', JSON.stringify(cart));
    }

    function updateCartCount() {
      const cart = getCart();
      const count = cart.reduce((sum, item) => sum + item.quantity, 0);
      if (cartCount) {
        cartCount.textContent = count;
        cartCount.style.display = count > 0 ? 'inline-block' : 'none';
      }
    }

    function addToDatabase(productId, quantity = 1) {
      fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          product_id: productId,
          quantity: quantity
        })
      })
      .then(response => response.json())
      .then(data => {
        if (!data.success) {
          alert("Failed to add to database: " + data.message);
        }
      })
      .catch(error => {
        console.error("Error adding to database:", error);
      });
    }

    cartBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const id = parseInt(btn.getAttribute('data-id'));

        const name = btn.getAttribute('data-name');
        const price = btn.getAttribute('data-price');
        const image = btn.getAttribute('data-image');

        let cart = getCart();
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
          existingItem.quantity += 1;
          addToDatabase(id, existingItem.quantity);
        } else {
          cart.push({ id, name, price, image, quantity: 1 });
          addToDatabase(id, 1);
        }

        setCart(cart);
        updateCartCount();
      });
    });

    updateCartCount();
  });
</script>


</body>

</html>