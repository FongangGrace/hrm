<?php
require_once 'check_session.php';
require_role('hr');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = mysqli_connect("localhost", "root", "", "hrm");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize inputs
    $leave_id = isset($_POST['leave_id']) ? mysqli_real_escape_string($conn, $_POST['leave_id']) : '';
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : '';

    if ($leave_id && $status) {
        // Update the status in leave_requests table without updated_at
        $sql = "UPDATE leave_requests SET 
                status = '$status'
                WHERE id = '$leave_id'";

        if (mysqli_query($conn, $sql)) {
            // Add this action logging code
            $hr_email = $_SESSION['email'];
            $action_type = "Leave Request " . ucfirst($status);
            $description = "Leave request ID: $leave_id has been $status";
            
            $log_sql = "INSERT INTO hr_actions (action_type, description, performed_by) 
                        VALUES ('$action_type', '$description', '$hr_email')";
            mysqli_query($conn, $log_sql);
            
            // Get the updated record to confirm the change
            $check_sql = "SELECT lr.status, e.username 
                         FROM leave_requests lr 
                         JOIN employees e ON lr.employee_id = e.id 
                         WHERE lr.id = '$leave_id'";
            $result = mysqli_query($conn, $check_sql);
            $row = mysqli_fetch_assoc($result);
            
            if ($row) {
                $message = "Leave request for " . $row['username'] . " has been " . $status;
                header("Location: HumanResourceDashboard.php?success=" . urlencode($message));
            } else {
                header("Location: HumanResourceDashboard.php?success=Status updated to " . $status);
            }
        } else {
            header("Location: HumanResourceDashboard.php?error=Failed to update status: " . mysqli_error($conn));
        }
    }
    else {
        header("Location: HumanResourceDashboard.php?error=Invalid request parameters");
    }
    mysqli_close($conn);
    exit();
}

header("Location: HumanResourceDashboard.php");
exit();