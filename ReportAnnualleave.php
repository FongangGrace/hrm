<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annual Leave Report</title>
    <link rel="stylesheet" href="Add.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<?php
    $conn = mysqli_connect("localhost", 'root', '', 'hrm'); // Replace 'MOM' with your actual database name
    if (!$conn) {
        die("Error: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM Annualleave";
    $result = mysqli_query($conn, $sql);
?>
    <div class="container-fluid">
        <div class="container">
            <br><br>
            <div class="sub-container">
                <br>
                <h1>Annual Leave Report</h1>
                <br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Department</th>
                            <th>Purpose</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['Name'] . "</td>";
                                    echo "<td>" . $row['Start_Date'] . "</td>";
                                    echo "<td>" . $row['End_Date'] . "</td>";
                                    echo "<td>" . $row['Department'] . "</td>";
                                    echo "<td>" . $row['Purpose'] . "</td>";
                                    echo "<td>" . $row['Status'] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No leave records found.</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
                <br>
                <p style="text-align: center;"><a style="color: black;" href="Annualleave.php">Back to Home</a></p>
            </div>
            <br>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>