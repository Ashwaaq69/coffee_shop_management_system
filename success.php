<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .success-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
        }

        .success-header {
            font-size: 2rem;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .success-message {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .btn-home {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-home:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="success-container">
        <h1 class="success-header">Order Placed Successfully!</h1>
        <p class="success-message">Thank you for your purchase! Your order has been placed and is being processed.</p>
        <a href="index.php" class="btn-home">Back to Home</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>