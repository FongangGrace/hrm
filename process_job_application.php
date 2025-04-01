<?php
require_once 'check_session.php';
require_role('employee');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", "root", "", "hrm");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $job_id = $_POST['job_id'];
    $applicant_id = $_SESSION['user_id'];
    $cover_letter = $_POST['cover_letter'];
    
    // Handle file upload
    $cv_path = '';
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $upload_dir = 'uploads/resumes/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . $_FILES['cv']['name'];
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['cv']['tmp_name'], $target_file)) {
            $cv_path = $target_file;
        }
    }

    // Insert application with error handling
    $query = "INSERT INTO job_applications (job_id, applicant_id, cv, cover_letter, status, created_at) 
              VALUES (?, ?, ?, ?, 'pending', NOW())";
    
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiss", $job_id, $applicant_id, $cv_path, $cover_letter);
        
        if (mysqli_stmt_execute($stmt)) {
            // Log the activity
            $activity_desc = "Applied for a new job position";
            $log_query = "INSERT INTO user_activities (user_id, description, created_at) VALUES (?, ?, NOW())";
            $log_stmt = mysqli_prepare($conn, $log_query);
            mysqli_stmt_bind_param($log_stmt, "is", $applicant_id, $activity_desc);
            mysqli_stmt_execute($log_stmt);
            
            header("Location: EmployeeDashboard.php?success=Application submitted successfully");
            exit();
        } else {
            error_log("Application insert error: " . mysqli_error($conn));
            header("Location: EmployeeDashboard.php?error=Failed to submit application");
            exit();
        }
    }
}
?>