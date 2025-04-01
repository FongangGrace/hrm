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
    $conn = mysqli_connect("localhost", 'root', '', 'hrm'); // Replace 'MOM' with your actual database name
    if (!$conn) {
        die("Error: " . mysqli_connect_error());
    }

    if (isset($_POST['create'])) {
        $employee_name = $_POST['employee_name'];
        $employee_email = $_POST['employee_email'];
        $employee_phone = $_POST['employee_phone'];
        $employee_department = $_POST['employee_department'];
        $default_password = "00000000"; // Set your default password here
        $hashed_password = password_hash($default_password, PASSWORD_DEFAULT); // Hash the password

        $sql = "INSERT INTO Accounts (employee_name, employee_email, employee_phone, employee_department, password) VALUES ('$employee_name', '$employee_email', '$employee_phone', '$employee_department', '$hashed_password')";
        $result = mysqli_query($conn, $sql);
        header("Location:Accounts.php");

        if ($result) {
            echo "<div class='alert alert-success'>Employee account created successfully. Default password is: 00000000. Employee should change it after login.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error creating employee account: " . mysqli_error($conn) . "</div>";
        }
    }
?>
    <form action="" method="post">
        <div class="container-fluid">
            <div class="container">
                <br><br>
                <div style="height: 700px;" class="sub-container">
                    <br>
                    <h1>Create Employee Account</h1>
                    <br>
                    <label style="margin-left: 20px;" for="employee_name">Employee Name</label><br>
                    <input type="text" name="employee_name" placeholder="Employee Name" required><br><br>

                    <label style="margin-left: 20px;" for="employee_email">Employee Email</label><br>
                    <input type="email" name="employee_email" placeholder="Employee Email" required><br><br>

                    <label style="margin-left: 20px;" for="employee_phone">Employee Phone</label><br>
                    <input type="tel" name="employee_phone" placeholder="Employee Phone" required><br><br>

                    <label style="margin-left: 20px;" for="employee_department">Employee Department</label><br>
                    <input type="text" name="employee_department" placeholder="Employee Department" required><br><br>

                    <button type="submit" name="create">Create Account</button><br><br>

                    <p style="text-align: center;"><a style="color: black;" href="Accounts.php">Cancel</a></p>
                </div>
                <br>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>