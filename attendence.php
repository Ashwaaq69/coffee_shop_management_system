<?php
include 'includes/init.php';

// Check if a new attendance record is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveAttendance'])) {
    $employee_id = $_POST['employee_id'];
    $attendance_date = date('Y-m-d'); // Use the current date
    $status = $_POST['status'] === 'on' ? 'Present' : 'Absent';

    // Insert the new attendance record
    $data = [
        "employee_id" => $employee_id,
        "attendance_date" => $attendance_date,
        "status" => $status
    ];

    if (insert('employeeattendance', $data)) {
        echo "<script>alert('Attendance saved successfully!');</script>";
    } else {
        echo "<script>alert('Failed to save attendance.');</script>";
    }
}

// Check if AJAX request is sent to update the status
if (isset($_POST['action']) && $_POST['action'] === 'updateStatus') {
    $attendance_id = $_POST['attendance_id'];
    $status = $_POST['status'] === 'true' ? 'Present' : 'Absent';

    // Update the status in the database
    $data = [
        "id" => $attendance_id,
        "status" => $status
    ];

    if (update('employeeattendance', $data, 'id')) {
        echo json_encode(["success" => true, "message" => "Status updated successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update status."]);
    }
    exit;
}

// Read all employees for the dropdown
$employees = read('employees');
?>

<main id="main" class="main">
    <section class="section">
        <div class="container mt-4"></div>
        <!-- <div class="pagetitle text-center mt-5 p-2">
            <h1>Employee Attendance</h1>
        </div> -->

        <div class="row">
            <div class="col-lg-12 p-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Attendance List</h3>
                    </div>
                    <div class="card-body p-4">
                        <!-- Add attendance form -->
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="employee" class="form-label">Employee</label>
                                <select class="form-select" id="employee" name="employee_id" required>
                                    <option value="">Select Employee</option>
                                    <?php foreach ($employees as $employee) { ?>
                                        <option value="<?= $employee['id']; ?>"><?= $employee['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="status" name="status">
                                <label class="form-check-label" for="status">Present</label>
                            </div>
                            <button type="submit" name="saveAttendance" class="btn btn-success">Save Attendance</button>
                        </form>

                        <!-- Attendance table -->
                        <table class="table table-hover table-striped table-bordered text-center mt-4">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Attendance ID</th>
                                    <th>Employee Name</th>
                                    <th>Attendance Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach (read('employeeattendance') as $attendance) {
                                    ?>
                                    <tr>
                                        <td><?= $attendance['id']; ?></td>
                                        <td><?= read_column('employees', 'name', $attendance['employee_id']); ?></td>
                                        <td><?= $attendance['attendance_date']; ?></td>
                                        <td>
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input status-checkbox" type="checkbox"
                                                       data-id="<?= $attendance['id']; ?>"
                                                       <?= $attendance['status'] === 'Present' ? 'checked' : ''; ?>>
                                            </div>
                                            <span id="status-label-<?= $attendance['id']; ?>">
                                                <?= $attendance['status'] === 'Present' ? '<i class="bi bi-check-circle" style="color: green;"></i>' : '<i class="bi bi-x-circle" style="color: red;"></i>'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS for toggle switch -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    $('.status-checkbox').change(function () {
        let attendance_id = $(this).data('id');
        let status = $(this).is(':checked');

        $.ajax({
            url: '', // Current PHP page
            type: 'POST',
            data: {
                action: 'updateStatus',
                attendance_id: attendance_id,
                status: status
            },
            success: function (response) {
                let res = JSON.parse(response);
                if (res.success) {
                    let statusLabel = $('#status-label-' + attendance_id);
                    if (status) {
                        statusLabel.html(
                            '<i class="bi bi-check-circle" style="color: green;"></i>'); // Present icon
                    } else {
                        statusLabel.html(
                            '<i class="bi bi-x-circle" style="color: red;"></i>'); // Absent icon
                    }
                } else {
                    alert(res.message);
                }
            },
            error: function () {
                alert('Failed to update status.');
            }
        });
    });
});
</script>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Table Styling */
    .table {
        background-color: #f9f9f9;
        border-radius: 8px;
        overflow: hidden;
    }

    .table thead {
        background-color: #343a40;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Card shadow */
    .card {
        border-radius: 10px;
    }

    .card-header {
        font-weight: bold;
    }

    /* Custom status styling */
    .form-switch .form-check-input {
        width: 2.5em;
        height: 1.5em;
    }

    .form-switch .form-check-input:checked {
        background-color: #28a745;
    }
</style>
