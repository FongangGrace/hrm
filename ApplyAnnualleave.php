<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: Login Form.php");
    exit();
}

$conn = mysqli_connect("localhost", 'root', '', 'hrm');
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}

// Get employee details from session
$email = $_SESSION['email'];
$emp_sql = "SELECT * FROM employees WHERE email = ?";
$emp_stmt = mysqli_prepare($conn, $emp_sql);
if (!$emp_stmt) {
    die("Error preparing statement: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($emp_stmt, "s", $email);
mysqli_stmt_execute($emp_stmt);
$emp_result = mysqli_stmt_get_result($emp_stmt);
$employee = mysqli_fetch_assoc($emp_result);

if (isset($_POST['apply'])) {
    $employee_id = $employee['id'];
    $start_date = mysqli_real_escape_string($conn, $_POST['sdate']);
    $end_date = mysqli_real_escape_string($conn, $_POST['edate']);
    $reason = mysqli_real_escape_string($conn, $_POST['purpose']);
    
    // Insert using prepared statement
    $sql = "INSERT INTO leave_requests (employee_id, start_date, end_date, reason, type, status) 
            VALUES (?, ?, ?, ?, 'annual', 'pending')";
    
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "isss", $employee_id, $start_date, $end_date, $reason);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert alert-success'>Annual leave application submitted successfully!</div>";
        // Redirect after 2 seconds
        header("refresh:2;url=EmployeeDashboard.php");
    } else {
        echo "<div class='alert alert-danger'>Error submitting application: " . mysqli_error($conn) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annual Leave Application - MOM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="EmployeeDashboard.php">
                <img src="logo.jpg" alt="Logo" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <span class="font-semibold text-xl">Annual Leave Application</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="EmployeeDashboard.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Apply for Annual Leave</h2>
                        
                        <form method="post" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="name" class="form-label">Employee Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($employee['username']); ?>" 
                                       required readonly>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="sdate" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="sdate" name="sdate" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="edate" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="edate" name="edate" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="depart" class="form-label">Department</label>
                                <input type="text" class="form-control" id="depart" name="depart" 
                                       value="<?php echo htmlspecialchars($employee['department']); ?>" 
                                       required readonly>
                            </div>

                            <div class="mb-3">
                                <label for="purpose" class="form-label">Purpose of Leave</label>
                                <textarea class="form-control" id="purpose" name="purpose" rows="4" required></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="apply" class="btn btn-primary">
                                    Submit Application
                                </button>
                                <a href="EmployeeDashboard.php" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Date validation
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('sdate');
            const endDate = document.getElementById('edate');
            
            startDate.addEventListener('change', function() {
                endDate.min = this.value;
            });
            
            endDate.addEventListener('change', function() {
                if (startDate.value && this.value < startDate.value) {
                    this.value = startDate.value;
                }
            });
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            startDate.min = today;
            endDate.min = today;
        });
    </script>
</body>
</html>