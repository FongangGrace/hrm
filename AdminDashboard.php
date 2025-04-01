<?php
require_once 'check_session.php';
require_role('admin');

// Database connection
$conn = mysqli_connect("localhost", "root", "", "hrm");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get stats
$emp_sql = "SELECT COUNT(*) as count FROM employees";
$emp_result = mysqli_query($conn, $emp_sql);
$total_employees = mysqli_fetch_assoc($emp_result)['count'];

$dept_sql = "SELECT COUNT(DISTINCT department) as count FROM employees WHERE department IS NOT NULL";
$dept_result = mysqli_query($conn, $dept_sql);
$total_departments = mysqli_fetch_assoc($dept_result)['count'];

$job_sql = "SELECT COUNT(*) as count FROM job_vacancies WHERE status = 'open'";
$job_result = mysqli_query($conn, $job_sql);
$active_jobs = mysqli_fetch_assoc($job_result)['count'];

$leave_sql = "SELECT COUNT(*) as count FROM leave_requests WHERE status = 'pending'";
$leave_result = mysqli_query($conn, $leave_sql);
$pending_leaves = mysqli_fetch_assoc($leave_result)['count'];

$sql3 = "SELECT COUNT(*) as count FROM permissions WHERE status = 'pending'";
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_assoc($result3);
$pending_permissions = $row3['count'];

$sql4 = "SELECT COUNT(*) as count FROM payments WHERE status = 'pending'";
$result4 = mysqli_query($conn, $sql4);
$row4 = mysqli_fetch_assoc($result4);
$pending_payments = $row4['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MOM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <!-- Add this at the top of your page, in the navigation area -->
    <nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="logo.jpg" alt="Logo" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <span class="font-semibold text-xl">Admin Dashboard</span>
            </a>
            <div class="ms-auto">
                <button onclick="location.href='logout.php'" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </div>
        </div>
    </nav>

    <!-- Then your existing dashboard content -->
    <div class="container-fluid px-4 py-5">
        <h1 class="text-2xl font-bold text-gray-800">Welcome, Administrator</h1>
        <p class="text-gray-600">Manage your organization efficiently</p>
    </div>

        <!-- Quick Stats -->
        <div class="row g-4 mb-5">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dashboard-card hover-scale">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Total Employees</h3>
                            <div class="dashboard-stat"><?php echo $total_employees; ?></div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-people text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dashboard-card hover-scale">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Departments</h3>
                            <div class="dashboard-stat"><?php echo $total_departments; ?></div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-building text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dashboard-card hover-scale">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Active Jobs</h3>
                            <div class="dashboard-stat"><?php echo $active_jobs; ?></div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-briefcase text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dashboard-card hover-scale">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Leave Requests</h3>
                            <div class="dashboard-stat"><?php echo $pending_leaves; ?></div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-calendar-check text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dashboard-card hover-scale">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Pending Permissions</h3>
                            <div class="dashboard-stat"><?php echo $pending_permissions; ?></div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-key text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dashboard-card hover-scale">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Pending Payments</h3>
                            <div class="dashboard-stat"><?php echo $pending_payments; ?></div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-cash text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="Employee.php" class="card hover-scale text-decoration-none">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="bi bi-person-plus text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="card-title text-gray-800">Manage Employees</h4>
                                <p class="card-text text-gray-600">Add, edit, or remove employee records</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="Annualleave.php" class="card hover-scale text-decoration-none">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="bi bi-calendar2-week text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="card-title text-gray-800">Leave Management</h4>
                                <p class="card-text text-gray-600">Review and manage leave requests</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="ViewApplications.php" class="card hover-scale text-decoration-none">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="bi bi-file-earmark-text text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="card-title text-gray-800">Job Applications</h4>
                                <p class="card-text text-gray-600">Review submitted applications</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="ReportEmployee.php" class="card hover-scale text-decoration-none">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="bi bi-file-earmark-text text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="card-title text-gray-800">Reports</h4>
                                <p class="card-text text-gray-600">Generate HR reports</p>
                            </div>
                        </a>
                    </div>
                    <!-- Add Permission Management Card -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="ManagePermissions.php" class="card hover-scale text-decoration-none">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="bi bi-shield-check text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="card-title text-gray-800">Manage Permissions</h4>
                                <p class="card-text text-gray-600">Review and manage permissions</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Other scripts -->
    <script src="assets/js/theme-switcher.js"></script>
</body>
</html>