<?php
include 'includes/init.php'; // Ensure you have the session started in 'init.php'
?>

<main id="main" class="main" style="background-color: #F7DCB9;">
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Product List</h2>
            <a href="addCart.php" id="viewCartBtn" class="btn btn-light position-relative">
                <i class="bi bi-cart-fill"></i> View Cart
                <span id="cartCount"
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0; ?>
                </span>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Example for Product Display -->
        <?php
        $products = [
            ['id' => 7, 'name' => 'turkish coffee', 'price' => 4.30],
            ['id' => 8, 'name' => 'leto', 'price' => 5],
            ['id' => 9, 'name' => 'Esperso', 'price' => 6],
        ];

        foreach ($products as $product): ?>
        <div class="col-md-3">
            <div class="card h-60">
                <img src="assets/img/menu4.png" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                    <p class="card-text">
                        <span class="text-success">price: $<?php echo $product['price']; ?></span>
                    </p>
                    <a href="#" class="add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>"
                        data-product-name="<?php echo $product['name']; ?>"
                        data-price="<?php echo $product['price']; ?>"
                        style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Add
                        to Cart</a>


                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.add-to-cart-btn').click(function(e) {
        e.preventDefault(); // Prevent the default anchor action

        var product_id = $(this).data('product-id');
        var product_name = $(this).data('product-name');
        var price = $(this).data('price');

        $.ajax({
            url: 'addCart.php', // URL of the PHP script that handles cart addition
            type: 'POST',
            data: {
                add_to_cart: true,
                product_id: product_id,
                product_name: product_name,
                price: price
            },
            success: function(response) {
                // Parse the returned JSON
                var result = JSON.parse(response);

                // Update the cart count
                $('#cart-count').text(result.cartCount);

                alert('Product added to cart!');
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });
});
</script>
