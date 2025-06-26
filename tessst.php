<?php
require_once 'admin.php';
require_once 'Database.php';

$admin = new Admin("1", "Ahmed", "Mohamed", "pharma@example.com", "secure123", "Owner");

$output = "";

try {
    // Assuming $conn is your PDO connection from Database.php
    $stmt = $conn->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $products = [];
}

try {
    // Assuming $conn is your PDO connection from Database.php
    $stmt = $conn->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $users = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {
    $user = $_POST['username'];
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->execute([$user]);
    if ($stmt->rowCount() > 0) {
        // echo "User deleted successfully!";
    } else {
        // echo "Error: Could not delete user.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'addProduct':
                if (
                    isset($_POST['productId'], $_POST['productName'], $_POST['price'], $_POST['quantity'], $_POST['category'], $_POST['img'])
                ) {
                    $output = $admin->addProduct(
                        $_POST['productId'],
                        $_POST['productName'],
                        $_POST['price'],
                        $_POST['quantity'],
                        $_POST['category'],
                        $_POST['img']
                    );
                } else {
                    $output = "Missing required fields for adding product.";
                }
                break;

            case 'updateProduct':
                if (
                    isset($_POST['id'], $_POST['name'], $_POST['price'])
                ) {
                    $output = $admin->updateProduct(
                        $_POST['id'],
                        $_POST['name'],
                        $_POST['price'],
                        '', // Placeholder for argument 4
                        '', // Placeholder for argument 5
                        ''  // Placeholder for argument 6
                    );
                } else {
                    $output = "Missing required fields for updating product.";
                }
                break;

            case 'deleteProduct':
                if (isset($_POST['id'])) {
                    $output = $admin->deleteProduct($_POST['id']);
                } else {
                    $output = "Missing product ID for deletion.";
                }
                break;

            case 'updateOrderStatus':
                if (
                    isset($_POST['orderItem'], $_POST['orderId'], $_POST['orderStatus'])
                ) {
                    $admin->updateOrdersStatus($_POST['orderItem'], $_POST['orderId'], $_POST['orderStatus']);
                    $output = "Order status updated successfully.";
                } else {
                    $output = "Missing required fields for updating order status.";
                }
                break;

            case 'cancelOrder':
                if (
                    isset($_POST['orderItem'], $_POST['orderId'])
                ) {
                    $admin->cancelOrders($_POST['orderItem'], $_POST['orderId']);
                    $output = "Order cancelled successfully.";
                } else {
                    $output = "Missing required fields for cancelling order.";
                }
                break;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"
        rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/all.min.css" />
    <link rel="stylesheet" href="css/admins.css" />
    <script>
        window.onload = function() {
            var clickedSection = localStorage.getItem("selectedSection");
            var sections = document.querySelectorAll(".section");
            sections.forEach((section) => {
                section.classList.add("d-none");
            });
            var sSection = document.getElementById(clickedSection);
            sSection.classList.remove("d-none");
        };
    </script>
</head>

<body>
    <main
        data-bs-spy="scroll"
        data-bs-target="#navbar-example2"
        data-bs-smooth-scroll="true">
        <div class="container d-flex flex-nowrap">
            <div class="col-md-2">
                <div class="row">
                    <div class="sakr-logo my-5">
                        <h1>Sakr Pharma</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="list-group dashboard">
                        <a
                            href="#adminProduct"
                            class="list-group-item list-group-item-action d-flex align-items-center my-2 rounded-4 border-0 p-3 active"
                            aria-current="true"
                            onclick="getSectionId('adminProduct')">
                            <i class="fa-brands fa-product-hunt"></i><span class="ms-2">Products</span>
                        </a>
                        <a
                            onclick="getSectionId('adminOrderList')"
                            href="#adminOrderList"
                            class="list-group-item list-group-item-action d-flex align-items-center my-2 rounded-4 border-0 p-3">
                            <i class="fa-solid fa-align-left"></i><span class="ms-2">Order List</span>
                        </a>
                        <a
                            onclick="getSectionId('adminCustomer')"
                            href="#adminCustomer"
                            class="list-group-item list-group-item-action d-flex align-items-center my-2 rounded-4 border-0 p-3">
                            <i class="fa-solid fa-users"></i><span class="ms-2">Customers</span>
                        </a>
                        <a
                            onclick="getSectionId('adminInventory')"
                            href="#adminInventory"
                            class="list-group-item list-group-item-action d-flex align-items-center my-2 rounded-4 border-0 p-3">
                            <i class="fa-solid fa-warehouse"></i><span class="ms-2">Inventory</span>
                        </a>
                        <a
                            onclick="getSectionId('adminReports')"
                            href="#adminReports"
                            class="list-group-item list-group-item-action d-flex align-items-center my-2 rounded-4 border-0 p-3">
                            <i class="fa-solid fa-bell"></i>
                            <span class="ms-2">Reports</span>
                        </a>
                    </div>
                </div>
            </div>
            <div
                data-bs-spy="scroll"
                data-bs-target="#list-example"
                data-bs-smooth-scroll="true"
                class="scrollspy-example col-md-10"
                tabindex="0">
                <div id="adminProduct" class="section container">
                    <div class="add-product my-5 p-4">
                        <div class="text-center heading-add-product">
                            <h1>Add Product</h1>
                        </div>
                        <form onsubmit="return validateInput()" method="POST">
                            <input type="hidden" name="action" value="addProduct" />
                            <input
                                oninput="validateFormAddProduct(this)"
                                required
                                name="productId"
                                type="text"
                                placeholder="Product ID"
                                id="productId"
                                class="form-control bg-body-secondary my-3 form-control-lg" />
                            <input
                                oninput="validateFormAddProduct(this)"
                                required
                                name="productName"
                                type="text"
                                placeholder="Product Name"
                                id="productName"
                                class="form-control bg-body-secondary my-3 form-control-lg" />
                            <div class="alert alert-danger d-none">
                                Please Enter the Product Name Correctly
                            </div>
                            <input
                                oninput="validateFormAddProduct(this)"
                                required
                                name="price"
                                type="text"
                                placeholder="Product Price"
                                id="productPrice"
                                class="form-control bg-body-secondary my-3 form-control-lg" />
                            <div class="alert alert-danger d-none">
                                Please Enter the Product Price Correctly
                            </div>
                            <input
                                required
                                name="quantity"
                                type="text"
                                placeholder="Quantity"
                                id="quantity"
                                class="form-control bg-body-secondary my-3 form-control-lg" />
                            <datalist id="searchOptions">
                                <option value="body care"></option>
                                <option value="Cosmetics"></option>
                                <option value="Medicines"></option>
                                <option value="Tablets"></option>
                                <option value="Hair Care"></option>
                            </datalist>
                            <input
                                oninput="validateFormAddProduct(this)"
                                required
                                list="searchOptions"
                                placeholder="Category"
                                name="category"
                                id="category"
                                class="form-control bg-body-secondary my-3 form-control-lg" />
                            <div class="alert alert-danger d-none">
                                Please Choose from the options
                            </div>
                            <input
                                oninput="validateFormAddProduct(this)"
                                required
                                type="text"
                                placeholder="Product Photo (LINK)"
                                id="productPhoto"
                                name="img"
                                class="form-control bg-body-secondary my-3 form-control-lg" />
                            <button class="btn text-center btn-addproduct" type="submit">
                                Add Product
                            </button>
                        </form>
                    </div>
                    <div class="edit-product my-5 p-4">
                        <div class="heading-edit-product text-center">
                            <h1>Edit/Delete Product</h1>
                        </div>
                        <div class="search-container my-5">
                            <form
                                class="d-flex w-100 justify-content-between p-2"
                                role="search">
                                <input
                                    type="text"
                                    id="searchInput"
                                    aria-label="Search"
                                    placeholder="Search..."
                                    class="search-input form-control" />
                                <button class="btn rounded-5" onclick="activateSearch()">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </div>
                        <div class="container">
                            <?php foreach ($products as $product): ?>
                                <div class="row d-flex mb-4 justify-content-around">
                                    <div
                                        class="col-md-12 d-flex justify-content-between align-items-center bg-body-secondary border-2 border-black rounded-5">
                                        <div class="col-md-2 my-3">
                                            <div class="image-product-cart">
                                                <img
                                                    src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                            </div>
                                        </div>
                                        <div class=" col-md-3">
                                            <div class="row">
                                                <div class="product-name">
                                                    <h5 class="text-center"><?php echo htmlspecialchars($product['name']); ?></h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="product-ID">
                                                    <h5 class="lead text-center text-8e8e8e"><?php echo htmlspecialchars($product['ID']); ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="product-price text-center">
                                                <h5 class="text-center m-0"><?php echo htmlspecialchars($product['price']); ?></h5>
                                            </div>
                                        </div>
                                        <div
                                            class="col-md-2 d-flex align-items-center justify-content-around flex-nowrap">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-success rounded-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                Edit Product
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Product</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST">
                                                                <input type="hidden" name="action" value="updateProduct" />
                                                                <input type="hidden" name="productId" value="<?php echo $product['id']; ?>" />
                                                                <input
                                                                    oninput="validateFormAddProduct(this)"

                                                                    name="newName"
                                                                    type="text"
                                                                    placeholder="Product Name"
                                                                    id="name"
                                                                    class="form-control bg-body-secondary my-3 form-control-lg" />
                                                                <div class="alert alert-danger d-none">
                                                                    Please Enter the Product Name Correctly
                                                                </div>
                                                                <input
                                                                    oninput="validateFormAddProduct(this)"
                                                                    name="newPrice"
                                                                    type="text"
                                                                    placeholder="Product Price"
                                                                    id="productPrice"
                                                                    class="form-control bg-body-secondary my-3 form-control-lg" />
                                                                <div class="alert alert-danger d-none">
                                                                    Please Enter the Product Price Correctly
                                                                </div>
                                                                <input
                                                                    name="newquantity"
                                                                    type="text"
                                                                    placeholder="Quantity"
                                                                    id="quantity"
                                                                    class="form-control bg-body-secondary my-3 form-control-lg" />
                                                                <datalist id="searchOptions">
                                                                    <option value="body care"></option>
                                                                    <option value="Cosmetics"></option>
                                                                    <option value="Medicines"></option>
                                                                    <option value="Tablets"></option>
                                                                    <option value="Hair Care"></option>
                                                                </datalist>
                                                                <input
                                                                    oninput="validateFormAddProduct(this)"
                                                                    list="searchOptions"
                                                                    placeholder="Category"
                                                                    name="newcategory"
                                                                    id="category"
                                                                    class="form-control bg-body-secondary my-3 form-control-lg" />
                                                                <div class="alert alert-danger d-none">
                                                                    Please Choose from the options
                                                                </div>
                                                                <input
                                                                    oninput="validateFormAddProduct(this)"
                                                                    type="text"
                                                                    placeholder="Product Photo (LINK)"
                                                                    id="productPhoto"
                                                                    name="newimg"
                                                                    class="form-control bg-body-secondary my-3 form-control-lg" />
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-success">Done</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="delete-product text-center">
                                                <form method="POST">
                                                    <input type="hidden" name="action" value="deleteProduct">
                                                    <input type="hidden" name="id" value="<?php echo $product['ID']; ?>">
                                                    <button class="btn btn-danger rounded-4" type="submit">Delete Product</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div id="adminOrderList" class="section container">

                </div>
                <div id="adminCustomer" class="section container">
                    <div class="container my-5">
                        <?php foreach ($users as $user): ?>
                            <div class="row bg-body-secondary p-4 rounded-4 align-items-center mb-4 justify-content-around">
                                <div class="col-md-2">
                                    <div class="id-user">
                                        <h4><?php echo htmlspecialchars($user['id']) ?></h4>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="username">
                                        <h4><?php echo htmlspecialchars($user['username']) ?></h4>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="name">
                                        <h4><?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']) ?></h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="email">
                                        <h4><?php echo htmlspecialchars($user['email']) ?></h4>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="delete-user">
                                        <form method="POST">
                                            <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']) ?>">
                                            <button class="btn btn-lg btn-danger rounded-4" type="submit">Delete User</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div id="adminInventory" class="section container">

                </div>
                <div id="adminReports" class="section container">

                </div>
            </div>
        </div>
    </main>
    <script src="js/adminss.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>