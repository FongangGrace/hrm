<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: Login Form.php");
    exit();
}

$conn = mysqli_connect("localhost", 'root', '', 'hrm');
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}


$email = $_SESSION['email'];
$sql = "SELECT * FROM employees WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);


$jobs_sql = "SELECT * FROM job_vacancies WHERE status = 'open' ORDER BY created_at DESC";
$jobs_result = mysqli_query($conn, $jobs_sql);


if (isset($_POST['apply'])) {
    $job_id = $_POST['job_id'];
    $applicant_id = $user['id'];
    $status = 'pending';
    
    // Check if already applied
    $check_sql = "SELECT * FROM job_applications WHERE job_id = ? AND applicant_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ii", $job_id, $applicant_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error = "You have already applied for this position.";
    } else {
        // Insert new application
        $apply_sql = "INSERT INTO job_applications (job_id, applicant_id, status) VALUES (?, ?, ?)";
        $apply_stmt = mysqli_prepare($conn, $apply_sql);
        mysqli_stmt_bind_param($apply_stmt, "iis", $job_id, $applicant_id, $status);
        
        if (mysqli_stmt_execute($apply_stmt)) {
            $success = "Your application has been submitted successfully!";
        } else {
            $error = "Error submitting application. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job - MOM System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="EmployeeDashboard.php">Apply for Job</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="EmployeeDashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Available Job Positions</h5>
                    </div>
                    <div class="card-body">
                        <?php if (mysqli_num_rows($jobs_result) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Department</th>
                                            <th>Description</th>
                                            <th>Requirements</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($job = mysqli_fetch_assoc($jobs_result)): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($job['title']); ?></td>
                                                <td><?php echo htmlspecialchars($job['department']); ?></td>
                                                <td><?php echo htmlspecialchars(substr($job['description'], 0, 100)) . '...'; ?></td>
                                                <td><?php echo htmlspecialchars(substr($job['requirements'], 0, 100)) . '...'; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#jobModal<?php echo $job['id']; ?>">
                                                        View Details
                                                    </button>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                                        <button type="submit" name="apply" class="btn btn-success btn-sm">Apply</button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Job Details Modal -->
                                            <div class="modal fade" id="jobModal<?php echo $job['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"><?php echo htmlspecialchars($job['title']); ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h6>Department</h6>
                                                            <p><?php echo htmlspecialchars($job['department']); ?></p>
                                                            
                                                            <h6>Description</h6>
                                                            <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                                                            
                                                            <h6>Requirements</h6>
                                                            <p><?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                                                <button type="submit" name="apply" class="btn btn-success">Apply Now</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">No job vacancies are currently available.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 