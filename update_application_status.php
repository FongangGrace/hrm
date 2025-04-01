<?php
require_once 'check_session.php';
require_role('hr');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = mysqli_connect("localhost", "root", "", "hrm");
    
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];
    
    $query = "UPDATE job_applications SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $application_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Log the activity
        $activity_desc = "Updated job application status to " . $status;
        $log_query = "INSERT INTO user_activities (user_id, description, created_at) VALUES (?, ?, NOW())";
        $log_stmt = mysqli_prepare($conn, $log_query);
        mysqli_stmt_bind_param($log_stmt, "is", $_SESSION['user_id'], $activity_desc);
        mysqli_stmt_execute($log_stmt);
        
        header("Location: HumanResourceDashboard.php?success=Application status updated successfully");
    } else {
        header("Location: HumanResourceDashboard.php?error=Failed to update application status");
    }
} else {
    header("Location: HumanResourceDashboard.php");
}
?>