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

// Get available job vacancies
$jobs_sql = "SELECT * FROM job_vacancies WHERE status = 'open'";
$jobs_result = mysqli_query($conn, $jobs_sql);
$jobs = array();
if ($jobs_result) {
    while ($row = mysqli_fetch_assoc($jobs_result)) {
        $jobs[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $job_id = (int)$_POST['job_id'];
    $resume = mysqli_real_escape_string($conn, $_POST['resume']);
    $cover_letter = mysqli_real_escape_string($conn, $_POST['cover_letter']);
    $status = 'pending';

    $sql = "INSERT INTO job_applications (job_id, applicant_name, email, phone, resume, cover_letter, status, application_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issssss", $job_id, $name, $email, $phone, $resume, $cover_letter, $status);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert alert-success'>Application submitted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error submitting application: " . mysqli_error($conn) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application - MOM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Job Application Form</h1>
            
            <?php if (empty($jobs)): ?>
            <div class="alert alert-info">
                No job vacancies are currently available. Please check back later.
            </div>
            <?php else: ?>
            <form action="" method="post" class="space-y-6">
                <div class="form-group">
                    <label for="job_id" class="block text-sm font-medium text-gray-700">Select Position</label>
                    <select name="job_id" id="job_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">Select a position</option>
                        <?php foreach ($jobs as $job): ?>
                        <option value="<?php echo htmlspecialchars($job['id']); ?>">
                            <?php echo htmlspecialchars($job['title']); ?> - <?php echo htmlspecialchars($job['department']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="form-group">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="form-group">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="form-group">
                    <label for="resume" class="block text-sm font-medium text-gray-700">Resume/CV</label>
                    <textarea name="resume" id="resume" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Paste your resume content here" required></textarea>
                </div>

                <div class="form-group">
                    <label for="cover_letter" class="block text-sm font-medium text-gray-700">Cover Letter</label>
                    <textarea name="cover_letter" id="cover_letter" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Write your cover letter here" required></textarea>
                </div>

                <div class="flex justify-between items-center">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Submit Application
                    </button>
                    <a href="EmployeeDashboard.php" class="text-gray-600 hover:text-gray-800">Back to Dashboard</a>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>