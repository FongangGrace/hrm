<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: LoginAdmin.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "hrm");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        $_SESSION['error'] = "Invalid payment ID";
        header("Location: ManagePayment.php");
        exit();
    }

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $payment_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $payment_type = mysqli_real_escape_string($conn, $_POST['payment_type']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "UPDATE payments SET 
            amount = '$amount',
            payment_date = '$payment_date',
            payment_type = '$payment_type',
            status = '$status'
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Payment updated successfully";
        header("Location: ManagePayment.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating payment: " . mysqli_error($conn);
    }
}

// Get payment details
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid payment ID";
    header("Location: ManagePayment.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "SELECT p.*, e.employee_name, e.department 
        FROM payments p 
        JOIN employees e ON p.employee_id = e.id 
        WHERE p.id = '$id'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Payment not found";
    header("Location: ManagePayment.php");
    exit();
}

$payment = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment - HRM System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
        }
        .sidebar .nav-link:hover {
            color: rgba(255,255,255,1);
        }
        .sidebar .nav-link.active {
            color: white;
        }
        .main-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 position-fixed sidebar">
                <div class="p-3">
                    <h4>HRM System</h4>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="AdminDashboard.php">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Employee.php">
                                <i class="bi bi-people"></i> Employees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Leave.php">
                                <i class="bi bi-calendar-check"></i> Leave Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Permission.php">
                                <i class="bi bi-key"></i> Permissions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="ManagePayment.php">
                                <i class="bi bi-cash"></i> Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ReportEmployee.php">
                                <i class="bi bi-file-earmark-text"></i> Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-auto main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Edit Payment</h2>
                    <a href="ManagePayment.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Payments
                    </a>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form action="EditPayment.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $payment['id']; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Employee</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($payment['employee_name']); ?>" readonly>
                                <small class="text-muted">Department: <?php echo htmlspecialchars($payment['department']); ?></small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input type="number" name="amount" class="form-control" step="0.01" value="<?php echo $payment['amount']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Date</label>
                                <input type="date" name="payment_date" class="form-control" value="<?php echo $payment['payment_date']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Type</label>
                                <select name="payment_type" class="form-select" required>
                                    <option value="salary" <?php echo $payment['payment_type'] == 'salary' ? 'selected' : ''; ?>>Salary</option>
                                    <option value="bonus" <?php echo $payment['payment_type'] == 'bonus' ? 'selected' : ''; ?>>Bonus</option>
                                    <option value="allowance" <?php echo $payment['payment_type'] == 'allowance' ? 'selected' : ''; ?>>Allowance</option>
                                    <option value="other" <?php echo $payment['payment_type'] == 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="pending" <?php echo $payment['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="completed" <?php echo $payment['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $payment['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 