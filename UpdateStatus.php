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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['application_id']) && isset($_POST['new_status'])) {
    $application_id = mysqli_real_escape_string($conn, $_POST['application_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);
    $interview_date = !empty($_POST['interview_date']) ? mysqli_real_escape_string($conn, $_POST['interview_date']) : null;
    $updated_at = date('Y-m-d H:i:s');

    // Update application status
    $query = "UPDATE job_applications SET status = ?, interview_date = ?, updated_at = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $new_status, $interview_date, $updated_at, $application_id);

    if (mysqli_stmt_execute($stmt)) {
        // Add a note about the status change
        $note = "Application status updated to: " . ucfirst($new_status);
        if ($interview_date) {
            $note .= " (Interview scheduled for: " . date('M d, Y', strtotime($interview_date)) . ")";
        }
        
        $note_query = "INSERT INTO application_notes (application_id, note, created_at) VALUES (?, ?, ?)";
        $note_stmt = mysqli_prepare($conn, $note_query);
        mysqli_stmt_bind_param($note_stmt, "iss", $application_id, $note, $updated_at);
        mysqli_stmt_execute($note_stmt);

        $_SESSION['success_message'] = "Application status updated successfully!";
    } else {
        $_SESSION['error_message'] = "Error updating status: " . mysqli_error($conn);
    }

    // Redirect back to the application view
    header("Location: ViewApplication.php?id=" . $application_id);
    exit();
} else {
    header("Location: Recruitment.php");
    exit();
}
?> 