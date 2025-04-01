<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Leave Request</title>
    <link rel="stylesheet" href="Add.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<?php
    $conn = mysqli_connect("localhost", 'root', '', 'hrm'); 
    if (!$conn) {
        die("Error: " . mysqli_connect_error());
    }

    $leave_id = isset($_GET['id']) ? (int)$_GET['id'] : null; // Get leave ID from URL and cast to integer

    if ($leave_id) {
        // Fetch leave data for editing using prepared statement
        $sql_select = "SELECT lr.*, e.username as employee_name, e.department 
                      FROM leave_requests lr 
                      JOIN employees e ON lr.employee_id = e.id 
                      WHERE lr.id = ?";
        $stmt = mysqli_prepare($conn, $sql_select);
        mysqli_stmt_bind_param($stmt, "i", $leave_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if (!$row) {
            echo "<div class='alert alert-danger'>Leave record not found.</div>";
            exit();
        }

        if (isset($_POST['update'])) {
            $employee_id = $row['employee_id']; // Keep the same employee
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $reason = $_POST['reason'];
            $type = $_POST['type'];
            $status = $_POST['status'];
            
            // Use prepared statement for update
            $sql_update = "UPDATE leave_requests 
                          SET start_date = ?, end_date = ?, reason = ?, 
                              type = ?, status = ? 
                          WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $sql_update);
            mysqli_stmt_bind_param($update_stmt, "sssssi", 
                $start_date, $end_date, $reason, $type, $status, $leave_id);
            
            if (mysqli_stmt_execute($update_stmt)) {
                header("Location: Annualleave.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error updating leave request: " . mysqli_error($conn) . "</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Leave ID not provided.</div>";
        exit();
    }
?>

    <form action="" method="post">
        <div class="container-fluid">
            <div class="container">
                <br><br>
                <div style="height: 750px;" class="sub-container">
                    <br>
                    <h1>Edit Leave Request</h1>
                    <br>
                    <label style="margin-left: 20px;" for="employee">Employee</label><br>
                    <input type="text" value="<?php echo htmlspecialchars($row['employee_name']); ?>" readonly><br><br>

                    <label style="margin-left: 20px;" for="department">Department</label><br>
                    <input type="text" value="<?php echo htmlspecialchars($row['department']); ?>" readonly><br><br>

                    <label style="margin-left: 20px;" for="start_date">Start Date</label><br>
                    <input type="date" name="start_date" value="<?php echo htmlspecialchars($row['start_date']); ?>" required><br><br>

                    <label style="margin-left: 20px;" for="end_date">End Date</label><br>
                    <input type="date" name="end_date" value="<?php echo htmlspecialchars($row['end_date']); ?>" required><br><br>

                    <label style="margin-left: 20px;" for="type">Leave Type</label><br>
                    <select name="type" required>
                        <option value="annual" <?php if ($row['type'] == 'annual') echo 'selected'; ?>>Annual</option>
                        <option value="sick" <?php if ($row['type'] == 'sick') echo 'selected'; ?>>Sick</option>
                        <option value="personal" <?php if ($row['type'] == 'personal') echo 'selected'; ?>>Personal</option>
                        <option value="other" <?php if ($row['type'] == 'other') echo 'selected'; ?>>Other</option>
                    </select><br><br>

                    <label style="margin-left: 20px;" for="reason">Reason</label><br>
                    <textarea name="reason" cols="24" rows="5" required><?php echo htmlspecialchars($row['reason']); ?></textarea><br><br>

                    <label style="margin-left: 20px;" for="status">Status</label><br>
                    <select name="status" required>
                        <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                        <option value="approved" <?php if ($row['status'] == 'approved') echo 'selected'; ?>>Approved</option>
                        <option value="rejected" <?php if ($row['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>
                    </select><br><br>

                    <button type="submit" name="update">Update</button><br><br>

                    <p style="text-align: center;"><a style="color: black;" href="Annualleave.php">Cancel</a></p>
                </div>
                <br>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>