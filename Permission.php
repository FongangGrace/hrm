<?php
session_start();
require_once 'includes/auth_check.php';
require_role('hr');

$conn = mysqli_connect("localhost", 'root', '', 'hrm');
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}

// Get all permissions with employee details
$sql = "SELECT p.*, e.username as employee_name, e.department 
        FROM permissions p 
        JOIN employees e ON p.employee_id = e.id 
        ORDER BY p.request_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permissions Management - MOM System</title>
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
                <h1 style="color: black;">PERMISSIONS</h1>
                
                <!-- Action Buttons -->
                <div class="mb-4">
                    <button onclick="location.href='AddPermission.php'" class="btn btn-primary m-2">
                        <i class="bi bi-plus-circle"></i> Add New Permission
                    </button>
                    <button onclick="location.href='ReportPermission.php'" class="btn btn-info m-2">
                        <i class="bi bi-file-earmark-text"></i> Generate Report
                    </button>
                </div>

                <!-- Permissions Table -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Department</th>
                                        <th>Request Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['employee_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['request_date'])); ?></td>
                                        <td><?php echo date('h:i A', strtotime($row['start_time'])); ?></td>
                                        <td><?php echo date('h:i A', strtotime($row['end_time'])); ?></td>
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
                                        <td>
                                            <a href="EditPermission.php?ID=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="DeletePermission.php?ID=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this permission request?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
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
