<?php
require_once 'check_session.php';
require_role('hr');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", "root", "", "hrm");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $employee_id = $_POST['employee_id'];
    $salary_id = $_POST['salary_id'];
    $basic_salary = $_POST['basic_salary'];
    $performance_bonus = isset($_POST['performance_bonus']) ? $_POST['performance_bonus'] : 0;
    $overtime_pay = isset($_POST['overtime_pay']) ? $_POST['overtime_pay'] : 0;
    $tax = isset($_POST['tax']) ? $_POST['tax'] : 0;
    $insurance = isset($_POST['insurance']) ? $_POST['insurance'] : 0;

    // Calculate total salary
    $total_bonus = $performance_bonus + $overtime_pay;
    $total_deductions = $tax + $insurance;
    $net_salary = $basic_salary + $total_bonus - $total_deductions;

    if (empty($salary_id)) {
        // Insert new salary record
        $query = "INSERT INTO salary_details (employee_id, basic_salary, performance_bonus, overtime_pay, 
                                            tax, insurance, total_bonus, total_deductions, net_salary) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "idddddddd", $employee_id, $basic_salary, $performance_bonus, 
                             $overtime_pay, $tax, $insurance, $total_bonus, $total_deductions, $net_salary);
    } else {
        // Update existing salary record
        $query = "UPDATE salary_details 
                 SET basic_salary = ?, performance_bonus = ?, overtime_pay = ?, 
                     tax = ?, insurance = ?, total_bonus = ?, total_deductions = ?, net_salary = ? 
                 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ddddddddi", $basic_salary, $performance_bonus, $overtime_pay, 
                             $tax, $insurance, $total_bonus, $total_deductions, $net_salary, $salary_id);
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ManageSalary.php?success=Salary details updated successfully");
    } else {
        header("Location: ManageSalary.php?error=Failed to update salary details");
    }
    exit();
}
?>