<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: LoginAdmin.php");
    exit();
}

$conn = mysqli_connect("localhost", 'root', '', 'hrm');
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}

// Get filter parameters
$department = isset($_GET['department']) ? $_GET['department'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Build the query
$sql = "SELECT l.*, e.username as employee_name, e.department 
        FROM leave_requests l 
        JOIN employees e ON l.employee_id = e.id 
        WHERE l.type = 'annual'";

if ($department) {
    $sql .= " AND e.department = '" . mysqli_real_escape_string($conn, $department) . "'";
}
if ($status) {
    $sql .= " AND l.status = '" . mysqli_real_escape_string($conn, $status) . "'";
}
if ($start_date) {
    $sql .= " AND l.start_date >= '" . mysqli_real_escape_string($conn, $start_date) . "'";
}
if ($end_date) {
    $sql .= " AND l.end_date <= '" . mysqli_real_escape_string($conn, $end_date) . "'";
}

$sql .= " ORDER BY l.created_at DESC";
$result = mysqli_query($conn, $sql);

// Get unique departments for filter
$dept_sql = "SELECT DISTINCT department FROM employees ORDER BY department";
$dept_result = mysqli_query($conn, $dept_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Report - MOM System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Employee.css">
</head>
<body>
    <div class="container-fluid">
        <div class="container text-center">
            <!-- Sidebar -->
            <div class="left">
                <h1 style="color: goldenrod; font-size: 50px; font-weight: bold; text-align: center;">MGT</h1>
                <ul>
                    <h5 style='font-weight:bold; cursor:pointer;' onclick="location.href='Employee.php'">Employees</h5>
                    <h5 style='font-weight:bold; cursor:pointer;' onclick="location.href='Permission.php'">Permission</h5>
                    <h5 style='font-weight:bold; cursor:pointer;' onclick="location.href='apply.php'">Job Application</h5>
                </ul>

                <button onclick="location.href='logout.php'" style="width: 120px; height: 45px; border-radius: 20px; margin: 20px; border: none; margin-left:900px; background-color:darkblue; color: white;">Logout</button>
            </div>

            <!-- Main Content -->
            <div class="right">
                <br>
                <h1 style="color: black;">Leave Report</h1>
                
                <!-- Filter Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Department</label>
                                <select name="department" class="form-select">
                                    <option value="">All Departments</option>
                                    <?php while ($dept = mysqli_fetch_assoc($dept_result)): ?>
                                        <option value="<?php echo htmlspecialchars($dept['department']); ?>" 
                                                <?php echo $department === $dept['department'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($dept['department']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="rejected" <?php echo $status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                                <a href="ReportLeave.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Clear Filters
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Leave Requests Report</h5>
                            <button onclick="window.print()" class="btn btn-success">
                                <i class="bi bi-printer"></i> Print Report
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Department</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                        <th>Applied Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['employee_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($row['start_date'])); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($row['end_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                switch($row['status']) {
                                                    case 'approved':
                                                        echo 'success';
                                                        break;
                                                    case 'rejected':
                                                        echo 'danger';
                                                        break;
                                                    case 'pending':
                                                        echo 'warning';
                                                        break;
                                                    default:
                                                        echo 'secondary';
                                                }
                                            ?>">
                                                <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 