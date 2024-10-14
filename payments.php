<?php 
include 'includes/init.php'; // Initialize any required settings
// include 'connection.php'; // Include your database connection file

$action = $_POST['action'] ?? null;

if ($action == 'addPayment') {
    $order_id = $_POST['order_id'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO payments (order_id, amount) VALUES (?, ?)");
    if ($stmt->execute([$order_id, $amount])) {
        echo json_encode(['success' => true, 'message' => 'Payment added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add payment.']);
    }
} elseif ($action == 'editPayment') {
    $payment_id = $_POST['payment_id'];
    $order_id = $_POST['order_id'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("UPDATE payments SET order_id = ?, amount = ? WHERE payment_id = ?");
    if ($stmt->execute([$order_id, $amount, $payment_id])) {
        echo json_encode(['success' => true, 'message' => 'Payment updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update payment.']);
    }
} elseif ($action == 'deletePayment') {
    $payment_id = $_POST['payment_id'];

    $stmt = $conn->prepare("DELETE FROM payments WHERE payment_id = ?");
    if ($stmt->execute([$payment_id])) {
        echo json_encode(['success' => true, 'message' => 'Payment deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete payment.']);
    }
} elseif ($action == 'getPayment') {
    $payment_id = $_GET['payment_id'];

    $stmt = $conn->prepare("SELECT * FROM payments WHERE payment_id = ?");
    $stmt->execute([$payment_id]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($payment);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main id="main">
        <div class="container mt-5">
            <h2>Payment Management</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">Add Payment</button>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Order ID</th>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch payments from the database
                    $payments = $conn->query("SELECT * FROM payments");
                    while ($payment = $payments->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                            <td>{$payment['payment_id']}</td>
                            <td>{$payment['order_id']}</td>
                            <td>{$payment['payment_date']}</td>
                            <td>{$payment['amount']}</td>
                            <td>
                                <button class='btn btn-warning edit-btn' data-id='{$payment['payment_id']}'>Edit</button>
                                <button class='btn btn-danger delete-btn' data-id='{$payment['payment_id']}'>Delete</button>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    
    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addPaymentForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Order ID</label>
                            <input type="text" class="form-control" id="order_id" name="order_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Payment Modal -->
    <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editPaymentForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_payment_id" name="payment_id">
                        <div class="mb-3">
                            <label for="edit_order_id" class="form-label">Order ID</label>
                            <input type="text" class="form-control" id="edit_order_id" name="order_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="edit_amount" name="amount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    // JavaScript/jQuery for handling modal actions
    $(document).ready(function() {
        // Add Payment
        $('#addPaymentForm').submit(function(e) {
            e.preventDefault();
            let order_id = $('#order_id').val();
            let amount = $('#amount').val();

            $.ajax({
                url: 'payments.php', // Your PHP processing file
                type: 'POST',
                data: {
                    action: 'addPayment',
                    order_id: order_id,
                    amount: amount
                },
                success: function(response) {
                    let res = JSON.parse(response);
                    alert(res.message);
                    if (res.success) {
                        location.reload();
                    }
                },
                error: function() {
                    alert('Failed to add payment.');
                }
            });
        });

        // Edit Payment
        $('.edit-btn').click(function() {
            let payment_id = $(this).data('id');
            $.ajax({
                url: 'payments.php', // Your PHP processing file
                type: 'GET',
                data: {
                    action: 'getPayment',
                    payment_id: payment_id
                },
                success: function(response) {
                    let payment = JSON.parse(response);
                    $('#edit_payment_id').val(payment.payment_id);
                    $('#edit_order_id').val(payment.order_id);
                    $('#edit_amount').val(payment.amount);
                    $('#editPaymentModal').modal('show');
                },
                error: function() {
                    alert('Failed to fetch payment details.');
                }
            });
        });

        // Update Payment
        $('#editPaymentForm').submit(function(e) {
            e.preventDefault();
            let payment_id = $('#edit_payment_id').val();
            let order_id = $('#edit_order_id').val();
            let amount = $('#edit_amount').val();

            $.ajax({
                url: 'payments.php', // Your PHP processing file
                type: 'POST',
                data: {
                    action: 'editPayment',
                    payment_id: payment_id,
                    order_id: order_id,
                    amount: amount
                },
                success: function(response) {
                    let res = JSON.parse(response);
                    alert(res.message);
                    if (res.success) {
                        location.reload();
                    }
                },
                error: function() {
                    alert('Failed to update payment.');
                }
            });
        });

        // Delete Payment
        $('.delete-btn').click(function() {
            if (confirm('Are you sure you want to delete this payment?')) {
                let payment_id = $(this).data('id');
                $.ajax({
                    url: 'payments.php', // Your PHP processing file
                    type: 'POST',
                    data: {
                        action: 'deletePayment',
                        payment_id: payment_id
                    },
                    success: function(response) {
                        let res = JSON.parse(response);
                        alert(res.message);
                        if (res.success) {
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('Failed to delete payment.');
                    }
                });
            }
        });
    });
    </script>
</body>
</html>
