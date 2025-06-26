<?php
session_start();
require_once("user.php");
$conn = new mysqli("localhost", "root", "", "pharmacy_db");

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user = null;
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, fname, lname, username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $user = [
        'id' => $row['id'],
        'fname' => $row['fname'],
        'lname' => $row['lname'],
        'username' => $row['username'],
    ];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sakr Pharma</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="css/all.min.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <main data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-smooth-scroll="true">
        <header>
            <div class="container position-relative mb-5">
                <nav id="mynav" class="navbar navbar-expand-lg fixed-top op bg-white">
                    <div class="container">
                        <a class="navbar-brand text-black sakr-heading" href="home.php">Sakr Pharma</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav d-flex justify-content-between align-items-center mt-3 mx-3">
                                <li class="nav-item p-2 m-0 p-0">
                                    <a class="nav-link text-8e8e8e position-relative active m-0 p-0" aria-current="page"
                                        href="#Home">HOME</a>
                                </li>
                                <li class="nav-item p-2">
                                    <a class="nav-link text-8e8e8e position-relative" href="#Products">Products</a>
                                </li>
                                <li class="nav-item p-2">
                                    <a class="nav-link text-8e8e8e position-relative" href="#AboutUS">About Us</a>
                                </li>
                                <li class="nav-item p-2">
                                    <a class="nav-link text-8e8e8e position-relative" href="#categories">Categories</a>
                                </li>
                                <li class="nav-item p-2">
                                    <a class="nav-link text-8e8e8e position-relative" href="#location">Location</a>
                                </li>
                                <li class="nav-item p-2">
                                    <a class="nav-link text-8e8e8e position-relative" href="#reviews">Reviews</a>
                                </li>
                                <li class="nav-item p-2">
                                    <a class="nav-link text-8e8e8e position-relative" href="upload.php">Upload Prescription</a>
                                </li>
                                <li class="nav-item p-2">
                                    <a class="nav-link text-8e8e8e position-relative" href="review_prescriptions.php">Review Prescriptions</a>
                                </li>
                            </ul>
                            <ul class="navbar-nav d-flex justify-content-between align-items-center ms-auto mb-2 mb-lg-0">

                                <li class="nav-item p-2">
                                    <h5 class="m-0 lead">Hello <?php echo htmlspecialchars($user['fname']) ?> <i class="fa-regular fa-hand"></i></h5>
                                </li>
                                <li class="nav-item p-2">
                                    <a class="nav-link text-black position-relative" href="cart.php">
                                        <span>
                                            <i class="fa-solid fa-cart-shopping"></i></span></a>
                                </li>
                                <li class="nav-item p-2">
                                    <a class="nav-link text-black position-relative" href="UserProfile.php"><i
                                            class="fa-solid fa-user"></i></a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-align-right"></i>
                                    </a>
                                    <ul class="dropdown-menu p-5">
                                        <li class="text-center mb-3">
                                            <a class="btn btn-secondary" href="track_order.php" role="button">Track Your Order</a>
                                        </li>
                                        <li class="text-center mb-3">
                                            <a class="btn btn-danger" href="logout.php" role="button">Logout</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="Home" class="container p-5 heading-container align-items-center rounded-5 position-relative">
                <div class="row d-flex justify-content-between w-100 position-relative">
                    <div class="col-md-6">
                        <div class="container">
                            <div class="buy-text">
                                <h2>
                                    Buy your <br />
                                    Medicines and <br />cosmetics
                                </h2>
                            </div>
                            <div class="box-adjusting d-flex justify-content-between align-items-center w-75">
                                <div class="items-text">
                                    <h4>
                                        <span>100+</span> <br />
                                        <span class="text">Item</span>
                                    </h4>
                                </div>
                                <div class="line-txt">
                                    <span><i class="fa-solid fa-grip-lines-vertical"></i></span>
                                </div>
                                <div class="customers-text">
                                    <h4>
                                        <span>100+</span> <br />
                                        <span class="text">Customers</span>
                                    </h4>
                                </div>
                            </div>
                            <div class="search-bar">
                                <form class="d-flex w-100 justify-content-between p-2" role="search">
                                    <input class="form-control input-adjust rounded-start-4" type="search"
                                        placeholder="What Category you are looking for?" aria-label="Search" />
                                    <button class="btn btn-secondary rounded-5" type="submit">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 position-relative">
                        <div class="container position-relative">
                            <div class="image-in-background position-relative w-100">
                                <img src="images/modified_cross_without_white_shape.png" alt="" width="100%" />
                            </div>
                            <div class="white-rectangle w-100">
                                <img src="images/rectangle2.png" width="85%" alt="" />
                            </div>
                            <div class="pharma-logo w-100">
                                <img src="images/624dd0a951a1e8a118215b1b24a0da59-pharmacy-logo.webp" width="60%" alt="" />
                            </div>
                            <div class="arrow-left w-100">
                                <img src="images/arrow.png" width="35%" alt="" />
                            </div>
                            <div class="arrow-right w-100">
                                <img src="images/arrow2.png" width="15%" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="Products" class="container pt-5 mx-auto my-5">
                <div class="row">
                    <div class="col-md-3">
                        <div class="container d-flex align-items-start flex-column">
                            <div class="text-best-selling">
                                <h4>
                                    Best Selling <br />
                                    items
                                </h4>
                            </div>
                            <div class="paragraph-inside-selling text-8e8e8e">
                                <p>
                                    Easiest way to healthy life by buying your favorite
                                    cosmetics
                                </p>
                            </div>
                            <div class="see-more-btn">
                                <a href="Product.php">
                                    <button type="button" class="btn btn-secondary btn">
                                        See more
                                        <span><i class="fa-solid fa-arrow-right-long"></i></span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="image-product">
                            <img
                                src="images/Head-Shoulders-Anti-Dandruff-Shampoo-Itchy-Scalp-23-7-fl-oz_55d7e9c4-b93e-4e2b-9382-129b649c345c.png"
                                alt="shampoo" width="100%" />
                        </div>
                        <div class="product-name">
                            <h5>Shampoo</h5>
                        </div>
                        <div class="product-price text-8e8e8e">
                            <p>100.LE</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="image-product">
                            <img src="images/131231.png" alt="Oil" width="100%" />
                        </div>
                        <div class="product-name">
                            <h5>Oil</h5>
                        </div>
                        <div class="product-price text-8e8e8e">
                            <p>200.LE</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="image-product">
                            <img
                                src="images/web-sizing-w-shadows-_smart-objects__0000s_0003s_0004_neurepair_conditioner_250ml_a0700592-13be-4e1c-9201-07a6c44c53cf.png"
                                alt="Conditioner" width="100%" />
                        </div>
                        <div class="product-name">
                            <h5>Conditioner</h5>
                        </div>
                        <div class="product-price text-8e8e8e">
                            <p>300.LE</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <section id="AboutUS" class="my-5 py-5">
            <div class="container my-5 d-flex flex-column align-items-center">
                <div class="heading-about-us">
                    <div class="heading text-center">
                        <h1>About Us</h1>
                    </div>
                    <div class="txt-about-us text-center">
                        <p class="text-8e8e8e">
                            Order now and appreciate the beauty of nature
                        </p>
                    </div>
                </div>
            </div>
            <div class="container d-flex flex-column justify-content-between align-items-center my-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="icons-about-us mx-auto rounded-circle my-3">
                            <span><i class="fa-solid fa-capsules"></i></span>
                        </div>
                        <div class="title-icons text-center my-3">
                            <h3>Large Assortment</h3>
                        </div>
                        <div class="para-icons text-center my-3">
                            <p class="text-8e8e8e">
                                we offer many different types of products with fewer
                                variations in each category.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="icons-about-us mx-auto rounded-circle my-3">
                            <span><i class="fa-solid fa-truck-fast"></i></span>
                        </div>
                        <div class="title-icons text-center my-3">
                            <h3>Fast & Free Shipping</h3>
                        </div>
                        <div class="para-icons text-center my-3">
                            <p class="text-8e8e8e">
                                4-day or less delivery time, free shipping and an expedited
                                delivery option.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="icons-about-us mx-auto rounded-circle my-3">
                            <span><i class="fa-solid fa-phone-volume"></i></span>
                        </div>
                        <div class="title-icons text-center my-3">
                            <h3>24/7 Support</h3>
                        </div>
                        <div class="para-icons text-center my-3">
                            <p class="text-8e8e8e">
                                answers to any business related inquiry 24/7 and in real-time.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="categories" class="mt-5">
            <div class="container pb-5">
                <div class="category-heading">
                    <div class="heading text-center">
                        <h1>Categories</h1>
                    </div>
                    <div class="txt-category text-center">
                        <p class="text-8e8e8e">Find what you are looking for</p>
                    </div>
                </div>
            </div>
            <div class="container-fluid bg-category d-flex flex-column justify-content-between align-items-center mt-5">
                <div class="row w-75">
                    <div class="col-4 d-flex position-relative justify-content-center">
                        <div class="box w-75 position-relative">
                            <div class="absolute-category">
                                <div class="img-category rounded-4 mb-4">
                                    <img src="images/kadarius-seegars-b9KAwJRBXgw-unsplash.jpg" width="100%" alt="cosmetics" />
                                </div>
                                <div class="paragraph-after-category text-center">
                                    <p>Cosmetics</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 position-relative d-flex justify-content-center">
                        <div class="box w-75 position-relative">
                            <div class="img-category position-relative rounded-4 mb-4">
                                <img src="images/sincerely-media-X2BZgaPkQRs-unsplash.jpg" width="100%" alt="Shampoo" />
                            </div>
                            <div class="paragraph-after-category text-center">
                                <p>Shampoo</p>
                            </div>
                            <div class="details-explore-more text-8e8e8e text-center">
                                <p>
                                    Horem ipsum dolor sit amet, consectetur adipiscing elit.
                                </p>
                            </div>
                            <div class="explore-btn text-center">
                                <a class="text-decoration-none text-black" href="signin.php">
                                    <button type="button" class="btn btn-white btn-lg">
                                        Explore
                                        <span><i class="fa-solid fa-arrow-right-long"></i></span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 position-relative d-flex justify-content-center">
                        <div class="box w-75 position-relative">
                            <div class="absolute-category">
                                <div class="img-category position-relative rounded-4 mb-4">
                                    <img src="images/yulia-matvienko-GUE3mxAVrF8-unsplash.jpg" width="100%" alt="Medicines" />
                                </div>
                                <div class="paragraph-after-category text-center">
                                    <p>Medicines</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="location" class="my-5">
            <div class="container my-4 d-flex flex-column align-items-center">
                <div class="heading-location my-3 heading">
                    <h1>Our Location</h1>
                    <div class="paragraph-location">
                        <p class="text-8e8e8e text-center">To Find Us Easily</p>
                    </div>
                </div>
                <div class="map my-4">
                    <div style="width:100%; display: flex; justify-content: center;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d27575.89749211341!2d31.405706374316406!3d30.23744189999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14580fc780e4c58d%3A0xf7603f88f80b218!2sSakr%20Pharmacy!5e0!3m2!1sen!2seg!4v1747771839616!5m2!1sen!2seg"
                            width="1200"
                            height="450"
                            style="border:0; border-radius:20px;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </section>
        <section id="reviews" class="my-5">
            <div class="container my-4 d-flex flex-column align-items-center">
                <div class="heading heading-reviews">
                    <h1>Reviews</h1>
                </div>
                <div class="paragraph-reviews">
                    <p class="text-8e8e8e text-center">What our customers say</p>
                </div>
            </div>
            <!-- Elfsight All-in-One Reviews | Untitled All-in-One Reviews -->
            <script src="https://static.elfsight.com/platform/platform.js" async></script>
            <div class="elfsight-app-f4421881-cda0-4659-8f56-a1b2c7c4a773" data-elfsight-app-lazy></div>
        </section>
        <footer class="p-5">
            <div class="container-fluid w-100">
                <div class="row w-75 mx-auto justify-content-between">
                    <div class="col-md-4">
                        <div class="brand-name my-4">
                            <p>Sakr Pharma</p>
                        </div>
                        <div class="social-media-para text-8e8e8e my-4">
                            <p>We help you find your Medicines and Cosmetics</p>
                        </div>
                        <div class="social-media-links col-md-8">
                            <ul class="list-unstyled d-flex justify-content-between flex-row">
                                <li class="d-flex justify-content-center">
                                    <a href=""><span><i class="fa-brands fa-facebook-f"></i></span></a>
                                </li>
                                <li class="d-flex justify-content-center">
                                    <a href=""><span><i class="fa-brands fa-instagram"></i></span></a>
                                </li>
                                <li class="d-flex justify-content-center">
                                    <a href=""><span><i class="fa-brands fa-x-twitter"></i></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="container d-flex flex-row justify-content-between">
                            <ul class="list-unstyled my-4">
                                <li>
                                    <a class="text-decoration-none" href="">
                                        <h5 class="text-black">Information</h5>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-decoration-none" href="">
                                        <h5 class="text-8e8e8e">About</h5>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-decoration-none" href="">
                                        <h5 class="text-8e8e8e">Product</h5>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-decoration-none" href="">
                                        <h5 class="text-8e8e8e">Blog</h5>
                                    </a>
                                </li>
                            </ul>
                            <ul class="list-unstyled my-4">
                                <li>
                                    <a class="text-decoration-none" href="">
                                        <h5 class="text-black">Company</h5>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-decoration-none" href="">
                                        <h5 class="text-8e8e8e">Community</h5>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-decoration-none" href="">
                                        <h5 class="text-8e8e8e">Career</h5>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-decoration-none" href="">
                                        <h5 class="text-8e8e8e">Our story</h5>
                                    </a>
                                </li>
                            </ul>
                            <ul class="list-unstyled my-4">
                                <li>
                                    <a class="text-decoration-none" href="">
                                        <h5 class="text-black">Contact</h5>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-decoration-none" href="">
                                        <h5 class="text-8e8e8e">Getting Started</h5>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-decoration-none" href="">
                                        <h5 class="text-8e8e8e">Pricing</h5>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-decoration-none" href="">
                                        <h5 class="text-8e8e8e">Resources</h5>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </main>
    <script src="js/index.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>