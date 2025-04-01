<?php
require_once 'config.php';

// SQL commands to execute
$sql_commands = [
    // Drop attendance-related tables
    "DROP TABLE IF EXISTS attendance",
    "DROP TABLE IF EXISTS attendance_logs",
    
    // Modify payroll table
    "ALTER TABLE payroll
     DROP COLUMN IF EXISTS late_days,
     DROP COLUMN IF EXISTS absent_days,
     DROP COLUMN IF EXISTS late_deductions,
     DROP COLUMN IF EXISTS absent_deductions",
    
    // Update payroll table structure
    "ALTER TABLE payroll
     ADD COLUMN IF NOT EXISTS basic_salary DECIMAL(10,2) NOT NULL DEFAULT 0,
     ADD COLUMN IF NOT EXISTS allowances DECIMAL(10,2) NOT NULL DEFAULT 0,
     ADD COLUMN IF NOT EXISTS deductions DECIMAL(10,2) NOT NULL DEFAULT 0,
     ADD COLUMN IF NOT EXISTS net_salary DECIMAL(10,2) NOT NULL DEFAULT 0,
     ADD COLUMN IF NOT EXISTS status ENUM('pending', 'processing', 'paid') NOT NULL DEFAULT 'pending'",
    
    // Update employees table
    "ALTER TABLE employees
     DROP COLUMN IF EXISTS attendance_status,
     DROP COLUMN IF EXISTS last_attendance_date"
];

// Execute each SQL command
foreach ($sql_commands as $sql) {
    if (mysqli_query($conn, $sql)) {
        echo "Successfully executed: " . substr($sql, 0, 50) . "...<br>";
    } else {
        echo "Error executing: " . substr($sql, 0, 50) . "...<br>";
        echo "Error message: " . mysqli_error($conn) . "<br><br>";
    }
}

echo "<br>Database update completed. <a href='ManagePayment.php'>Return to Payment Management</a>";
?> 