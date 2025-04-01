<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'check_session.php';
require_role('hr');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = mysqli_connect("localhost", "root", "", "hrm");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);
    $status = isset($_POST['status']) ? $_POST['status'] : 'open';
    $posted_by = $_SESSION['user_id'];
    $deadline = date('Y-m-d', strtotime('+30 days')); // Set default deadline to 30 days from now

    $query = "INSERT INTO job_vacancies (title, department, description, requirements, status, posted_by, deadline, created_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssss", $title, $department, $description, $requirements, $status, $posted_by, $deadline);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: JobVacancies.php?success=Job vacancy added successfully");
    } else {
        header("Location: JobVacancies.php?error=Failed to add job vacancy: " . mysqli_error($conn));
    }

    mysqli_close($conn);
} else {
    header("Location: JobVacancies.php");
}
?>