<?php 
ob_start(); // Start output buffering
session_start(); // Start the session
include 'includes/init.php'; // Include the DB connection

// Start the session at the top of the file before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle the checkout process
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the cart is not empty
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        try {
            // Start the transaction
            $conn->beginTransaction();

            // Calculate the total amount
            $total = 0;
            foreach ($_SESSION['cart'] as $cart_item) {
                $total += floatval($cart_item['price']) * intval($cart_item['quantity']);
            }

            // Insert into the orders table
            $stmt = $conn->prepare("INSERT INTO orders (total_amount, order_status, payment_method) VALUES (?, ?, ?)");
            $orderStatus = 'Pending';
            $paymentMethod = 'Online Payment';

            if (!$stmt->execute([$total, $orderStatus, $paymentMethod])) {
                throw new Exception("Error inserting order: " . implode(", ", $stmt->errorInfo()));
            }

            // Get the last inserted order ID
            $orderId = $conn->lastInsertId();

            // Insert each cart item into the orderitems table
            $itemStmt = $conn->prepare("INSERT INTO orderitems (order_id, product_id, quantity, price, total) VALUES (?, ?, ?, ?, ?)");
            foreach ($_SESSION['cart'] as $cart_item) {
                $itemTotal = floatval($cart_item['price']) * intval($cart_item['quantity']);
                if (!$itemStmt->execute([$orderId, $cart_item['product_id'], $cart_item['quantity'], $cart_item['price'], $itemTotal])) {
                    throw new Exception("Error inserting order item: " . implode(", ", $itemStmt->errorInfo()));
                }
            }

            // Commit the transaction
            $conn->commit();

            // Clear the cart
            unset($_SESSION['cart']);   
           
            // Redirect to a success page
            header('Location: success.php');
            exit();
        } catch (Exception $e) {
            // Rollback the transaction if something fails
            $conn->rollBack();
            die("Transaction failed: " . $e->getMessage());
        }
    } else {
        echo "<p>Your cart is empty.</p>";
    }
} else {
    // Display the cart contents for confirmation before submission
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f9f9f9;
    }

    .checkout-container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-top: 50px;
    }

    .checkout-header {
        background-color: #4CAF50;
        color: white;
        padding: 15px;
        border-radius: 10px 10px 0 0;
        text-align: center;
    }

    .checkout-item {
        margin-bottom: 15px;
        padding: 15px;
        border: 1px solid #e3e3e3;
        border-radius: 5px;
        background-color: #fff;
    }

    .checkout-total {
        font-size: 1.5rem;
        font-weight: bold;
        color: #4CAF50;
        text-align: right;
        margin-top: 20px;
    }

    .btn-confirm {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 1.2rem;
        display: block;
        width: 100%;
        margin-top: 20px;
    }

    .btn-confirm:hover {
        background-color: #45a049;
    }
    </style>
</head>

<body>
    <main id="main">
        <a href="addCart.php" style="background-color: #45a049; color: white; padding: 4px; text-decoration: none;">Back
            to cart</a>
        <div class="container">
            <div class="checkout-container">
                <div class="checkout-header">
                    <h2>Checkout</h2>
                </div>

                <div class="row">
                    <?php foreach ($_SESSION['cart'] as $cart_item): ?>
                    <div class="col-md-6">
                        <div class="checkout-item">
                            <h5><?php echo htmlspecialchars($cart_item['product_name']); ?></h5>
                            <p>Price: $<?php echo htmlspecialchars($cart_item['price']); ?></p>
                            <p>Quantity: <?php echo htmlspecialchars($cart_item['quantity']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="checkout-total">
                    <?php
                            $total = 0;
                            foreach ($_SESSION['cart'] as $cart_item) {
                                $total += floatval($cart_item['price']) * intval($cart_item['quantity']);
                            }
                            echo "Total: $" . number_format($total, 2);
                            ?>
                </div>

                <form method="POST" action="checkout.php">
                    <button type="submit" class="btn-confirm">Confirm and Place Order</button>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}
ob_end_flush(); // Flush and send the buffer
?>