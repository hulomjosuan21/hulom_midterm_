<?php
require_once 'connection.php';

$cartCRUD = new CartCRUD();
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'remove') {
            $cartCRUD->removeFromCart($_POST['product_id']);
        } elseif ($_POST['action'] == 'clear') {
            $cartCRUD->clearCart($user_id);
        } elseif ($_POST['action'] == 'checkout') {
            $cartCRUD->checkoutItem($user_id, $_POST['product_id']);
        } elseif ($_POST['action'] == 'checkout_all') {
            $cartCRUD->checkoutAllItems($user_id); // Checkout all items
        }
    }
}

$cartItems = $cartCRUD->getCartItems($user_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Shopping Cart</title>
</head>

<body>
    <header>
        <nav class="navbar">
            <a class="a-btn" href="index.php"><i class="fa-solid fa-chevron-left"></i> Back</a>
            <h1>Shopping Cart</h1>
        </nav>
    </header>

    <main>
        <section class="cart-container">
            <?php if (count($cartItems) > 0): ?>
            <?php foreach ($cartItems as $item): ?>
            <div class="cart-item">
                <img src="<?= htmlspecialchars($item->image_url); ?>"
                    alt="<?= htmlspecialchars($item->product_name); ?>" class="item-image">
                <div class="item-details">
                    <h3 class="item-name"><?= htmlspecialchars($item->product_name); ?></h3>
                    <p class="item-price">Price: $<?= number_format($item->price, 2); ?></p>
                    <p class="item-quantity">Quantity: <?= $item->quantity; ?></p>
                    <form action="cart.php" method="POST" class="item-actions">
                        <input type="hidden" name="product_id" value="<?= $item->product_id; ?>">
                        <button type="submit" name="action" value="remove" class="btn remove-btn">
                            Remove <i class="fa-solid fa-trash"></i>
                        </button>
                        <button type="submit" name="action" value="checkout" class="btn checkout-btn">
                            Checkout <i class="fa-solid fa-check"></i>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="checkout-all">
                <form action="cart.php" method="POST" class="clear-cart-form">
                    <button type="submit" name="action" value="clear" class="btn clear-cart-btn">Clear Cart</button>
                    <button type="submit" name="action" value="checkout_all" class="btn checkout-all-btn">Checkout
                        All</button>
                </form>
            </div>
            <?php else: ?>
            <p class="empty-cart-message">Your cart is empty.</p>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>