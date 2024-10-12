<?php
include 'includes/init.php';
session_start();

// Handle product removal
if (isset($_GET['remove'])) {
    $remove_index = $_GET['remove'];

    // Check if the index exists in the cart
    if (isset($_SESSION['cart'][$remove_index])) {
        // Remove the item from the cart
        unset($_SESSION['cart'][$remove_index]);
        
        // Re-index the cart array
        $_SESSION['cart'] = array_values($_SESSION['cart']);

        // Update cart count
        $_SESSION['cart_count'] = count($_SESSION['cart']);
    }
}

// Add to cart logic
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];      
    $product_name = $_POST['product_name'];  
    $price = $_POST['price']; 

    // Start or check the cart session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            $cart_item['quantity']++; // Increment quantity
            $found = true;
            break;
        }
    }

    // If not found, add a new product to the cart
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => 1
        ];
    }

    // Update cart count
    $_SESSION['cart_count'] = count($_SESSION['cart']);

    // Return the cart count as JSON
    echo json_encode(['cartCount' => $_SESSION['cart_count']]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
    body {
        background-color: #e9ecef;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .cart-container {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        padding: 40px;
        margin-top: 50px;
    }

    .cart-header {
        background-color: #74512D;
        color: white;
        padding: 15px;
        border-radius: 12px 12px 0 0;
        text-align: center;
        font-size: 1.75rem;
        font-weight: bold;
    }

    .cart-item {
        margin-bottom: 15px;
        padding: 20px;
        border-radius: 10px;
        background-color: #f8f9fa;
        transition: transform 0.2s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .cart-item:hover {
        transform: scale(1.02);
    }

    .cart-total {
        font-size: 1.5rem;
        font-weight: bold;
        color: #28a745;
        text-align: right;
        margin-top: 20px;
    }

    .btn-checkout {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 1.2rem;
        display: block;
        width: 100%;
        margin-top: 20px;
        transition: background-color 0.3s ease;
    }

    .btn-checkout:hover {
        background-color: #218838;
    }

    .remove-item {
        cursor: pointer;
        color: red;
    }
    </style>
</head>

<body>
    <main id="main">
    <a href="product.php" style="background-color: #45a049; color:white; padding:4px;  text-decoration: none;">Back to product</a>
        <div class="container">
            <div class="cart-container">
                <div class="cart-header">
                    Your Cart
                </div>

                <div class="row">
                    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $key => $cart_item): ?>
                    <div class="col-md-12">
                        <div class="cart-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5><?php echo htmlspecialchars($cart_item['product_name']); ?></h5>
                                <p>Price: <strong>$<?php echo htmlspecialchars($cart_item['price']); ?></strong></p>
                                <p>Quantity: <span
                                        class="badge bg-secondary"><?php echo htmlspecialchars($cart_item['quantity']); ?></span>
                                </p>
                            </div>
                            <div>
                                <!-- Remove Button -->
                                <a href="?remove=<?php echo $key; ?>" class="remove-item" title="Remove Item">
                                    <i class="bi bi-trash" style="font-size: 1rem; ">remove</i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="col-md-12 text-center">
                        <p class="text-muted">Your cart is empty.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="cart-total">
                    <?php
                    // Calculate the total price
                    $total = 0;
                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $cart_item) {
                            $total += $cart_item['price'] * $cart_item['quantity'];
                        }
                    }
                    echo "Total: $" . number_format($total, 2);
                    ?>
                </div>

                <a href="checkout.php" class="btn-checkout" style="text-decoration: none;">Proceed to Checkout</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>