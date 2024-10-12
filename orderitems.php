<?php
include 'includes/init.php';
$message = [];
// Handle save action
if (isset($_POST['btnSave'])) {
    // Prepare data for the order items
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        // Check if the order item already exists
        $existingItem = read_where('orderitems', 'order_id = ' . $order_id . ' AND product_id = ' . trim(escape($_POST['product_id'])));
        
        if ($existingItem) {
            $message[] = ["This product is already added to the order.", "warning"];
        } else {
            $orderItemData = [
                "order_id" => $order_id,
                "product_id" => trim(escape($_POST['product_id'])),
                "quantity" => trim(escape($_POST['quantity'])),
                "price" => read_column('products', 'price', $_POST['product_id']),
                "total" => (isset($_POST['total_price']) ? trim(escape($_POST['total_price'])) : null)
            ];

            // Insert into orderitems table
            if (insert('orderitems', $orderItemData)) {
                $message[] = ["Order successfully added!", "success"];
            } else {
                $message[] = ["Failed to add order items.", "danger"];
            }
        }
    } else {
        $message[] = ["Product ID or Quantity not provided.", "danger"];
    }
}



// Handle delete action
if (isset($_POST['btnDelete'])) {
    if (delete('orderitems', $_POST['orderitem_id'])) {
        $message = ["Successfully Deleted!", "success"];
    } else {
        $message = ["Sorry! Something went wrong", "danger"];
    }
}

// Display message if any
if (!empty($message)) {
    echo "<div class='alert alert-{$message[1]}'>{$message[0]}</div>";
}
?>


<main id="main" class="main">
    <section class="section">
        <div class="container mt-4"></div>
        <div class="pagetitle text-center mt-5 p-2">
            <h1>Order Items</h1>
        </div>

        <div class="row">
            <div class="card-header py-3">
                <button type="button" class="btn" style="background-color: #603F26; color:white; margin-left:20px;"
                    data-bs-toggle="modal" data-bs-target="#orderItemModal" onclick="reset()">
                    Add New Order Item
                </button>
            </div>
            <div class="col-lg-12 p-4">
                <div class="card">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th><b>ID</b></th>
                                <th>Order_id</th>
                                <th>Produc_id</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (read('orderitems') as $orderItem) { ?>
                            <tr>
                                <td><?= $orderItem['id']; ?></td>
                                <td><?= $orderItem['order_id']; ?></td>
                                <td><?= $orderItem['product_id']; ?></td>
                                <td><?= $orderItem['quantity']; ?></td>
                                <td><?= $orderItem['price']; ?></td>
                                <td><?= $orderItem['total']; ?></td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-primary"
                                        onclick="fillForm(<?= $orderItem['id']; ?>, '<?= $orderItem['order_id']; ?>', '<?= $orderItem['product_id']; ?>', '<?= $orderItem['quantity']; ?>', '<?= $orderItem['price']; ?>', '<?= $orderItem['total']; ?>')">
                                        <i class="bi bi-pencil"></i> Update
                                    </a>

                                    <a href="#deleteModal" data-bs-toggle="modal" class="btn btn-danger"
                                        onclick="setId(<?= $orderItem['id']; ?>)">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Order Item Modal -->
<div class="modal fade" id="orderItemModal" tabindex="-1" aria-labelledby="order_item_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="orderItemForm">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="order_item_modal_label">Order Items</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="action" value="insert">
                    <input type="hidden" name="order_id" id="order_id" value="0">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" placeholder="Product ID" name=" product_id"
                            id=" product_id" required>
                        <span class="text-danger" id="product_idError"></span>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" class="form-control" placeholder="Quantity" name="quantity" id="quantity"
                            required>
                        <span class="text-danger" id="quantityError"></span>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" class="form-control" placeholder="Price" name="price" id="price"
                            step="0.01" required>
                        <span class="text-danger" id="priceError"></span>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" class="form-control" placeholder="Total" name="total" id="total"
                            step="0.01">
                        <span class="text-danger" id="totalError"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="btnSave">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" tabindex="-1" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-light" style="background-color: #603F26;">
                <h5 class="modal-title">Delete Order Item</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="deleteForm">
                <div class="modal-body">
                    <input type="hidden" name="orderitem_id" id="orderitem_id"> <!-- Updated hidden field name -->
                    <p class="lead">Are you sure you want to delete this order item?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger" name="btnDelete">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Function to fill the modal with existing data for update
function fillForm(id, order_id, product_id, quantity, price, total) {
    document.getElementById('action').value = 'update';
    document.getElementById('order_id').value = order_id;
    document.getElementById(' product_id').value = product_id;
    document.getElementById('quantity').value = quantity;
    document.getElementById('price').value = price;
    document.getElementById('total').value = total; // Update total if needed
    var myModal = new bootstrap.Modal(document.getElementById('orderItemModal'));
    myModal.show();
}

// Reset the modal form fields
function reset() {
    document.getElementById('action').value = 'insert';
    document.getElementById('order_id').value = 0; // Reset order_id for new entries
    document.getElementById(' product_id').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('price').value = '';
    document.getElementById('total').value = ''; // Reset total as well
}

// Set the ID for the delete action
function setId(id) {
    document.getElementById('orderitem_id').value = id; // Ensure the right ID is passed
}
</script>


<!-- Include Bootstrap CSS in the head -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Include Bootstrap JS before the closing body tag -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>