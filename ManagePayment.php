<?php
require_once 'check_session.php';
require_role('hr');

// Database connection
$conn = mysqli_connect("localhost", "root", "", "hrm");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle payment status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $payroll_id = (int)$_POST['payroll_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_query = "UPDATE payroll SET status = '$status' WHERE id = $payroll_id";
    if (!mysqli_query($conn, $update_query)) {
        die("Error updating payment status: " . mysqli_error($conn));
    }
    
    // Redirect to prevent form resubmission
    header("Location: ManagePayment.php");
    exit();
}

// Get all processed payroll records
$payroll_query = "SELECT p.*, e.username, e.email 
                  FROM payroll p 
                  JOIN employees e ON p.employee_id = e.id 
                  WHERE p.status IN ('processed', 'paid')
                  ORDER BY p.year DESC, p.month DESC, e.username";
$payroll_result = mysqli_query($conn, $payroll_query);
if (!$payroll_result) {
    die("Error fetching payroll records: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management - MOM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="HumanResourceDashboard.php">
                <img src="logo.jpg" alt="Logo" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <span class="font-semibold text-xl">Payment Management</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="HumanResourceDashboard.php">
                            <i class="bi bi-house"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="CalculateSalary.php">
                            <i class="bi bi-calculator"></i> Calculate Salary
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
    <div class="container-fluid px-4 py-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="text-2xl font-bold text-gray-800">Payment Management</h1>
                <p class="text-gray-600">Manage employee salary payments</p>
            </div>
        </div>

        <!-- Employee Selection -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Calculate New Salary</h5>
                        <form action="CalculateSalary.php" method="GET" class="row g-3">
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label">Select Employee</label>
                                <select name="id" id="employee_id" class="form-select" required>
                                    <option value="">Choose an employee...</option>
                                    <?php
                                    // Get all employees with detailed error checking
                                    $employees_query = "SELECT id, username, email, status FROM employees ORDER BY username";
                                    $employees_result = mysqli_query($conn, $employees_query);
                                    
                                    if (!$employees_result) {
                                        echo "<option value=''>Error loading employees: " . mysqli_error($conn) . "</option>";
                                    } else {
                                        $num_employees = mysqli_num_rows($employees_result);
                                        echo "<!-- Debug: Found " . $num_employees . " employees -->";
                                        
                                        if ($num_employees == 0) {
                                            echo "<option value=''>No employees found in the system</option>";
                                        } else {
                                            while ($employee = mysqli_fetch_assoc($employees_result)) {
                                                echo "<!-- Debug: Employee ID: " . $employee['id'] . ", Username: " . $employee['username'] . ", Status: " . $employee['status'] . " -->";
                                                echo "<option value='" . $employee['id'] . "'>" . 
                                                     htmlspecialchars($employee['username']) . " (" . 
                                                     htmlspecialchars($employee['email']) . ") - " . 
                                                     htmlspecialchars($employee['status']) . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-calculator"></i> Calculate Salary
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payroll Records -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Month</th>
                                <th>Basic Salary</th>
                                <th>Deductions</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (mysqli_num_rows($payroll_result) > 0) {
                                while ($payroll = mysqli_fetch_assoc($payroll_result)) {
                                    $month_name = date('F', mktime(0, 0, 0, $payroll['month'], 1));
                            ?>
                                <tr>
                                    <td>
                                        <div><?php echo htmlspecialchars($payroll['username']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($payroll['email']); ?></small>
                                    </td>
                                    <td><?php echo $month_name . ' ' . $payroll['year']; ?></td>
                                    <td><?php echo number_format($payroll['basic_salary'], 0); ?> FCFA</td>
                                    <td><?php echo number_format($payroll['deductions'], 0); ?> FCFA</td>
                                    <td><?php echo number_format($payroll['net_salary'], 0); ?> FCFA</td>
                                    <td>
                                        <?php
                                        $status_class = array(
                                            'processed' => 'bg-warning',
                                            'paid' => 'bg-success'
                                        );
                                        $status_text = ucfirst($payroll['status']);
                                        ?>
                                        <span class="badge <?php echo $status_class[$payroll['status']]; ?>">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($payroll['status'] === 'processed') { ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="payroll_id" value="<?php echo $payroll['id']; ?>">
                                                <input type="hidden" name="status" value="paid">
                                                <button type="submit" name="update_status" class="btn btn-success btn-sm">
                                                    <i class="bi bi-check-circle"></i> Mark as Paid
                                                </button>
                                            </form>
                                        <?php } ?>
                                        <a href="CalculateSalary.php?id=<?php echo $payroll['employee_id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                            <?php 
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="7" class="text-center">No payroll records found.</td>
                                </tr>
                            <?php 
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
