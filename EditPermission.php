<?php
session_start();
require_once 'includes/auth_check.php';
require_role('hr');

$conn = mysqli_connect('localhost', 'root', '', 'hrm');
if(!$conn){
    die("error" . mysqli_connect_error());
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // Check if id parameter exists
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: Permission.php");
        exit();
    }
    
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT p.*, e.username as employee_name, e.department 
            FROM permissions p 
            JOIN employees e ON p.employee_id = e.id 
            WHERE p.id = '$id'";
    $result = mysqli_query($conn, $sql);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        header("Location: Permission.php");
        exit();
    }
    
    $row = mysqli_fetch_assoc($result);
    
    echo "<div class='container-fluid'>";
    echo "<h2 style='text-align:center; font-weight:bold; margin-top:30px;'>Edit Permission</h2>";
    echo "<br>";
    echo "<br>";
    echo "<div class='container'>";
    echo "<form action='' method='POST'>";
    echo "<input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>";
    echo "<div class='mb-3'>";
    echo "<label for='employee_name' class='form-label'>Employee Name</label>";
    echo "<input type='text' class='form-control' id='employee_name' name='employee_name' value='" . htmlspecialchars($row['employee_name']) . "' readonly>";
    echo "</div>";
    echo "<div class='mb-3'>";
    echo "<label for='department' class='form-label'>Department</label>";
    echo "<input type='text' class='form-control' id='department' name='department' value='" . htmlspecialchars($row['department']) . "' readonly>";
    echo "</div>";
    echo "<div class='mb-3'>";
    echo "<label for='request_date' class='form-label'>Request Date</label>";
    echo "<input type='date' class='form-control' id='request_date' name='request_date' value='" . htmlspecialchars($row['request_date']) . "' required>";
    echo "</div>";
    echo "<div class='mb-3'>";
    echo "<label for='start_time' class='form-label'>Start Time</label>";
    echo "<input type='time' class='form-control' id='start_time' name='start_time' value='" . htmlspecialchars($row['start_time']) . "' required>";
    echo "</div>";
    echo "<div class='mb-3'>";
    echo "<label for='end_time' class='form-label'>End Time</label>";
    echo "<input type='time' class='form-control' id='end_time' name='end_time' value='" . htmlspecialchars($row['end_time']) . "' required>";
    echo "</div>";
    echo "<div class='mb-3'>";
    echo "<label for='reason' class='form-label'>Reason</label>";
    echo "<textarea class='form-control' id='reason' name='reason' rows='3' required>" . htmlspecialchars($row['reason']) . "</textarea>";
    echo "</div>";
    echo "<div class='mb-3'>";
    echo "<label for='status' class='form-label'>Status</label>";
    echo "<select class='form-select' id='status' name='status' required>";
    echo "<option value='pending'" . ($row['status'] == 'pending' ? ' selected' : '') . ">Pending</option>";
    echo "<option value='approved'" . ($row['status'] == 'approved' ? ' selected' : '') . ">Approved</option>";
    echo "<option value='rejected'" . ($row['status'] == 'rejected' ? ' selected' : '') . ">Rejected</option>";
    echo "</select>";
    echo "</div>";
    echo "<button type='submit' name='update' class='btn btn-primary'>Update Permission</button>";
    echo "<a href='Permission.php' class='btn btn-secondary'>Cancel</a>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Check if id parameter exists
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        header("Location: Permission.php");
        exit();
    }
    
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $request_date = mysqli_real_escape_string($conn, $_POST['request_date']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $sql = "UPDATE permissions SET 
            request_date = '$request_date',
            start_time = '$start_time',
            end_time = '$end_time',
            reason = '$reason',
            status = '$status'
            WHERE id = '$id'";
            
    if(mysqli_query($conn, $sql)){
        header("Location: Permission.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Permission - MOM System</title>
    <link rel="stylesheet" href="Report.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>