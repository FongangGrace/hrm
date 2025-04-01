<?php
require_once 'config.php';

// SQL commands to execute
$sql_commands = [
    // Drop existing table if it exists
    "DROP TABLE IF EXISTS payroll",
    
    // Create payroll table with the correct structure
    "CREATE TABLE payroll (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id INT,
        month INT NOT NULL,
        year INT NOT NULL,
        basic_salary DECIMAL(10,2) NOT NULL,
        allowances DECIMAL(10,2) DEFAULT 0.00,
        deductions DECIMAL(10,2) DEFAULT 0.00,
        net_salary DECIMAL(10,2) NOT NULL,
        status ENUM('pending', 'processed', 'paid') DEFAULT 'pending',
        processed_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (employee_id) REFERENCES employees(id),
        FOREIGN KEY (processed_by) REFERENCES users(id)
    )",
    
    // Add unique constraint
    "ALTER TABLE payroll
     ADD UNIQUE KEY unique_employee_month_year (employee_id, month, year)"
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

echo "<br>Payroll table setup completed. <a href='ManagePayment.php'>Return to Payment Management</a>";
?> 