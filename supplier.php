<?php
include 'includes/init.php';
$message = [];

if (isset($_POST['btnSave'])) {
    if ($_POST['action'] == 'insert') {
        $data = [
            "supplier_name" => trim(escape($_POST['name'])),
            "contact_person" => trim(escape($_POST['contact_person'])),
            "phone" => trim(escape($_POST['phone'])),
            "email" => trim(escape($_POST['email']))
        ];

        if (insert('suppliers', $data)) {
            $message = ["Successfully inserted!", "success"];
        } else {
            $message = ["Sorry! Something went wrong", "danger"];
        }
    } else if ($_POST['action'] == 'update') {
        $data = [
            "id" => trim(escape($_POST['supplierid'])), 
            "supplier_name" => trim(escape($_POST['name'])),
            "contact_person" => trim(escape($_POST['contact_person'])),
            "phone" => trim(escape($_POST['phone'])),
            "email" => trim(escape($_POST['email']))
        ];

        if (update('suppliers', $data)) {
            $message = ["Successfully Updated!", "success"];
        } else {
            $message = ["Sorry! Something went wrong", "danger"];
        }
    }
}

if (isset($_POST['btnDelete'])) {
    if (delete('suppliers', $_POST['supplier_id'])) {
        $message = ["Successfully Deleted!", "success"];
    } else {
        $message = ["Sorry! Something went wrong", "danger"];
    }
}
?>

<main id="main" class="main">
    <section class="section">
        <div class="container mt-4">
            
        </div>
        <div class="pagetitle text-center mt-5 p-2">
            <h1>our suppliers</h1>
        </div>
        <div class="alerts">
            <?php $message ? showMessage($message) : ""; ?>
        </div>

        <div class="row">
            <div class="card-header py-3 ">
                <button type="button" class="btn" style="background-color: #603F26; color:white; margin-left:20px;" data-bs-toggle="modal"
                    data-bs-target="#supplierModal" onclick="reset()">
                    Add new supplier
                </button>
            </div>
            <div class="col-lg-12 p-4">
                <div class="card">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th><b>id</b></th>
                                <th>supplier_name</th>
                                <th>contact_person</th>
                                <th>phone</th>
                                <th>email</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (read('suppliers') as $supplier) { ?>
                            <tr>
                                <td><?= $supplier['id']; ?></td>
                                <td><?= $supplier['supplier_name']; ?></td>
                                <td><?= $supplier['contact_person']; ?></td>
                                <td><?= $supplier['phone']; ?></td>
                                <td><?= $supplier['email']; ?></td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-primary"
                                        onclick="fillForm(<?= $supplier['id']; ?>, '<?= $supplier['supplier_name']; ?>', '<?= $supplier['contact_person']; ?>', '<?= $supplier['phone']; ?>', '<?= $supplier['email']; ?>')">
                                        <i class="bi bi-pencil"></i> Update
                                    </a>
                                    <a href="#deleteModal" data-bs-toggle="modal" class="btn btn-danger"
                                        onclick="setId(<?= $supplier['id']; ?>)">
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

<!-- Supplier Modal -->
<div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplier_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="supplierForm">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="supplier_modal_label">Add New Supplier</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="action" value="insert">
                    <input type="hidden" name="supplierid" id="supplierid" value="0">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" placeholder="Supplier Name" name="name" id="name">
                        <span class="text-danger" id="nameError"></span>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" placeholder="Contact Person" name="contact_person"
                            id="contact_person">
                        <span class="text-danger" id="contact_personError"></span>
                    </div>
                    <div class="form-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                        <span class="text-danger" id="emailError"></span>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" placeholder="Phone" name="phone" id="phone">
                        <span class="text-danger" id="phoneError"></span>
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
                <h5 class="modal-title">Delete Supplier</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="deleteForm">
                <div class="modal-body">
                    <input type="hidden" name="supplier_id" id="supplier_id"> <!-- Corrected to hidden field -->
                    <p class="lead">Are you sure you want to delete this supplier?</p>
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
    document.getElementById('supplierModal').querySelector('.modal-title').textContent = 'Add New Supplier';
    document.getElementById('action').value = 'insert';
    document.getElementById('supplierid').value = '0';
    document.getElementById('name').value = '';
    document.getElementById('contact_person').value = '';
    document.getElementById('phone').value = '';
    document.getElementById('email').value = '';
}

function setId(id) {
    document.getElementById('supplier_id').value = id;
}

function fillForm(id, name, contact_person, phone, email) {
    // Update the modal's title and form fields
    document.getElementById('supplierModal').querySelector('.modal-title').textContent = 'Update Supplier';
    document.getElementById('action').value = 'update';
    document.getElementById('supplierid').value = id;
    document.getElementById('name').value = name;
    document.getElementById('contact_person').value = contact_person;
    document.getElementById('phone').value = phone;
    document.getElementById('email').value = email;

    // Ensure modal is properly initialized and displayed
    const supplierModal = new bootstrap.Modal(document.getElementById('supplierModal'), {
        
    });
    supplierModal.show(); // Show the modal
}

removeAlert();
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
