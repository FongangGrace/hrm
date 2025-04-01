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

<?php
    $conn = mysqli_connect("localhost", 'root', '', 'hrm'); 
    if (!$conn) {
        die("Error: " . mysqli_connect_error());
    }

    if (isset($_POST['add'])) {
        $employee_id = $_POST['employee_id'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $reason = $_POST['reason'];
        $type = $_POST['type'];
        $status = 'pending';
        $approved_by = NULL;

        $sql = "INSERT INTO leave_requests (employee_id, start_date, end_date, reason, type, status) 
                VALUES ($employee_id, '$start_date', '$end_date', '$reason', '$type', '$status')";
        $result = mysqli_query($conn, $sql);
       
        if ($result) {
            header("Location: Annualleave.php"); // Redirect on success
            exit(); // Ensure no further code execution
        } else {
            echo "<div class='alert alert-danger'>Error adding leave request: " . mysqli_error($conn) . "</div>";
        }
    }
?>
    <form action="" method="post">
        <div class="container-fluid">
            <div class="container">
                <br><br>
                <div style="height: 750px;" class="sub-container">
                    <br>
                    <h1>Add a New Annual Leave</h1>
                    <br>
                    <label style="margin-left: 20px;" for="employee_id">Employee ID</label>
                    <br>
                    <input type="number" name="employee_id" id="" placeholder="Employee ID" required>
                    <br>
                    <br>
                    <label style="margin-left: 20px;" for="start_date">Start Date</label>
                    <br>
                    <input type="date" name="start_date" id="" required>
                    <br>
                    <br>
                    <label style="margin-left: 20px;" for="end_date">End Date</label>
                    <br>
                    <input type="date" name="end_date" id="" required>
                    <br>
                    <br>
                    <label style="margin-left: 20px;" for="type">Leave Type</label>
                    <br>
                    <select name="type" id="" required>
                        <option value="" disabled selected>Select Type</option>
                        <option value="annual">Annual</option>
                        <option value="sick">Sick</option>
                        <option value="personal">Personal</option>
                        <option value="other">Other</option>
                    </select>
                    <br>
                    <br>
                    <label style="margin-left: 20px;" for="reason">Reason</label>
                    <br>
                    <textarea name="reason" id="" cols="24" rows="5" required></textarea>
                    <br>
                    <br>
                    <button type="submit" name="add">Add</button><br><br>

                    <p style="text-align: center;"><a style="color: black;" href="Annualleave.php">Cancel</a></p>
                </div>
                <br>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>