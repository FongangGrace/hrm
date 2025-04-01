<?php
session_start();
require_once 'includes/auth_check.php';
require_role('hr');

$conn = mysqli_connect("localhost", 'root', '', 'hrm');
if(!$conn){
    die("error" .$conn.mysqli_connect_error());
}

if(isset($_POST['add'])){
    $employee_id = $_POST['employee_id'];
    $request_date = date('Y-m-d');
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $reason = $_POST['reason'];
    $status = 'pending';
    $approved_by = NULL;

    $sql = "INSERT INTO permissions (employee_id, request_date, start_time, end_time, reason, status) 
            VALUES ($employee_id, '$request_date', '$start_time', '$end_time', '$reason', '$status')";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        header("Location: Permission.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error adding permission: " . mysqli_error($conn) . "</div>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Add.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
                <form action="" method="post">
                    <div class="container-fluid">
                        <div class="container">
                            <br>
                            <br>
                            <div style="height: 750px;" class="sub-container">
                                <br>
                             <h1>Add a New Permission</h1>
                             <br>
                             <label style="margin-left: 20px;" for="employee_id">Employee ID</label>
                             <br>
                             <input type="number" name="employee_id" id="" placeholder="Employee ID" required>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="start_time">Start Time</label>
                             <br>
                             <input type="time" name="start_time" id="" required>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="end_time">End Time</label>
                             <br>
                             <input type="time" name="end_time" id="" required>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="reason">Reason</label>
                             <br>
                             <textarea name="reason" id="" cols="24" rows="5" required></textarea>
                             <br>
                             <br>
                             <button type='submit' name='add'>Add</button>
                             <br>
                             <br>
                             <p style="text-align: center;"><a style="color: black;" href="Permission.php">Cancel</a></p>
                            </div>
                            <br>
                         </div>  
                    </div>  
                </form> 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
</body>
</html>