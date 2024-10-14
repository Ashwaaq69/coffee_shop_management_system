<?php

include 'includes/init.php';
$message = [];

if (isset($_POST['btnSave'])) {
    $data = [
        "supplier_id" => trim(escape($_POST['supplier_id'])),
        "product_id" => trim(escape($_POST['product_id'])),
        "quantity" => trim(escape($_POST['quantity'])),
        "purchase_date" => trim(escape($_POST['purchase_date']))
    ];

    // Check for existing product and supplier
    $productExists = read_where('products', 'id = ' . $data['product_id']);
    $supplierExists = read_where('suppliers', 'id = ' . $data['supplier_id']);

    if (empty($productExists)) {
        $message = ["Product ID does not exist.", "danger"];
    } elseif (empty($supplierExists)) {
        $message = ["Supplier ID does not exist.", "danger"];
    } else {
        if (insert('purchase', $data)) {
            $message = ["Successfully inserted!", "success"];
        } else {
            $message = ["Sorry! Something went wrong", "danger"];
        }
    }
}


if (isset($_POST['btnDelete'])) {
    if (delete('purchase', $_POST['purchase_id'])) {
        $message = ["Successfully Deleted!", "success"];
    } else {
        $message = ["Sorry! Something went wrong", "danger"];
    }
}

?>
<main id="main" class="main">
    <section class="section">
        <div class="container mt-4"></div>
        <div class="pagetitle text-center mt-5 p-2">
            <h1>Our Suppliers</h1>
        </div>
        
        <div class="alerts">
            <?php $message ? showMessage($message) : ""; ?>
        </div>

        <div class="row">
            <div class="card-header py-3 ">
                <button type="button" class="btn" style="background-color: #603F26; color:white; margin-left:20px;" data-bs-toggle="modal" data-bs-target="#purchaseModal" onclick="resetForm()">
                    Add New Purchase
                </button>
            </div>
           
            <div class="col-lg-12 p-4">
                <div class="card">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th><b>Purchase ID</b></th>
                                <th>Supplier ID</th>
                                <th>Product ID</th>
                                <th>Quantity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (read('purchase') as $purchase) { ?>
                            <tr>
                                <td><?= $purchase['id']; ?></td>
                                <td><?= read_column('suppliers', 'supplier_name', $purchase['supplier_id']); ?></td>
                                <td><?= read_column('products', 'product_name', $purchase['product_id']); ?></td>
                                <td><?= $purchase['quantity']; ?></td>
                                <td>
                                    <!-- Edit Button -->
                                    <a href="#purchaseModal" data-bs-toggle="modal" class="btn btn-primary"
                                        onclick="fillForm(<?= $purchase['id']; ?>, <?= $purchase['supplier_id']; ?>, <?= $purchase['product_id']; ?>, <?= $purchase['quantity']; ?>, '<?= $purchase['purchase_date']; ?>')">
                                        <i class="bi bi-pencil"></i> Update
                                    </a>

                                    <!-- Delete Button -->
                                    <a href="#deleteModal" data-bs-toggle="modal" class="btn btn-danger" onclick="setId(<?= $purchase['id']; ?>)">
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

<!-- Purchase Modal -->
<div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="purchase_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="purchaseForm">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="purchase_modal_label">Add New Purchase</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="action" value="insert">
                    <input type="hidden" name="purchaseid" id="purchaseid" value="0">

                    <div class="form-group mb-3">
                        <input type="text" class="form-control" placeholder="Supplier ID" name="supplier_id" id="supplier_id" required>
                        <span class="text-danger" id="supplierError"></span>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" placeholder="Product ID" name="product_id" id="product_id" required>
                        <span class="text-danger" id="productError"></span>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" class="form-control" placeholder="Quantity" name="quantity" id="quantity" required>
                        <span class="text-danger" id="quantityError"></span>
                    </div>
                    <!-- Uncomment this field if you need to add purchase date -->
                    <!-- <div class="form-group mb-3">
                        <input type="date" class="form-control" placeholder="Purchase Date" name="purchase_date" id="purchase_date">
                        <span class="text-danger" id="dateError"></span>
                    </div> -->
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
                <h5 class="modal-title">Delete Purchase</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="deleteForm">
                <div class="modal-body">
                    <input type="hidden" name="purchase_id" id="purchase_id">
                    <p class="lead">Are you sure you want to delete this purchase?</p>
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

function resetForm() {
    document.getElementById('purchase_modal_label').textContent = 'Add New Purchase';
    document.getElementById('action').value = 'insert';
    document.getElementById('purchaseid').value = '0';
    document.getElementById('supplier_id').value = '';
    document.getElementById('product_id').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('purchase_date').value = '';
}

function setId(id) {
    document.getElementById('purchase_id').value = id;
}

function fillForm(id, supplier_id, product_id, quantity, purchase_date) {
    document.getElementById('purchase_modal_label').textContent = 'Update Purchase';
    document.getElementById('action').value = 'update';
    document.getElementById('purchaseid').value = id;
    document.getElementById('supplier_id').value = supplier_id;
    document.getElementById('product_id').value = product_id;
    document.getElementById('quantity').value = quantity;
    document.getElementById('purchase_date').value = purchase_date;

    const purchaseModal = new bootstrap.Modal(document.getElementById('purchaseModal'));
    purchaseModal.show();
}
</script>

<!-- Bootstrap CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
