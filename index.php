<?php
require_once 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$productCRUD = new ProductCRUD();
$cartCRUD = new CartCRUD();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        if (isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
            $cartCRUD->addToCart($user_id, $_POST['product_id']);
            header("Location: index.php");
            exit;
        } elseif (isset($_POST['action']) && $_POST['action'] === 'buy_now') {
            $cartCRUD->buyNow($user_id, $_POST['product_id']);
            header("Location: confirmation.php"); // Redirect to a confirmation page after purchase
            exit;
        }
    }
}

$products = $productCRUD->readProducts();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Hulom Midterm - Products</title>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">Hulom Midterm</div>
            <ul class="nav-links">
                <li><a href="index.php"><i class="fa-solid fa-bag-shopping"></i> Products</a></li>
                <a href="cart.php"><i class="fas fa-shopping-cart"></i> Carts</a>
            </ul>
            <div class="cart">
                <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </nav>
    </header>

    <main>
        <section class="products">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($product->image_url); ?>"
                    alt="<?= htmlspecialchars($product->product_name); ?>">
                <h3><?= htmlspecialchars($product->product_name); ?></h3>
                <p>$<?= number_format($product->price, 2); ?></p>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <form action="index.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product->product_id; ?>">
                        <button type="submit" name="action" value="add_to_cart" class="add-to-cart-btn">
                            Add to Cart <i class="fas fa-cart-plus"></i>
                        </button>
                    </form>
                    <form action="index.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product->product_id; ?>">
                        <button type="submit" name="action" value="buy_now" class="buy-now-btn">
                            Buy Now <i class="fas fa-credit-card"></i>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
    </main>
</body>

</html>