<?php
session_start();
require_once 'includes/auth_check.php';
// Allow both HR and admin roles to view applications
if (!in_array($_SESSION['role'], ['hr', 'admin'])) {
    header("Location: Login Form.php?error=unauthorized");
    exit();
}

$conn = mysqli_connect("localhost", 'root', '', 'hrm');
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['application_id']) && isset($_POST['status'])) {
    $application_id = (int)$_POST['application_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_sql = "UPDATE job_applications SET status = ?, updated_at = NOW() WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "si", $status, $application_id);
    
    if (mysqli_stmt_execute($update_stmt)) {
        echo "<div class='alert alert-success'>Application status updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating application status: " . mysqli_error($conn) . "</div>";
    }
}

// Get all applications with job details
$sql = "SELECT ja.*, jv.title as job_title, jv.department 
        FROM job_applications ja 
        LEFT JOIN job_vacancies jv ON ja.job_id = jv.id 
        ORDER BY ja.application_date DESC";
$result = mysqli_query($conn, $sql);
$applications = array();
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $applications[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applications - MOM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="AdminDashboard.php">
                <img src="logo.jpg" alt="Logo" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <span class="font-semibold text-xl">Job Applications</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="window.location.reload();">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="AdminDashboard.php">
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
    <div class="container-fluid px-4 py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="text-2xl font-bold text-gray-800">Job Applications</h1>
                <p class="text-gray-600">Review and manage job applications</p>
            </div>
        </div>

        <?php if (empty($applications)): ?>
        <div class="alert alert-info">
            No job applications have been submitted yet.
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Applicant Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Application Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($app['id']); ?></td>
                                <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                                <td><?php echo htmlspecialchars($app['department']); ?></td>
                                <td><?php echo htmlspecialchars($app['applicant_name']); ?></td>
                                <td><?php echo htmlspecialchars($app['email']); ?></td>
                                <td><?php echo htmlspecialchars($app['phone']); ?></td>
                                <td><?php echo htmlspecialchars($app['application_date']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        switch($app['status']) {
                                            case 'pending':
                                                echo 'warning';
                                                break;
                                            case 'approved':
                                                echo 'success';
                                                break;
                                            case 'rejected':
                                                echo 'danger';
                                                break;
                                            default:
                                                echo 'secondary';
                                        }
                                    ?>">
                                        <?php echo ucfirst(htmlspecialchars($app['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                            Update Status
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="bi bi-check-circle"></i> Approve
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-x-circle"></i> Reject
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $app['id']; ?>">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            <div class="modal fade" id="viewModal<?php echo $app['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Application Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-4">
                                                <h6 class="fw-bold">Resume/CV</h6>
                                                <pre class="bg-light p-3 rounded"><?php echo htmlspecialchars($app['resume']); ?></pre>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold">Cover Letter</h6>
                                                <pre class="bg-light p-3 rounded"><?php echo htmlspecialchars($app['cover_letter']); ?></pre>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
