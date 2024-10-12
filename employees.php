<?php include 'includes/init.php';

// include 'includes/ajax.php';

$message = [];
if (isset($_POST['btnSave'])) {
    // Check if 'action' is set in the POST request
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'insert') {
            $data = [
                "name" => trim(escape($_POST['name'])),
                "role" => trim(escape($_POST['role'])),
                "email" => trim(escape($_POST['email'])),
                "phone" => trim(escape($_POST['phone']))
            ];

            if (insert('employees', $data)) {
                $message = ["Successfully inserted!", "success"];
            } else {
                $message = ["Sorry! Something went wrong", "danger"];
            }
        } else if ($_POST['action'] == 'update') {
            $data = [
                "id" => trim(escape($_POST['employeeid'])),
                "name" => trim(escape($_POST['name'])),
                "role" => trim(escape($_POST['role'])),
                "phone" => trim(escape($_POST['phone'])),
                "email" => trim(escape($_POST['email']))
            ];

            if (update('employees', $data)) {
                $message = ["Successfully Updated!", "success"];
            } else {
                $message = ["Sorry! Something went wrong", "danger"];
            }
        }
    } else {
        $message = ["Action not specified", "danger"];
    }
}

if(isset($_POST['btnDelete'])){
    if(delete('employees' ,$_POST['employee_id'])){
        $message = ["Successfully Deleted!", "success"];
    } else {
        $message = ["Sorry! Something went wrong", "danger"];
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Tables</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Bundle (includes Popper.js) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>

    <style>
    table {
        width: 100%;
        border-collapse: collapse;

    }

    th,
    td {
        /* border: 2px solid #603F26; */
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #603F26;
    }

    .action-icons {
        display: flex;
        gap: 10px;
    }

    .action-icons i {
        cursor: pointer;
        color: #007bff;
        transition: color 0.3s;
    }

    .action-icons i:hover {
        color: #0056b3;
    }
    </style>
</head>

<body>



    <main id="main" class="main">

        <!-- End Page Title -->

        <section class="section">


            <div class="container mt-4">
                <div class="text-center">
                    <h2>Hi, Welcome Back!</h2>
                    <!-- <p class="lead">Your restaurant admin template</p> -->
                </div>
            </div>

            <!-- Employee Cards Grid -->
            <div class="container p-4" style="background-color: #603F26; margin: botom 20px;">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <!-- Employee Card 1 -->
                    <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                            <img src="https://imgs.search.brave.com/QirPMZ0zGEfEu7SAGrl8efhcCub0CC9jTV7xdydFQdI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/aXN0b2NrcGhvdG8u/Y29tL3Jlc291cmNl/cy9pbWFnZXMvUGhv/dG9GVExQL1NjaWVu/Y2VUZWNobm9sb2d5/LTgyNTE4MDg0Ni5q/cGc"
                                class="card-img-top rounded-circle mx-auto mt-3" alt="Airi Satou" style="width: 100px;">
                            <div class="card-body">
                                <h5 class="card-title">Airi Satou</h5>
                                <p class="card-text">Manager</p>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Card 2 -->
                    <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                            <img src="https://imgs.search.brave.com/QirPMZ0zGEfEu7SAGrl8efhcCub0CC9jTV7xdydFQdI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/aXN0b2NrcGhvdG8u/Y29tL3Jlc291cmNl/cy9pbWFnZXMvUGhv/dG9GVExQL1NjaWVu/Y2VUZWNobm9sb2d5/LTgyNTE4MDg0Ni5q/cGc"
                                class="card-img-top rounded-circle mx-auto mt-3" alt="Angelica Ramos"
                                style="width: 100px;">
                            <div class="card-body">
                                <h5 class="card-title">Angelica Ramos</h5>
                                <p class="card-text">Waiter</p>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Card 3 -->
                    <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                            <img src="https://imgs.search.brave.com/QirPMZ0zGEfEu7SAGrl8efhcCub0CC9jTV7xdydFQdI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/aXN0b2NrcGhvdG8u/Y29tL3Jlc291cmNl/cy9pbWFnZXMvUGhv/dG9GVExQL1NjaWVu/Y2VUZWNobm9sb2d5/LTgyNTE4MDg0Ni5q/cGc"
                                class="card-img-top rounded-circle mx-auto mt-3" alt="Bradley Greer"
                                style="width: 100px;">
                            <div class="card-body">
                                <h5 class="card-title">Bradley Greer</h5>
                                <p class="card-text">Waiter</p>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Card 4 -->
                    <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                            <img src="https://imgs.search.brave.com/QirPMZ0zGEfEu7SAGrl8efhcCub0CC9jTV7xdydFQdI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/aXN0b2NrcGhvdG8u/Y29tL3Jlc291cmNl/cy9pbWFnZXMvUGhv/dG9GVExQL1NjaWVu/Y2VUZWNobm9sb2d5/LTgyNTE4MDg0Ni5q/cGc"
                                class="card-img-top rounded-circle mx-auto mt-3" alt="Brenden Wagner"
                                style="width: 100px;">
                            <div class="card-body">
                                <h5 class="card-title">Brenden Wagner</h5>
                                <p class="card-text">Waitress</p>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Card 5 -->
                    <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                            <img src="https://imgs.search.brave.com/QirPMZ0zGEfEu7SAGrl8efhcCub0CC9jTV7xdydFQdI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/aXN0b2NrcGhvdG8u/Y29tL3Jlc291cmNl/cy9pbWFnZXMvUGhv/dG9GVExQL1NjaWVu/Y2VUZWNobm9sb2d5/LTgyNTE4MDg0Ni5q/cGc"
                                class="card-img-top rounded-circle mx-auto mt-3" alt="Bob Springer"
                                style="width: 100px;">
                            <div class="card-body">
                                <h5 class="card-title">Bob Springer</h5>
                                <p class="card-text">Delivery person</p>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Card 6 -->
                    <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                            <img src="https://imgs.search.brave.com/QirPMZ0zGEfEu7SAGrl8efhcCub0CC9jTV7xdydFQdI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/aXN0b2NrcGhvdG8u/Y29tL3Jlc291cmNl/cy9pbWFnZXMvUGhv/dG9GVExQL1NjaWVu/Y2VUZWNobm9sb2d5/LTgyNTE4MDg0Ni5q/cGc"
                                class="card-img-top rounded-circle mx-auto mt-3" alt="Jonas Alexander"
                                style="width: 100px;">
                            <div class="card-body">
                                <h5 class="card-title">Jonas Alexander</h5>
                                <p class="card-text">Waitress</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pagetitle text-center mt-5 p-2">
                <h1>Employees list</h1>

            </div>
            <div class="alerts">
                <?php $message ? showMessage($message) : ""; ?>

            </div>

            <div class="row">
                <div class="card-header py-3 ">
                    <button type="button" class="btn " style="background-color: #603F26; color:white"
                        data-bs-toggle="modal" data-bs-target="#employeeModal" onclick="reset()">
                        add new employer
                    </button>

                </div>
                <div class="col-lg-12 p-4">

                    <div class="card">

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>
                                        <b>id</b>
                                    </th>

                                    <th>name</th>
                                    <th>role</th>
                                    <th>phone</th>
                                    <th>email</th>
                                    <th>action</th>

                                </tr>
                            </thead>
                            <tbody>


                                <?php
                                      foreach (read('employees') as $employer) {
                                      ?>
                                <tr>
                                    <td><?= $employer['id']; ?></td>
                                    <td><?= $employer['name']; ?></td>
                                    <td><?= $employer['role']; ?></td>
                                    <td><?= $employer['phone']; ?></td>
                                    <td><?= $employer['email']; ?></td>

                                    <td>

                                        <a href="javascript:void(0);" class="btn btn-primary"
                                            onclick="fillForm(<?= $employer['id']; ?>, '<?= $employer['name']; ?>', '<?= $employer['role']; ?>', '<?= $employer['phone']; ?>', '<?= $employer['email']; ?>')">
                                            <i class="bi bi-pencil"></i> Update
                                        </a>

                                        <a href="#deleteModal" data-bs-toggle="modal" class="btn btn-danger"
                                            onclick="setId(<?= $employer['id']; ?>)">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>

                                    </td>
                                </tr>
                                <?php } ?>


                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
            </div>
        </section>

    </main><!-- End #main -->


    <!-- Employee Modal -->
    <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employee_modal_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" id="employeeForm">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="employee_modal_label">Add New Employee</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Input Fields -->
                        <input type="hidden" name="action" id="action" value="insert">
                        <input type="hidden" name="employeeid" id="employeeid" value="0">


                        <div class="form-group mb-3">
                            <input type="text" class="form-control" placeholder="Employee Name" name="name" id="name">
                            <span class="text-danger" id="nameError"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" placeholder="Employee Role" name="role" id="role">
                            <span class="text-danger" id="roleError"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input type="email" class="form-control" placeholder="Employee Email" name="email"
                                id="email">
                            <span class="text-danger" id="emailError"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" placeholder="Employee Phone" name="phone"
                                id="phone">
                            <span class="text-danger" id="phoneError"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Buttons -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="btnSave">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- delete modal -->
    <div class="modal fade" tabindex="-1" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header  text-light" style="background-color: #603F26; ">
                    <h5 class="modal-title" style="background-color: #603F26; color:white;">Delete Employee</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <!-- Modal body -->
                <form method="post" id="employeeForm">
                    <div class="modal-body">
                        <input type="text" name="employee_id" id="employee_id">
                        <p class="lead">Are you sure you want to delete this employee?</p>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger" name="btnDelete">Yes, Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
    function validateEmployeeForm() {
        const name = document.getElementById('name').value.trim();
        const role = document.getElementById('role').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();

        const nameError = document.getElementById('nameError');
        const roleError = document.getElementById('roleError');
        const phoneError = document.getElementById('phoneError');
        const emailError = document.getElementById('emailError');

        nameError.textContent = "";
        roleError.textContent = "";
        phoneError.textContent = "";
        emailError.textContent = "";

        const phoneRegex = /^\d{10}$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        let isValid = true;

        if (name === "") {
            nameError.textContent = "Please enter the employee's name.";
            isValid = false;
        }

        if (role === "") {
            roleError.textContent = "Please enter the employee's role.";
            isValid = false;
        }

        if (phone === "") {
            phoneError.textContent = "Please enter a valid phone number (10 digits).";
            isValid = false;
        }

        if (!emailRegex.test(email)) {
            emailError.textContent = "Please enter a valid email address.";
            isValid = false;
        }


        return isValid;
    }

    // Attach the validation function to the form submit event
    document.getElementById('employeeForm').addEventListener('submit', function(event) {
        if (!validateEmployeeForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
    </script>


    <script>
    function reset() {
        $('.modal-title').text('Add employee');
        $('#action').val('insert');
        $('#employeeModal').val(0);
        document.getElementById('employeeid').value = '';
        document.getElementById('name').value = '';
        document.getElementById('role').value = '';
        document.getElementById('phone').value = '';
        document.getElementById('email').value = '';
    }

    function setId(id) {
        // document.getElementById('#employeee_id').value = id;
        $('#employee_id').val(id);
    }


    function fillForm(id, name, role, phone, email) {
        console.log(id);

        // Set modal title and form action
        const modalTitle = document.querySelector('#employeeModal .modal-title');
        modalTitle.textContent = 'Update Employee';
        document.getElementById('action').value = 'update';
        document.getElementById('employeeid').value = id;
        document.getElementById('name').value = name;
        document.getElementById('role').value = role;
        document.getElementById('phone').value = phone;
        document.getElementById('email').value = email;

        // Show the modal
        const employeeModal = new bootstrap.Modal(document.getElementById('employeeModal'));
        employeeModal.show();

        function removeAlert() {
            console.log('removeAlert function called');
            setTimeout(() => {
                const alerts = document.querySelector('.alerts');
                if (alerts) {
                    alerts.style.transition = 'opacity 1s ease-out'; // Smooth fade-out
                    alerts.style.opacity = 0;
                    setTimeout(() => alerts.remove(), 1000); // Wait for the transition to finish
                }
            }, 3000);
        }

        // Call removeAlert function after the page loads
        document.addEventListener('DOMContentLoaded', () => {
            removeAlert();
        });
    }
    </script>
    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
            Designed by <a href="#">Eng AShuu</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>