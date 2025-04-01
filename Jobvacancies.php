<?php
session_start();
require_once 'includes/auth_check.php';
require_role('hr');

$conn = mysqli_connect("localhost", 'root', '', 'hrm');
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}

// Get all job vacancies
$sql = "SELECT * FROM job_vacancies ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$jobs = array();
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Vacancies Management - MOM System</title>
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
                <span class="font-semibold text-xl">Job Vacancies</span>
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
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Job Vacancies Management</h1>
                    <p class="text-gray-600">Manage job postings and requirements</p>
                </div>
                <div>
                    <a href="AddJobVacancy.php" class="btn btn-primary me-2">
                        <i class="bi bi-plus-circle"></i> Add New Job
                    </a>
                    <a href="JobVacancyReport.php" class="btn btn-secondary">
                        <i class="bi bi-file-text"></i> Generate Report
                    </a>
                </div>
            </div>
        </div>

        <?php if (empty($jobs)): ?>
        <div class="alert alert-info">
            No job vacancies have been posted yet.
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Title</th>
                                <th>Department</th>
                                <th>Description</th>
                                <th>Requirements</th>
                                <th>Status</th>
                                <th>Deadline</th>
                                <th>Applications</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(isset($job['title']) ? $job['title'] : ''); ?></td>
                                <td><?php echo htmlspecialchars(isset($job['department']) ? $job['department'] : ''); ?></td>
                                <td><?php echo htmlspecialchars(substr(isset($job['description']) ? $job['description'] : '', 0, 100)) . '...'; ?></td>
                                <td><?php echo htmlspecialchars(substr(isset($job['requirements']) ? $job['requirements'] : '', 0, 100)) . '...'; ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        $status = isset($job['status']) ? $job['status'] : '';
                                        switch($status) {
                                            case 'open':
                                                echo 'success';
                                                break;
                                            case 'closed':
                                                echo 'danger';
                                                break;
                                            case 'draft':
                                                echo 'warning';
                                                break;
                                            default:
                                                echo 'secondary';
                                        }
                                    ?>">
                                        <?php echo ucfirst(htmlspecialchars(isset($job['status']) ? $job['status'] : 'unknown')); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars(isset($job['deadline']) ? $job['deadline'] : ''); ?></td>
                                <td>
                                    <?php
                                    // Get application count
                                    $app_sql = "SELECT COUNT(*) as count FROM job_applications WHERE job_id = ?";
                                    $app_stmt = mysqli_prepare($conn, $app_sql);
                                    mysqli_stmt_bind_param($app_stmt, "i", $job['id']);
                                    mysqli_stmt_execute($app_stmt);
                                    $app_result = mysqli_stmt_get_result($app_stmt);
                                    $app_count = mysqli_fetch_assoc($app_result)['count'];
                                    ?>
                                    <span class="badge bg-info"><?php echo $app_count; ?></span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="EditJobVacancy.php?id=<?php echo isset($job['id']) ? $job['id'] : ''; ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="DeleteJobVacancy.php?id=<?php echo isset($job['id']) ? $job['id'] : ''; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this job posting?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $job['id']; ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            <div class="modal fade" id="viewModal<?php echo $job['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Job Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h4><?php echo htmlspecialchars(isset($job['title']) ? $job['title'] : ''); ?></h4>
                                            <p class="text-muted"><?php echo htmlspecialchars(isset($job['department']) ? $job['department'] : ''); ?></p>
                                            
                                            <h6 class="mt-4">Description</h6>
                                            <p><?php echo nl2br(htmlspecialchars(isset($job['description']) ? $job['description'] : '')); ?></p>
                                            
                                            <h6 class="mt-4">Requirements</h6>
                                            <p><?php echo nl2br(htmlspecialchars(isset($job['requirements']) ? $job['requirements'] : '')); ?></p>
                                            
                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <h6>Status</h6>
                                                    <p><?php echo ucfirst(htmlspecialchars(isset($job['status']) ? $job['status'] : '')); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Deadline</h6>
                                                    <p><?php echo htmlspecialchars(isset($job['deadline']) ? $job['deadline'] : ''); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <a href="ViewApplications.php?job_id=<?php echo $job['id']; ?>" class="btn btn-primary">
                                                View Applications (<?php echo $app_count; ?>)
                                            </a>
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