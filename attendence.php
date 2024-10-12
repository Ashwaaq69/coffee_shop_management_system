<?php
include 'includes/init.php';
$message = [];

// Check if the form to save (insert/update) is submitted
if (isset($_POST['btnSave'])) {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'update') {
            $data = [
                "id" => trim(escape($_POST['attendance_id'])), // Ensure attendance_id is correctly used
                "employee_id" => trim(escape($_POST['employee_id'])),
                "attendance_date" => trim(escape($_POST['attendance_date'])),
                "status" => trim(escape($_POST['status'])),
            ];

            // Update action
            if (update('employeeattendance', $data, 'id')) { // Ensure 'id' matches the column name
                $message = ["Attendance successfully updated!", "success"];
            } else {
                $message = ["Sorry! Something went wrong while updating attendance.", "danger"];
            }
        } elseif ($_POST['action'] == 'insert') {
            $data = [
                "employee_id" => trim(escape($_POST['employee_id'])),
                "attendance_date" => trim(escape($_POST['attendance_date'])),
                "status" => trim(escape($_POST['status'])),
            ];

            // Insert action
            if (insert('employeeattendance', $data)) { // Call the insert function
                $message = ["Attendance successfully added!", "success"];
            } else {
                $message = ["Sorry! Something went wrong while adding attendance.", "danger"];
            }
        }
    }
}

// Check if the delete button is pressed
if (isset($_POST['btnDelete'])) {
    if (delete('employeeattendance', $_POST['attendance_id'])) {
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
            <h1>Employee Attendance</h1>
        </div>
        <div class="alerts">
            <?php $message ? showMessage($message) : ""; ?>
        </div>

        <div class="row">
            <div class="card-header py-3 ">
                <button type="button" class="btn" style="background-color: #603F26; color:white; margin-left:20px;"
                    data-bs-toggle="modal" data-bs-target="#attendanceModal" onclick="reset()">
                    Add new attendance
                </button>
            </div>
            <div class="col-lg-12 p-4">
                <div class="card">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th><b>Attendance ID</b></th>
                                <th>Employee Name</th>
                                <th>Attendance Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach (read('employeeattendance') as $attendance) {
                            ?>
                            <tr>
                                <td> <?= $attendance['id']; ?></td>
                                <td><?= read_column('employees', 'name', $attendance['employee_id']); ?></td>
                                <td><?= $attendance['attendance_date']; ?></td>
                                <td><?= $attendance['status']; ?></td>
                                <td>
                                    <!-- Edit Attendance Button -->
                                    <a href="#attendanceModal" data-bs-toggle="modal" class="btn btn-primary"
                                        onclick="fillForm(<?= $attendance['id']; ?>, <?= $attendance['employee_id']; ?>, '<?= $attendance['attendance_date']; ?>', '<?= $attendance['status']; ?>')">
                                        <i class="bi bi-pencil"></i> Update
                                    </a>

                                    <!-- Delete Attendance Button -->

                                    <a href="#deleteModal" data-bs-toggle="modal" class="btn btn-danger"
                                        onclick="setId(<?= $attendance['id']; ?>)">
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

<!-- Attendance Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendance_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="attendanceForm">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="attendance_modal_label">Add New Attendance</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="action" value="insert">
                    <input type="hidden" name="attendance_id" id="attendance_id" value="0">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" placeholder="Employee ID" name="employee_id"
                            id="employee_id" required>
                        <span class="text-danger" id="employeeError"></span>
                    </div>
                    <div class="form-group mb-3">
                        <input type="date" class="form-control" placeholder="Attendance Date" name="attendance_date"
                            id="attendance_date" required>
                        <span class="text-danger" id="dateError"></span>
                    </div>
                    <div class="form-group mb-3">
                        <select class="form-control" name="status" id="status" required>
                            <option value="">Select Status</option>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                        </select>
                        <span class="text-danger" id="statusError"></span>
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
                <h5 class="modal-title">Delete Attendance</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="deleteForm">
                <div class="modal-body">
                    <input type="text" name="attendance_id" id="attendance_id">
                    <p class="lead">Are you sure you want to delete this attendance record?</p>
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
    document.getElementById('attendanceModal').querySelector('.modal-title').textContent =
        'Add New Attendance';
    document.getElementById('action').value = 'insert';
    document.getElementById('attendance_id').value = '0'; // Correct ID reference
    document.getElementById('employee_id').value = '';
    document.getElementById('attendance_date').value = '';
    document.getElementById('status').value = '';
}

function setId(id) {
    document.getElementById('attendance_id').value = id;
}

function fillForm(attendance_id, employee_id, attendance_date, status) {
    document.getElementById('attendanceModal').querySelector('.modal-title').textContent = 'Update Attendance';
    document.getElementById('attendance_id').value = attendance_id;
    document.getElementById('employee_id').value = employee_id;
    document.getElementById('attendance_date').value = attendance_date;
    document.getElementById('status').value = status;

    // Show the modal
    const attendanceModal = new bootstrap.Modal(document.getElementById('attendanceModal'), {});
    attendanceModal.show();
}
</script>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS (Popper.js and Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>