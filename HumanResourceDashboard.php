<?php
require_once 'check_session.php';
require_role('hr');

// Database connection
$conn = mysqli_connect("localhost", "root", "", "hrm");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch counts with error checking
$employee_count = 0;
$leave_count = 0;
$application_count = 0;
$position_count = 0;

// Get employee count
$employee_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM employees");
if ($employee_query) {
    $employee_count = mysqli_fetch_assoc($employee_query)['count'];
}

// Get leave count
// Update the leave count query
$leave_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM leave_requests WHERE status = 'pending'");
if ($leave_query) {
    $leave_count = mysqli_fetch_assoc($leave_query)['count'];
}

// Add this query to fetch pending leave requests
$pending_leaves_query = "SELECT lr.*, e.username as employee_name, e.department 
                        FROM leave_requests lr 
                        JOIN employees e ON lr.employee_id = e.id 
                        WHERE lr.status = 'pending' 
                        ORDER BY lr.created_at DESC 
                        LIMIT 5";
$pending_leaves_result = mysqli_query($conn, $pending_leaves_query);

// Get job applications count
$application_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM job_applications WHERE status = 'pending'");
if ($application_query) {
    $application_count = mysqli_fetch_assoc($application_query)['count'];
}

// Get open positions count
// Update the position count query
$position_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM job_vacancies WHERE status = 'open'");
if ($position_query) {
    $position_count = mysqli_fetch_assoc($position_query)['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<!-- Add at the top, after your PHP code -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard - MOM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
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

<!-- Replace existing navbar with -->
<nav class="navbar navbar-expand-lg" style="background-color: var(--nav-bg);">
    <div class="container">
        <div class="d-flex w-100 justify-content-end">
            <button onclick="location.href='logout.php'" class="btn btn-outline-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </div>
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
                        <!-- Same activities dropdown content as EmployeeDashboard -->
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
                        <h6 class="dropdown-header">Hello, HR Manager</h6>
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
<body class="bg-gray-50">
    <!-- Main Content -->
    <div class="container-fluid px-4 py-5">
        <!-- Welcome Message -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="text-2xl font-bold text-gray-800">Welcome, HR Manager</h1>
                <p class="text-gray-600">Manage G&C human resources</p>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <?php echo htmlspecialchars($_GET['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row g-4 mb-5">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dashboard-card hover-scale">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Total Employees</h3>
                            <div class="dashboard-stat"><?php echo $employee_count; ?></div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-people text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dashboard-card hover-scale">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Leave Requests</h3>
                            <div class="dashboard-stat"><?php echo $leave_count; ?></div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-calendar2-check text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dashboard-card hover-scale">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Job Applications</h3>
                            <div class="dashboard-stat"><?php echo $application_count; ?></div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-file-earmark-person text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dashboard-card hover-scale">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Open Positions</h3>
                            <div class="dashboard-stat"><?php echo $position_count; ?></div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-briefcase text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                <div class="row g-4">
                    <!-- Job Vacancies Quick Action -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="JobVacancies.php" class="card hover-scale text-decoration-none">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="bi bi-briefcase text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="card-title text-gray-800">Job Vacancies</h4>
                                <p class="card-text text-gray-600">Post and manage job openings</p>
                                <?php if ($position_count > 0): ?>
                                    <span class="badge bg-primary"><?php echo $position_count; ?> active</span>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>

                    <!-- Recruit Quick Action -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="ViewApplications.php" class="card hover-scale text-decoration-none">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="bi bi-people text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="card-title text-gray-800">Recruit</h4>
                                <p class="card-text text-gray-600">Review and manage applications</p>
                            </div>
                        </a>
                    </div>

                    <!-- Employee Accounts Quick Action -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="AddEmployee.php" class="card hover-scale text-decoration-none">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="bi bi-person-plus text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="card-title text-gray-800">Create Employee Accounts</h4>
                                <p class="card-text text-gray-600">Add new employees with default passwords</p>
                            </div>
                        </a>
                    </div>

                    <!-- Annual Leaves Quick Action -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="Annualleave.php" class="card hover-scale text-decoration-none">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="bi bi-calendar-check text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="card-title text-gray-800">Manage Annual Leaves</h4>
                                <p class="card-text text-gray-600">Handle leave requests and approvals</p>
                            </div>
                        </a>
                    </div>

                    <!-- Manage Salary Quick Action -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="ManageSalary.php" class="card hover-scale text-decoration-none">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="bi bi-cash text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="card-title text-gray-800">Manage Salary</h4>
                                <p class="card-text text-gray-600">Calculate and manage employee salaries</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Vacancies Management -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <!-- Job Vacancies -->
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Job Vacancies</h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addJobModal">
                                <i class="bi bi-plus-circle"></i> Add New Vacancy
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Job Title</th>
                                            <th>Department</th>
                                            <th>Description</th>
                                            <th>Requirements</th>
                                            <th>Status</th>
                                            <th>Deadline</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $vacancies_query = "SELECT * FROM job_vacancies ORDER BY created_at DESC";
                                        $vacancies_result = mysqli_query($conn, $vacancies_query);
                                        
                                        while ($job = mysqli_fetch_assoc($vacancies_result)) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($job['title']) . "</td>";
                                            echo "<td>" . htmlspecialchars($job['department']) . "</td>";
                                            echo "<td>" . htmlspecialchars(substr(isset($job['description']) ? $job['description'] : '', 0, 100)) . "...</td>";
                                            echo "<td>" . htmlspecialchars(substr(isset($job['requirements']) ? $job['requirements'] : '', 0, 100)) . "...</td>";
                                            echo "<td><span class='badge bg-" . ($job['status'] == 'open' ? 'success' : 'danger') . "'>" 
                                                     . ucfirst(htmlspecialchars($job['status'])) . "</span></td>";
                                            echo "<td>" . (isset($job['deadline']) ? date('Y-m-d', strtotime($job['deadline'])) : 'Not set') . "</td>";
                                            echo "<td>";
                                            echo "<button class='btn btn-sm btn-primary me-1' onclick='editVacancy(" . $job['id'] . ")'><i class='bi bi-pencil'></i></button>";
                                            echo "<button class='btn btn-sm btn-danger' onclick='deleteVacancy(" . $job['id'] . ")'><i class='bi bi-trash'></i></button>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rest of the dashboard content -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Job Applications</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Applied Date</th>
                                        <th>Status</th>
                                        <th>Documents</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $applications_query = "SELECT ja.*, jv.title, jv.department, e.username as applicant_name, 
                                                         ja.cover_letter, ja.cv, ja.status, ja.created_at 
                                                      FROM job_applications ja 
                                                      JOIN job_vacancies jv ON ja.job_id = jv.id 
                                                      JOIN employees e ON ja.applicant_id = e.id 
                                                      ORDER BY ja.created_at DESC";
                                    $applications_result = mysqli_query($conn, $applications_query);
                                    
                                    if (!$applications_result) {
                                        error_log("MySQL Error: " . mysqli_error($conn));
                                        echo '<tr><td colspan="7" class="text-center">Error loading applications. Please try again later.</td></tr>';
                                    } else {
                                        if (mysqli_num_rows($applications_result) > 0) {
                                            while ($app = mysqli_fetch_assoc($applications_result)) { ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($app['applicant_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($app['title']); ?></td>
                                                    <td><?php echo htmlspecialchars($app['department']); ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($app['created_at'])); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php 
                                                            echo ($app['status'] == 'pending') ? 'warning' : 
                                                                 (($app['status'] == 'approved') ? 'success' : 'danger'); 
                                                        ?>">
                                                            <?php echo ucfirst(htmlspecialchars($app['status'])); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($app['cv'])): ?>
                                                            <a href="<?php echo htmlspecialchars($app['cv']); ?>" 
                                                               class="btn btn-sm btn-primary" target="_blank">
                                                                View Resume
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (isset($app['cover_letter']) && !empty($app['cover_letter'])): ?>
                                                            <button type="button" class="btn btn-sm btn-info" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#coverLetterModal<?php echo $app['id']; ?>">
                                                                View Cover Letter
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <form action="update_application_status.php" method="POST" class="d-inline">
                                                            <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                                            <select class="form-select form-select-sm d-inline-block w-auto" name="status">
                                                                <option value="pending" <?php echo $app['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                                <option value="approved" <?php echo $app['status'] == 'approved' ? 'selected' : ''; ?>>Approve</option>
                                                                <option value="rejected" <?php echo $app['status'] == 'rejected' ? 'selected' : ''; ?>>Reject</option>
                                                            </select>
                                                            <button type="submit" class="btn btn-sm btn-primary ms-2">Update</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No job applications found</td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add this before the Quick Actions section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pending Leave Requests</h5>
                        <a href="Annualleave.php" class="btn btn-primary btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Purpose</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($leave = mysqli_fetch_assoc($pending_leaves_result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($leave['employee_name']); ?></td>
                                        <td><?php echo htmlspecialchars($leave['department']); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($leave['start_date'])); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($leave['end_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($leave['reason']); ?></td>
                                        <td>
                                            <form action="update_leave_status.php" method="POST" class="d-inline">
                                                <input type="hidden" name="leave_id" value="<?php echo $leave['id']; ?>">
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this leave request?')">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                            <form action="update_leave_status.php" method="POST" class="d-inline">
                                                <input type="hidden" name="leave_id" value="<?php echo $leave['id']; ?>">
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this leave request?')">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><td><?php echo htmlspecialchars(substr(isset($job['requirements']) ? $job['requirements'] : '', 0, 100)) . '...'; ?></td>
<td>
    <span class="badge bg-<?php echo isset($job['status']) ? ($job['status'] == 'open' ? 'success' : 'danger') : 'secondary'; ?>">
        <?php echo ucfirst(htmlspecialchars(isset($job['status']) ? $job['status'] : 'unknown')); ?>
    </span>
</td>
<td>
    <?php 
    // Handle deadline display with proper check
    echo isset($job['deadline']) ? date('d/m/Y', strtotime($job['deadline'])) : 'Not set';
    ?>
</td>