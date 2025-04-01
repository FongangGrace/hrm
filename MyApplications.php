<?php
require_once 'check_session.php';
require_role('employee');

$conn = mysqli_connect("localhost", "root", "", "hrm");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get user's job applications with job details
// Update the query with correct column names
$query = "SELECT ja.id, ja.job_id, ja.applicant_id, ja.status, ja.created_at as applied_date, 
          jv.title, jv.department, ja.cv as resume, ja.cover_letter
          FROM job_applications ja
          JOIN job_vacancies jv ON ja.job_id = jv.id
          WHERE ja.applicant_id = ?
          ORDER BY ja.created_at DESC";

// Add error handling for prepared statement
$stmt = mysqli_prepare($conn, $query);
if ($stmt === false) {
    die("Error preparing statement: " . mysqli_error($conn));
}

// Add error handling for parameter binding
if (!mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id'])) {
    die("Error binding parameters: " . mysqli_stmt_error($stmt));
}

// Add error handling for execution
if (!mysqli_stmt_execute($stmt)) {
    die("Error executing statement: " . mysqli_stmt_error($stmt));
}

// Add error handling for getting results
$result = mysqli_stmt_get_result($stmt);
if ($result === false) {
    die("Error getting results: " . mysqli_stmt_error($stmt));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Job Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2>Track the status of your job applications</h2>
        
        <div class="card mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Department</th>
                                <th>Applied Date</th>
                                <th>Status</th>
                                <th>Documents</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['applied_date'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo ($row['status'] == 'pending') ? 'warning' : 
                                                     (($row['status'] == 'approved') ? 'success' : 'danger'); 
                                            ?>">
                                                <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($row['cv'])): ?>
                                                <a href="<?php echo htmlspecialchars($row['cv']); ?>" 
                                                   class="btn btn-sm btn-primary" target="_blank">
                                                    View Resume
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($row['cover_letter'])): ?>
                                                <button type="button" class="btn btn-sm btn-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#coverLetterModal<?php echo $row['id']; ?>">
                                                    View Cover Letter
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    
                                    <!-- Cover Letter Modal -->
                                    <div class="modal fade" id="coverLetterModal<?php echo $row['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Cover Letter</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php echo nl2br(htmlspecialchars($row['cover_letter'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No job applications found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="EmployeeDashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>