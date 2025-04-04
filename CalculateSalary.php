<?php
require_once 'config.php';
require_once 'includes/auth_check.php';


$employee_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


$employee_query = "SELECT e.*, p.basic_salary, p.allowances, p.deductions 
                  FROM employees e 
                  LEFT JOIN payroll p ON e.id = p.employee_id 
                  WHERE e.id = ?";
$stmt = mysqli_prepare($conn, $employee_query);
mysqli_stmt_bind_param($stmt, "i", $employee_id);
mysqli_stmt_execute($stmt);
$employee_result = mysqli_stmt_get_result($stmt);
$employee = mysqli_fetch_assoc($employee_result);

if (!$employee) {
    die("Employee not found");
}

// Calculate salary
$basic_salary = $employee['basic_salary'] ?? 0;
$allowances = $employee['allowances'] ?? 0;
$deductions = $employee['deductions'] ?? 0;
$net_salary = $basic_salary + $allowances - $deductions;

// Get current month and year
$current_month = date('n');
$current_year = date('Y');

// Check if payroll already exists for this month
$check_query = "SELECT id FROM payroll WHERE employee_id = ? AND month = ? AND year = ?";
$stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($stmt, "iii", $employee_id, $current_month, $current_year);
mysqli_stmt_execute($stmt);
$existing_payroll = mysqli_stmt_get_result($stmt)->num_rows > 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$existing_payroll) {
    $month = $current_month;
    $year = $current_year;
    $status = 'pending';
    $processed_by = $_SESSION['user_id'] ?? null; // Get the current user's ID
    
    // Insert new payroll record
    $insert_query = "INSERT INTO payroll (employee_id, month, year, basic_salary, allowances, deductions, net_salary, status, processed_by) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "iiiddddsi", $employee_id, $month, $year, $basic_salary, $allowances, $deductions, $net_salary, $status, $processed_by);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ManagePayment.php?success=1");
        exit;
    } else {
        $error = "Error saving payroll record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculate Salary - HR Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }
        .nav-link {
            color: rgba(255,255,255,.8);
        }
        .nav-link:hover {
            color: white;
        }
        .nav-link.active {
            background: rgba(255,255,255,.1);
        }
        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,.1);
        }
        .salary-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .salary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .salary-item:last-child {
            border-bottom: none;
        }
        .salary-item.total {
            font-weight: bold;
            font-size: 1.2em;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 position-fixed sidebar">
                <div class="p-3">
                    <h4 class="text-center mb-4">HR System</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ManageEmployee.php">
                                <i class="fas fa-users me-2"></i> Manage Employees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ManagePayment.php">
                                <i class="fas fa-money-bill me-2"></i> Manage Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ManageLeave.php">
                                <i class="fas fa-calendar-alt me-2"></i> Manage Leave
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ManageTraining.php">
                                <i class="fas fa-graduation-cap me-2"></i> Manage Training
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ManagePerformance.php">
                                <i class="fas fa-chart-line me-2"></i> Manage Performance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ManageReports.php">
                                <i class="fas fa-file-alt me-2"></i> Manage Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ManageSettings.php">
                                <i class="fas fa-cog me-2"></i> Manage Settings
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-auto px-4 py-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Calculate Salary</h2>
                    <a href="ManagePayment.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Payments
                    </a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Employee Information</h5>
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($employee['username']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($employee['email']); ?></p>
                                <p><strong>Department:</strong> <?php echo htmlspecialchars($employee['department']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5>Calculation Details</h5>
                                <p><strong>Month:</strong> <?php echo date('F Y'); ?></p>
                                <p><strong>Status:</strong> <?php echo $existing_payroll ? 'Already Processed' : 'Pending'; ?></p>
                            </div>
                        </div>

                        <div class="salary-details">
                            <h5 class="mb-3">Salary Breakdown</h5>
                            <div class="salary-item">
                                <span>Basic Salary:</span>
                                <span>FCFA <?php echo number_format($basic_salary, 2); ?></span>
                            </div>
                            <div class="salary-item">
                                <span>Allowances:</span>
                                <span>FCFA <?php echo number_format($allowances, 2); ?></span>
                            </div>
                            <div class="salary-item">
                                <span>Deductions:</span>
                                <span>FCFA <?php echo number_format($deductions, 2); ?></span>
                            </div>
                            <div class="salary-item total">
                                <span>Net Salary:</span>
                                <span>FCFA <?php echo number_format($net_salary, 2); ?></span>
                            </div>
                        </div>

                        <?php if (!$existing_payroll): ?>
                            <form method="POST" class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Payroll Record
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>Payroll has already been processed for this month.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
