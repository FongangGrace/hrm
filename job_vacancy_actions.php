<?php
require_once 'check_session.php';
require_role('hr');

$conn = mysqli_connect("localhost", "root", "", "hrm");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $vacancy_id = mysqli_real_escape_string($conn, $_POST['vacancy_id']);

    if ($action == 'delete') {
        $query = "DELETE FROM job_vacancies WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $vacancy_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "success";
        } else {
            echo "error";
        }
    } elseif ($action == 'update') {
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $query = "UPDATE job_vacancies SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $status, $vacancy_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "success";
        } else {
            echo "error";
        }
    }
}
mysqli_close($conn);
?>