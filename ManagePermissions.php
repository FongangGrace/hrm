<?php
require_once 'check_session.php';
require_role('admin');

$conn = mysqli_connect("localhost", "root", "", "hrm");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle permission status updates
if (isset($_POST['permission_id']) && isset($_POST['status'])) {
    $permission_id = $_POST['permission_id'];
    $status = $_POST['status'];
    
    $update_query = "UPDATE permissions SET status = ?, updated_at = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    
    if ($stmt === false) {
        die("Prepare failed: " . mysqli_error($conn));
    }
    
    if (!mysqli_stmt_bind_param($stmt, "si", $status, $permission_id)) {
        die("Binding parameters failed: " . mysqli_stmt_error($stmt));
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        die("Execute failed: " . mysqli_stmt_error($stmt));
    } else {
        mysqli_stmt_close($stmt);
        header("Location: ManagePermissions.php?success=Permission status updated successfully");
        exit();
    }
}

// Fetch all pending permissions with employee details
$permissions_query = "SELECT p.id, p.employee_id, p.reason, p.status, p.created_at, 
                     e.username, e.department 
                     FROM permissions p 
                     JOIN employees e ON p.employee_id = e.id 
                     ORDER BY p.created_at DESC";
$permissions_result = mysqli_query($conn, $permissions_query);

if (!$permissions_result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Permissions - MOM System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="AdminDashboard.php">
                <img src="logo.jpg" alt="Logo" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <span class="font-semibold text-xl">Manage Permissions</span>
            </a>
            <div class="ms-auto">
                <a href="AdminDashboard.php" class="btn btn-outline-primary me-2">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-5">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Permission Type</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Requested Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($permission = mysqli_fetch_assoc($permissions_result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($permission['username']); ?></td>
                                <td><?php echo htmlspecialchars($permission['department']); ?></td>
                                <td><?php echo htmlspecialchars($permission['reason']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $permission['status'] == 'approved' ? 'success' : 
                                            ($permission['status'] == 'denied' ? 'danger' : 'warning'); 
                                    ?>">
                                        <?php echo ucfirst($permission['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('Y-m-d', strtotime($permission['created_at'])); ?></td>
                                <td>
                                    <?php if ($permission['status'] == 'pending'): ?>
                                    <form action="ManagePermissions.php" method="POST" class="d-inline">
                                        <input type="hidden" name="permission_id" value="<?php echo $permission['id']; ?>">
                                        <button type="submit" name="status" value="approved" class="btn btn-success btn-sm">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </button>
                                        <button type="submit" name="status" value="denied" class="btn btn-danger btn-sm">
                                            <i class="bi bi-x-lg"></i> Deny
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>