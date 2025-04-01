<?php
session_start();
require_once 'includes/auth_check.php';
require_role('hr');

$conn = mysqli_connect('localhost', 'root', '', 'hrm');
if(!$conn){
    die("error" . mysqli_connect_error());
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $sql = "SELECT p.*, e.username as employee_name, e.department 
            FROM permissions p 
            JOIN employees e ON p.employee_id = e.id 
            ORDER BY p.request_date DESC";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    echo "<div class='container-fluid'>";
    echo "<h2 style='text-align:center; font-weight:bold; margin-top:30px;'>The Permission Report</h2>";
    echo "<br>";
    echo "<br>";
    echo "<div class='container'>";
    echo "<table class='table table-hover'>";
    echo "<thead class='table-primary'>";
        echo "<tr>";
            echo "<th>Employee Name</th>";
            echo "<th>Department</th>";
            echo "<th>Request Date</th>";
            echo "<th>Start Time</th>";
            echo "<th>End Time</th>";
            echo "<th>Reason</th>";
            echo "<th>Status</th>";
        echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while($rows = mysqli_fetch_assoc($result)){
        echo "<tr>";
            echo "<td>" . htmlspecialchars($rows['employee_name']) . "</td>";
            echo "<td>" . htmlspecialchars($rows['department']) . "</td>";
            echo "<td>" . date('M d, Y', strtotime($rows['request_date'])) . "</td>";
            echo "<td>" . date('h:i A', strtotime($rows['start_time'])) . "</td>";
            echo "<td>" . date('h:i A', strtotime($rows['end_time'])) . "</td>";
            echo "<td>" . htmlspecialchars($rows['reason']) . "</td>";
            echo "<td>";
            switch($rows['status']) {
                case 'approved':
                    echo "<span class='badge bg-success'>Approved</span>";
                    break;
                case 'rejected':
                    echo "<span class='badge bg-danger'>Rejected</span>";
                    break;
                case 'pending':
                    echo "<span class='badge bg-warning'>Pending</span>";
                    break;
                default:
                    echo "<span class='badge bg-secondary'>Unknown</span>";
            }
            echo "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permission Report - MOM System</title>
    <link rel="stylesheet" href="Report.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>