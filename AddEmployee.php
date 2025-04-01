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
            if(!$conn){
                die("error" .$conn.mysqli_connect_error());
            }

            if(isset($_POST['add'])){
                $username = $_POST['name'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $department = $_POST['depart'];
                $password = password_hash('employee123', PASSWORD_DEFAULT); 
                $status = 'active';

                $sql = "INSERT INTO employees (username, password, email, phone, department, status) 
                        VALUES ('$username', '$password', '$email', '$phone', '$department', '$status')";
                $result = mysqli_query($conn, $sql);
                
                if ($result) {
                    header("Location: Employee.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Error adding employee: " . mysqli_error($conn) . "</div>";
                }
            }

 ?>
                <form action="" method="post">
                    <div class="container-fluid">
                        <div class="container">
                            <br>
                            <br>
                            <div style="height: 500px;" class="sub-container">
                                <br>
                             <h1>Add a New Employee</h1>
                             <br>
                             <label style="margin-left: 20px;" for="Name">Name</label>
                             <br>
                             <input type="text" name="name" id=""  placeholder="Name" required>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="Email">Email</label>
                             <input type="email" name="email" id=""  placeholder="Email" required>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="Phone"> Phone</label>
                             <input type="number" name="phone" id="" placeholder="Phone" required>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="Department">Department</label>
                             <input type="text" name="depart" id="" placeholder="Department" required>
                             <br>
                             <br>
                             <button type='submit' name='add'>Add</button>
                             <br>
                             <br>
                             <p style="text-align: center;"><a style="color: black;" href="Employee.php">Cancel</a></p>
                            </div>
                            <br>
                         </div>  
                    </div>  
                </form> 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
</body>
</html>