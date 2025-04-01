<?php
require_once 'check_session.php';
require_role('employee');

// Database connection
$conn = mysqli_connect("localhost", "root", "", "hrm");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get employee details
$email = $_SESSION['email'];
$sql = "SELECT * FROM employees WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Get employee's full name
$employee_name = $user['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-bg: #ffffff;
            --primary-text: #000000;
            --nav-bg: #f8f9fa;
        }

        [data-theme="dark"] {
            --primary-bg: #1a1a1a;
            --primary-text: #ffffff;
            --nav-bg: #343a40;
        }

        body {
            background-color: var(--primary-bg);
            color: var(--primary-text);
            transition: all 0.3s ease;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: -10px;
            padding: 3px 7px;
            border-radius: 50%;
            background: #dc3545;
            color: white;
            font-size: 12px;
        }

        .theme-switch {
            position: relative;
            width: 60px;
            height: 30px;
            margin: 0 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="EmployeeDashboard.php">
                <img src="assets/img/logo.jpg" alt="Company Logo" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                <span class="font-semibold text-xl">Employee Dashboard</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <!-- Notifications -->
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="#" id="notificationsDropdown" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <?php
                            $activity_query = "SELECT COUNT(*) as count FROM user_activities 
                                             WHERE user_id = ? AND DATE(created_at) = CURDATE()";
                            $stmt = mysqli_prepare($conn, $activity_query);
                            mysqli_stmt_bind_param($stmt, "i", $user['id']);
                            mysqli_stmt_execute($stmt);
                            $activity_count = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['count'];
                            if ($activity_count > 0) {
                                echo '<span class="notification-badge">' . $activity_count . '</span>';
                            }
                            ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Recent Activities</h6>
                            <?php
                            $activities_query = "SELECT * FROM user_activities 
                                               WHERE user_id = ? 
                                               ORDER BY created_at DESC LIMIT 5";
                            $stmt = mysqli_prepare($conn, $activities_query);
                            mysqli_stmt_bind_param($stmt, "i", $user['id']);
                            mysqli_stmt_execute($stmt);
                            $activities = mysqli_stmt_get_result($stmt);
                            
                            while ($activity = mysqli_fetch_assoc($activities)) {
                                echo '<a class="dropdown-item" href="#">';
                                echo htmlspecialchars($activity['description']);
                                echo '<br><small class="text-muted">' . date('M d, H:i', strtotime($activity['created_at'])) . '</small>';
                                echo '</a>';
                            }
                            ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center" href="view_activities.php">View All</a>
                        </div>
                    </li>

                    <!-- Theme Switch -->
                    <li class="nav-item">
                        <div class="theme-switch">
                            <input type="checkbox" id="theme-toggle" class="d-none">
                            <label for="theme-toggle" class="d-flex align-items-center">
                                <i class="bi bi-sun-fill me-2"></i>
                                <i class="bi bi-moon-fill"></i>
                            </label>
                        </div>
                    </li>

                    <!-- Profile -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" data-bs-toggle="dropdown">
                            <img src="<?php echo isset($user['profile_pic']) ? $user['profile_pic'] : 'assets/img/default-avatar.png'; ?>" 
                                 alt="Profile" class="rounded-circle" style="width: 32px; height: 32px;">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Hello, <?php echo htmlspecialchars($user['username']); ?></h6>
                            <a class="dropdown-item" href="profile.php">
                                <i class="bi bi-person"></i> My Profile
                            </a>
                            <a class="dropdown-item" href="ChangePassword.php">
                                <i class="bi bi-key"></i> Change Password
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
        
        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Request Leave</h5>
                        <p class="card-text">Apply for annual leave</p>
                        <a href="ApplyAnnualleave.php" class="btn btn-primary">Apply Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Request Permission</h5>
                        <p class="card-text">Request for permission</p>
                        <a href="ApplyPermission.php" class="btn btn-primary">Request Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Apply for Jobs</h5>
                        <p class="card-text">Browse and apply for internal positions</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applyJobModal">
                            Apply Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Application Modal -->
        <div class="modal fade" id="applyJobModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Apply for Position</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="process_job_application.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Available Positions</label>
                                <select class="form-select" name="job_id" required>
                                    <option value="">Select Position</option>
                                    <?php
                                    $jobs_query = "SELECT * FROM job_vacancies WHERE status = 'open'";
                                    $jobs_result = mysqli_query($conn, $jobs_query);
                                    while ($job = mysqli_fetch_assoc($jobs_result)) {
                                        echo "<option value='" . $job['id'] . "'>" . htmlspecialchars($job['title']) . " - " . htmlspecialchars($job['department']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cover Letter</label>
                                <textarea class="form-control" name="cover_letter" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Resume/CV</label>
                                <input type="file" class="form-control" name="cv" accept=".pdf,.doc,.docx" required>
                                <small class="text-muted">Accepted formats: PDF, DOC, DOCX</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Leave Requests Status -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>My Leave Requests</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Purpose</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $leave_sql = "SELECT * FROM leave_requests WHERE employee_id = ? ORDER BY start_date DESC";
                            $leave_stmt = mysqli_prepare($conn, $leave_sql);
                            mysqli_stmt_bind_param($leave_stmt, "i", $user['id']);
                            mysqli_stmt_execute($leave_stmt);
                            $leave_result = mysqli_stmt_get_result($leave_stmt);

                            if (mysqli_num_rows($leave_result) > 0) {
                                while ($row = mysqli_fetch_assoc($leave_result)) {
                                    $status_class = 'status-' . strtolower($row['status']);
                                    echo "<tr>";
                                    echo "<td>" . date('M d, Y', strtotime($row['start_date'])) . "</td>";
                                    echo "<td>" . date('M d, Y', strtotime($row['end_date'])) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
                                    echo "<td><span class='status-badge {$status_class}'>" . htmlspecialchars($row['status']) . "</span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No leave requests found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Permission Requests Status -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>My Permission Requests</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time Out</th>
                                <th>Time In</th>
                                <th>Reason</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $permission_sql = "SELECT * FROM permissions WHERE employee_id = ? ORDER BY created_at DESC";
                            $permission_stmt = mysqli_prepare($conn, $permission_sql);
                            
                            if ($permission_stmt === false) {
                                echo "<tr><td colspan='5' class='text-center'>Error preparing statement: " . mysqli_error($conn) . "</td></tr>";
                            } else {
                                if (!mysqli_stmt_bind_param($permission_stmt, "i", $user['id'])) {
                                    echo "<tr><td colspan='5' class='text-center'>Error binding parameters</td></tr>";
                                } else {
                                    if (!mysqli_stmt_execute($permission_stmt)) {
                                        echo "<tr><td colspan='5' class='text-center'>Error executing query</td></tr>";
                                    } else {
                                        $permission_result = mysqli_stmt_get_result($permission_stmt);
                                        if ($permission_result && mysqli_num_rows($permission_result) > 0) {
                                            while ($row = mysqli_fetch_assoc($permission_result)) {
                                                $status_class = 'status-' . strtolower($row['status']);
                                                echo "<tr>";
                                                echo "<td>" . date('M d, Y', strtotime($row['created_at'])) . "</td>";
                                                echo "<td>" . htmlspecialchars(isset($row['start_time']) ? $row['start_time'] : 'N/A') . "</td>";
                                                echo "<td>" . htmlspecialchars(isset($row['end_time']) ? $row['end_time'] : 'N/A') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
                                                echo "<td><span class='status-badge {$status_class}'>" . htmlspecialchars($row['status']) . "</span></td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5' class='text-center'>No permission requests found</td></tr>";
                                        }
                                    }
                                }
                                mysqli_stmt_close($permission_stmt);
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Job Applications Status -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>My Job Applications</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Department</th>
                                <th>Applied Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $job_sql = "SELECT ja.*, jv.title, jv.department 
                                      FROM job_applications ja 
                                      JOIN job_vacancies jv ON ja.job_id = jv.id 
                                      WHERE ja.applicant_id = ? 
                                      ORDER BY ja.created_at DESC";
                            $job_stmt = mysqli_prepare($conn, $job_sql);
                            mysqli_stmt_bind_param($job_stmt, "i", $user['id']);
                            mysqli_stmt_execute($job_stmt);
                            $job_result = mysqli_stmt_get_result($job_stmt);

                            if (mysqli_num_rows($job_result) > 0) {
                                while ($row = mysqli_fetch_assoc($job_result)) {
                                    $status_class = 'status-' . strtolower($row['status']);
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                                    echo "<td>" . date('M d, Y', strtotime($row['created_at'])) . "</td>";
                                    echo "<td><span class='status-badge {$status_class}'>" . htmlspecialchars($row['status']) . "</span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No job applications found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<script>
    // Theme switcher
    const themeToggle = document.getElementById('theme-toggle');
    
    // Check for saved theme preference
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        themeToggle.checked = true;
    }

    themeToggle.addEventListener('change', function() {
        if (this.checked) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        }
    });
</script>