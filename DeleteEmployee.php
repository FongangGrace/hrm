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

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Delete related records first
        mysqli_query($conn, "DELETE FROM job_applications WHERE applicant_id = $id");
        mysqli_query($conn, "DELETE FROM leave_requests WHERE employee_id = $id");
        mysqli_query($conn, "DELETE FROM permissions WHERE employee_id = $id");
        mysqli_query($conn, "DELETE FROM attendance WHERE employee_id = $id");
        mysqli_query($conn, "DELETE FROM payroll WHERE employee_id = $id");
        
        // Finally delete the employee
        mysqli_query($conn, "DELETE FROM employees WHERE id = $id");
        
        // If everything is successful, commit the transaction
        mysqli_commit($conn);
        header("Location: Employee.php");
        exit();
    } catch (Exception $e) {
        // If there's an error, rollback the transaction
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}

mysqli_close($conn);
?>