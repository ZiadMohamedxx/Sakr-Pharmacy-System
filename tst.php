<?php
require_once 'admins.php';
require_once 'Database.php';

$admin = new Admin("1", "Ahmed", "Mohamed", "pharma@example.com", "secure123", "Owner");

$output = "";

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


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "addCategory") {
    $categoryName = trim($_POST["categoryName"]);
    $categoryDescription = trim($_POST["categoryDescription"]);

    
    $stmt = $conn->prepare("INSERT INTO category (category_name	, category_description) VALUES (:name, :description)");

    
    $stmt->bindParam(':name', $categoryName, PDO::PARAM_STR);
    $stmt->bindParam(':description', $categoryDescription, PDO::PARAM_STR);

    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>✅ Category added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Error adding category.</div>";
    }
}




if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "editCategory") {
    $currentName = trim($_POST["currentCategoryName"]);    
    $newName = trim($_POST["newCategoryName"]);           
    $newDescription = trim($_POST["newCategoryDescription"]); 

    if (empty($currentName) || empty($newName)) {
        echo "<div class='alert alert-danger'>Current and New Category Names are required.</div>";
        exit;
    }

    try {
        
        $checkStmt = $conn->prepare("SELECT id FROM category WHERE category_name = :currentName LIMIT 1");
        $checkStmt->bindParam(':currentName', $currentName, PDO::PARAM_STR);
        $checkStmt->execute();

        if ($checkStmt->rowCount() === 1) {
            
            $updateStmt = $conn->prepare("
                UPDATE category
                SET category_name = :newName, category_description = :newDescription
                WHERE category_name = :currentName
            ");
            $updateStmt->bindParam(':newName', $newName, PDO::PARAM_STR);
            $updateStmt->bindParam(':newDescription', $newDescription, PDO::PARAM_STR);
            $updateStmt->bindParam(':currentName', $currentName, PDO::PARAM_STR);

            if ($updateStmt->execute()) {
                echo "<div class='alert alert-success'>Category updated successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Failed to update category.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Category with the current name not found.</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "deleteCategory") {
    $categoryName = trim($_POST["categoryName"]);

    if (empty($categoryName)) {
        echo "<div class='alert alert-danger'>Category Name is required to delete.</div>";
        exit;
    }

    try {
        
        $checkStmt = $conn->prepare("SELECT id FROM category WHERE category_name = :categoryName LIMIT 1");
        $checkStmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
        $checkStmt->execute();

        if ($checkStmt->rowCount() === 1) {
            // Delete the category by name
            $deleteStmt = $conn->prepare("DELETE FROM category WHERE category_name = :categoryName");
            $deleteStmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);

            if ($deleteStmt->execute()) {
                echo "<div class='alert alert-success'>Category deleted successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Failed to delete category.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Category with this name not found.</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'updateStock') {
        // Update quantity and price by product ID
        $productId = (int)$_POST['productId'];
        $newQuantity = (int)$_POST['newQuantity'];
        $newPrice = (float)$_POST['newPrice'];

        $updateStmt = $conn->prepare("UPDATE products SET quantity = ?, price = ? WHERE ID = ?");
        if ($updateStmt->execute([$newQuantity, $newPrice, $productId])) {
            echo "<div class='alert alert-success'>Product updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to update product.</div>";
        }
    } elseif ($_POST['action'] === 'deleteProduct') {
        // Delete product by ID
        $productId = (int)$_POST['productId'];

        $deleteStmt = $conn->prepare("DELETE FROM products WHERE ID = ?");
        if ($deleteStmt->execute([$productId])) {
            echo "<div class='alert alert-success'>Product deleted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to delete product.</div>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['prescriptionId'])) {
    $prescriptionId = (int)$_POST['prescriptionId'];
    $comment = $_POST['comment'] ?? '';
    
    if ($_POST['action'] === 'verify') {
        $status = 'Verified';
    } elseif ($_POST['action'] === 'reject') {
        $status = 'Rejected';
    } else {
        $status = null;
    }

    if ($status !== null) {
        $updateStmt = $conn->prepare("UPDATE prescription SET status = ?, comment = ? WHERE `prescription_ID` = ?");
        $success = $updateStmt->execute([$status, $comment, $prescriptionId]);

        if ($success) {
            echo "<div class='alert alert-success'>Prescription #$prescriptionId updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to update prescription #$prescriptionId.</div>";
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
    <link rel="stylesheet" href="css/adminss.css" />
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
                            onclick="getSectionId('adminInventory')"
                            href="#adminInventory"
                            class="list-group-item list-group-item-action d-flex align-items-center my-2 rounded-4 border-0 p-3">
                            <i class="fa-solid fa-warehouse"></i><span class="ms-2">Inventory</span>
                        </a>
                        <a
                             onclick="getSectionId('adminCategory')"
                             href="#adminCategory"
                             class="list-group-item list-group-item-action d-flex align-items-center my-2 rounded-4 border-0 p-3">
                             <i class="fa-solid fa-tags"></i>
                             <span class="ms-2">Category</span>
                        </a>
                        <a
                            onclick="getSectionId('adminReports')"
                            href="#adminReports"
                            class="list-group-item list-group-item-action d-flex align-items-center my-2 rounded-4 border-0 p-3">
                            <i class="fa-solid fa-bell"></i>
                            <span class="ms-2">prescriptions</span>
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
                </div>
                <div id="adminOrderList" class="section container my-5">
    <h2 class="mb-4 text-center display-6 fw-bold">📦 Order List</h2>

    <?php
    try {
        $stmt = $conn->prepare("SELECT order_id, customer_id, order_date, status, payment_id, address_id, total_amount FROM orders");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo '
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-primary">
                        <tr class="fw-semibold">
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Status</th>
                            
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $status = htmlspecialchars($row['status']);
                $badgeClass = match (strtolower($status)) {
                    'pending' => 'warning',
                    'shipped' => 'primary',
                    'delivered' => 'success',
                    'cancelled' => 'danger',
                    default => 'secondary',
                };

                echo '<tr>
                        <td>#' . htmlspecialchars($row['order_id']) . '</td>
                        <td>' . htmlspecialchars($row['customer_id']) . '</td>
                        <td>' . htmlspecialchars($row['order_date']) . '</td>
                        <td><span class="badge bg-' . $badgeClass . '">' . ucfirst($status) . '</span></td>
                        
                        <td><strong>EGP ' . number_format($row['total_amount'], 2) . '</strong></td>
                    </tr>';
            }

            echo '</tbody></table></div>';
        } else {
            echo '<div class="alert alert-info text-center">No orders found in the database.</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger text-center">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    ?>
</div>

<div id="adminInventory" class="section container my-5">
    <h2 class="mb-4 text-center">Inventory Products</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        try {
            $stmt = $conn->prepare("SELECT ID, name, quantity, price FROM products");
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = (int)$row['ID'];
                    $name = htmlspecialchars($row['name']);
                    $qty = (int)$row['quantity'];
                    $price = number_format((float)$row['price'], 2);

                    echo '
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">' . $name . '</h5>
                                <p class="card-text">
                                    Quantity: ' .
                                    ($qty === 0
                                        ? '<span class="badge bg-danger">Out of Stock</span>'
                                        : '<span class="badge bg-primary">' . $qty . '</span>') . 
                                '</p>
                                <p class="card-text">Price: EGP' . $price . '</p>

                                <form method="POST" class="d-flex align-items-center gap-2 mb-2">
                                    <input type="hidden" name="action" value="updateStock" />
                                    <input type="hidden" name="productId" value="' . $id . '" />
                                    <input type="number" name="newQuantity" min="0" placeholder="New quantity" class="form-control form-control-sm" required />
                                    <input type="text" name="newPrice" placeholder="New price" class="form-control form-control-sm" required />
                                    <button type="submit" class="btn btn-warning px-3 py-1" style="font-size: 0.8rem;">Update</button>
                                </form>

                                <form method="POST" onsubmit="return confirm(\'Are you sure you want to delete this product?\');">
                                    <input type="hidden" name="action" value="deleteProduct" />
                                    <input type="hidden" name="productId" value="' . $id . '" />
                                    <button type="submit" class="btn btn-danger px-3 py-1" style="font-size: 0.8rem;">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-center">No products found in inventory.</p>';
            }
        } catch (PDOException $e) {
            echo '<p class="text-danger text-center">Error loading inventory: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
    </div>
</div>

                <div id="adminCategory" class="section container d-none">
    
    <div class="add-category my-5 p-4">
        <div class="text-center heading-add-category">
        <h1>Add Category</h1>
</div>
<form method="POST">
    <input type="hidden" name="action" value="addCategory" />
    
    
    <input 
        required 
        name="categoryName" 
        type="text" 
        placeholder="Category Name" 
        class="form-control bg-body-secondary my-3 form-control-lg" 
    />
    
    <textarea 
        required 
        name="categoryDescription" 
        placeholder="Category Description" 
        class="form-control bg-body-secondary my-3 form-control-lg" 
        rows="3"
    ></textarea>
    
    <button class="btn btn-success text-center" type="submit">Add Category</button>
</form>
</div>


    
    <div class="edit-category my-5 p-4">
        <div class="text-center heading-edit-category">
        <h1>Edit Category</h1>
</div>
<form method="POST">
    <input type="hidden" name="action" value="editCategory" />
    
    <input 
        required 
        name="currentCategoryName" 
        type="text" 
        placeholder="Current Category Name" 
        class="form-control bg-body-secondary my-3 form-control-lg" 
    />
    
    <input 
        required 
        name="newCategoryName" 
        type="text" 
        placeholder="New Category Name" 
        class="form-control bg-body-secondary my-3 form-control-lg" 
    />
    
    <textarea 
        name="newCategoryDescription" 
        placeholder="New Category Description" 
        class="form-control bg-body-secondary my-3 form-control-lg" 
        rows="3"
    ></textarea>
    
    <button class="btn btn-warning text-center" type="submit">Edit Category</button>
</form>
</div>


    
<div class="delete-category my-5 p-4">
    <div class="text-center heading-delete-category">
        <h1>Delete Category</h1>
    </div>
    <form method="POST">
        <input type="hidden" name="action" value="deleteCategory" />
        <input 
            required 
            name="categoryName" 
            type="text" 
            placeholder="Category Name to Delete" 
            class="form-control bg-body-secondary my-3 form-control-lg" 
        />
        <button class="btn btn-danger text-center" type="submit">Delete Category</button>
    </form>
</div>

</div>

<div id="adminReports" class="section container my-5">
    <h2 class="mb-4 text-center">Prescription Reports</h2>
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php
        try {
            $stmt = $conn->prepare("SELECT `prescription_ID`, `customer_ID`, `upload_date`, `image_url`, `status`, `comment` FROM prescription");
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $prescriptionId = (int)$row['prescription_ID'];
                    $customerId = htmlspecialchars($row['customer_ID']);
                    $uploadDate = htmlspecialchars($row['upload_date']);
                    $imageUrl = htmlspecialchars($row['image_url']);
                    $status = htmlspecialchars($row['status'] ?? 'Pending');
                    $comment = htmlspecialchars($row['comment'] ?? '');

                    echo '
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Prescription #' . $prescriptionId . '</h5>
                                <p><strong>Customer ID:</strong> ' . $customerId . '</p>
                                <p><strong>Upload Date:</strong> ' . $uploadDate . '</p>
                                <p><strong>Status:</strong> ' . $status . '</p>
                                <p><strong>Comment:</strong> ' . nl2br($comment) . '</p>
                                <img src="' . $imageUrl . '" alt="Prescription Image" class="img-fluid mb-3" style="max-height: 200px;" />

                                <form method="POST" class="d-flex flex-column gap-2">
                                    <input type="hidden" name="prescriptionId" value="' . $prescriptionId . '" />
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Add a comment...">' . $comment . '</textarea>

                                    <div class="d-flex gap-2">
                                        <button type="submit" name="action" value="verify" class="btn btn-success flex-grow-1">Verify</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger flex-grow-1">Reject</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-center">No prescriptions found.</p>';
            }
        } catch (PDOException $e) {
            echo '<p class="text-danger text-center">Error loading prescriptions: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
    </div>
</div>
            </div>
        </div>
    </main>
    <script src="js/adminss.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>