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

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: Recruitment.php");
    exit();
}

$application_id = $_GET['id'];

// Fetch application details
$query = "SELECT ja.*, jv.title as position, jv.department, jv.description as job_description, 
          jv.requirements as job_requirements, e.username as applicant_name, e.email as applicant_email, 
          e.phone as applicant_phone
          FROM job_applications ja 
          JOIN job_vacancies jv ON ja.job_id = jv.id 
          LEFT JOIN employees e ON ja.applicant_id = e.id 
          WHERE ja.id = ?";

$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("Error preparing statement: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $application_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: Recruitment.php");
    exit();
}

$application = mysqli_fetch_assoc($result);

// Fetch application notes
$notes_query = "SELECT * FROM application_notes WHERE application_id = ? ORDER BY created_at DESC";
$notes_stmt = mysqli_prepare($conn, $notes_query);
mysqli_stmt_bind_param($notes_stmt, "i", $application_id);
mysqli_stmt_execute($notes_stmt);
$notes_result = mysqli_stmt_get_result($notes_stmt);

// Handle note submission
if (isset($_POST['add_note'])) {
    $note_text = $_POST['note'];
    $note_query = "INSERT INTO application_notes (application_id, note, created_at) VALUES (?, ?, NOW())";
    $note_stmt = mysqli_prepare($conn, $note_query);
    mysqli_stmt_bind_param($note_stmt, "is", $application_id, $note_text);
    
    if (mysqli_stmt_execute($note_stmt)) {
        header("Location: ViewApplication.php?id=" . $application_id);
        exit();
    } else {
        $error_message = "Error adding note: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application - MOM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="Recruitment.php">
                <img src="logo.jpg" alt="Logo" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <span class="font-semibold text-xl">View Application</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="Recruitment.php">
                            <i class="bi bi-arrow-left"></i> Back to Applications
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
        <div class="row">
            <!-- Application Details -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Application Details</h5>
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted">Applicant Information</h6>
                                <p><strong>Name:</strong> <?php echo htmlspecialchars(isset($application['applicant_name']) ? $application['applicant_name'] : 'N/A'); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars(isset($application['applicant_email']) ? $application['applicant_email'] : 'N/A'); ?></p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars(isset($application['applicant_phone']) ? $application['applicant_phone'] : 'N/A'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Application Information</h6>
                                <p><strong>Position:</strong> <?php echo htmlspecialchars($application['position']); ?></p>
                                <p><strong>Department:</strong> <?php echo htmlspecialchars($application['department']); ?></p>
                                <p><strong>Status:</strong> 
                                    <?php
                                    $status_class = [
                                        'pending' => 'bg-warning',
                                        'reviewed' => 'bg-info',
                                        'accepted' => 'bg-success',
                                        'rejected' => 'bg-danger'
                                    ];
                                    $status = $application['status'];
                                    echo "<span class='badge {$status_class[$status]}'>" . ucfirst($status) . "</span>";
                                    ?>
                                </p>
                                <p><strong>Applied On:</strong> <?php echo date('M d, Y', strtotime($application['created_at'])); ?></p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted">Job Description</h6>
                            <div class="border rounded p-3 bg-light">
                                <?php echo nl2br(htmlspecialchars($application['job_description'])); ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted">Requirements</h6>
                            <div class="border rounded p-3 bg-light">
                                <?php echo nl2br(htmlspecialchars($application['job_requirements'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Application Notes</h5>
                        
                        <!-- Add Note Form -->
                        <form method="POST" class="mb-4">
                            <div class="mb-3">
                                <label class="form-label">Add Note</label>
                                <textarea name="note" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" name="add_note" class="btn btn-primary">Add Note</button>
                        </form>

                        <!-- Notes List -->
                        <div class="notes-list">
                            <?php while ($note = mysqli_fetch_assoc($notes_result)): ?>
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <p class="card-text"><?php echo nl2br(htmlspecialchars($note['note'])); ?></p>
                                        <small class="text-muted">
                                            <?php echo date('M d, Y H:i', strtotime($note['created_at'])); ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 