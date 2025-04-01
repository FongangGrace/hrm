<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: LoginHuman.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "hrm");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle status updates
if (isset($_POST['update_status'])) {
    $application_id = $_POST['application_id'];
    $new_status = $_POST['new_status'];
    
    $update_query = "UPDATE job_applications SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $application_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Application status updated successfully!";
    } else {
        $error_message = "Error updating status: " . mysqli_error($conn);
    }
}

// Fetch applications with filters
$where_clause = "1=1";
if (isset($_GET['status']) && $_GET['status'] != 'all') {
    $where_clause .= " AND ja.status = '" . mysqli_real_escape_string($conn, $_GET['status']) . "'";
}
if (isset($_GET['position']) && $_GET['position'] != 'all') {
    $where_clause .= " AND jv.title = '" . mysqli_real_escape_string($conn, $_GET['position']) . "'";
}

$query = "SELECT ja.*, jv.title as position, jv.department, e.username as applicant_name 
          FROM job_applications ja 
          JOIN job_vacancies jv ON ja.job_id = jv.id 
          LEFT JOIN employees e ON ja.applicant_id = e.id 
          WHERE $where_clause 
          ORDER BY ja.created_at DESC";

// Execute query with error checking
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error executing query: " . mysqli_error($conn) . "<br>Query: " . $query);
}

// Check if we have any results
if (mysqli_num_rows($result) == 0) {
    $no_results_message = "No job applications found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruitment Management - MOM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="HumanResourceDashboard.php">
                <img src="logo.jpg" alt="Logo" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <span class="font-semibold text-xl">Recruitment Management</span>
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
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Application Status</label>
                                <select name="status" class="form-select">
                                    <option value="all" <?php echo (!isset($_GET['status']) || $_GET['status'] == 'all') ? 'selected' : ''; ?>>All Status</option>
                                    <option value="pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="reviewed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'reviewed') ? 'selected' : ''; ?>>Reviewed</option>
                                    <option value="accepted" <?php echo (isset($_GET['status']) && $_GET['status'] == 'accepted') ? 'selected' : ''; ?>>Accepted</option>
                                    <option value="rejected" <?php echo (isset($_GET['status']) && $_GET['status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Position</label>
                                <select name="position" class="form-select">
                                    <option value="all" <?php echo (!isset($_GET['position']) || $_GET['position'] == 'all') ? 'selected' : ''; ?>>All Positions</option>
                                    <?php
                                    $positions_query = "SELECT DISTINCT position FROM job_vacancies";
                                    $positions_result = mysqli_query($conn, $positions_query);
                                    while ($position = mysqli_fetch_assoc($positions_result)) {
                                        $selected = (isset($_GET['position']) && $_GET['position'] == $position['position']) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($position['position']) . "' $selected>" . htmlspecialchars($position['position']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                                <a href="Recruitment.php" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Job Applications</h5>
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Applicant Name</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Application Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars(isset($row['applicant_name']) ? $row['applicant_name'] : 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($row['position']); ?></td>
                                            <td><?php echo htmlspecialchars($row['department']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                            <td>
                                                <?php
                                                $status_class = [
                                                    'pending' => 'bg-warning',
                                                    'reviewed' => 'bg-info',
                                                    'accepted' => 'bg-success',
                                                    'rejected' => 'bg-danger'
                                                ];
                                                $status = $row['status'];
                                                echo "<span class='badge {$status_class[$status]}'>" . ucfirst($status) . "</span>";
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal<?php echo $row['id']; ?>">
                                                    Update Status
                                                </button>
                                                <a href="ViewApplication.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Update Status Modal -->
                                        <div class="modal fade" id="updateModal<?php echo $row['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Update Application Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                                                            <div class="mb-3">
                                                                <label class="form-label">New Status</label>
                                                                <select name="new_status" class="form-select" required>
                                                                    <option value="pending">Pending</option>
                                                                    <option value="reviewed">Reviewed</option>
                                                                    <option value="accepted">Accepted</option>
                                                                    <option value="rejected">Rejected</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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