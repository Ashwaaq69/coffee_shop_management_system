<?php
include 'includes/init.php';
$message = [];

if (isset($_POST['btnSave'])) {
    if ($_POST['action'] == 'insert') {
        $data = [
            "product_id" => trim(escape($_POST['product_id'])),
            "quantity" => trim(escape($_POST['quantity']))
        ];

        if (insert('inventory', $data)) {
            $message = ["Successfully inserted!", "success"];
        } else {
            $message = ["Sorry! Something went wrong", "danger"];
        }
    } else if ($_POST['action'] == 'update') {
        $data = [
            "id" => trim(escape($_POST['inventoryid'])), // Corrected inventory_id
            "product_id" => trim(escape($_POST['product_id'])),
            "quantity" => trim(escape($_POST['quantity']))
        ];

        if (update('inventory', $data)) {
            $message = ["Successfully Updated!", "success"];
        } else {
            $message = ["Sorry! Something went wrong", "danger"];
        }
    }
}

if (isset($_POST['btnDelete'])) {
    if (delete('inventory', $_POST['inventory_id'])) {
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
            <h1>Inventory Management</h1>
        </div>
        <div class="alerts">
            <?php $message ? showMessage($message) : ""; ?>
        </div>

        <div class="row">
            <div class="card-header py-3 ">
                <button type="button" class="btn" style="background-color: #603F26; color:white; margin-left:20px;" data-bs-toggle="modal"
                    data-bs-target="#inventoryModal" onclick="reset()">
                    inventory
                </button>
            </div>
            <div class="col-lg-12 p-4">
                <div class="card">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th><b>ID</b></th>
                                <th>Product ID</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (read('inventory') as $inventory) { ?>
                            <tr>
                                <td><?= $inventory['id']; ?></td>
                                <td><?= $inventory['product_id']; ?></td>
                                <td><?= $inventory['quantity']; ?></td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-primary"
                                        onclick="fillForm(<?= $inventory['id']; ?>, '<?= $inventory['product_id']; ?>', <?= $inventory['quantity']; ?>)">
                                        <i class="bi bi-pencil"></i> Update
                                    </a>
                                    <a href="#deleteModal" data-bs-toggle="modal" class="btn btn-danger"
                                        onclick="setId(<?= $inventory['id']; ?>)">
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

<!-- Inventory Modal -->
<div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventory_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="inventoryForm">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="inventory_modal_label">Add New Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="action" value="insert">
                    <input type="hidden" name="inventoryid" id="inventoryid" value="0">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" placeholder="Product ID" name="product_id" id="product_id">
                        <span class="text-danger" id="product_idError"></span>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" class="form-control" placeholder="Quantity" name="quantity" id="quantity">
                        <span class="text-danger" id="quantityError"></span>
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
                <h5 class="modal-title">Delete Item</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="deleteForm">
                <div class="modal-body">
                    <input type="hidden" name="inventory_id" id="inventory_id"> <!-- Corrected to hidden field -->
                    <p class="lead">Are you sure you want to delete this item?</p>
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
function reset() {
    document.getElementById('inventoryModal').querySelector('.modal-title').textContent = 'Add New Item';
    document.getElementById('action').value = 'insert';
    document.getElementById('inventoryid').value = '0';
    document.getElementById('product_id').value = '';
    document.getElementById('quantity').value = '';
}

function setId(id) {
    document.getElementById('inventory_id').value = id;
}

function fillForm(id, product_id, quantity) {
    document.getElementById('inventoryModal').querySelector('.modal-title').textContent = 'Update Item';
    document.getElementById('action').value = 'update';
    document.getElementById('inventoryid').value = id;
    document.getElementById('product_id').value = product_id;
    document.getElementById('quantity').value = quantity;

    const inventoryModal = new bootstrap.Modal(document.getElementById('inventoryModal'));
    inventoryModal.show();
}

removeAlert();
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
