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

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $id = $_GET['ID'];
        $sql2 = "SELECT * FROM employees WHERE id = '$id'";
        $result2 = mysqli_query($conn, $sql2);
        $rows = mysqli_fetch_assoc($result2);

        $name = $rows['username'];
        $email = $rows['email'];
        $phone = $rows['phone'];
        $depart = $rows['department'];

    }

    if(isset($_POST['edit'])){
        $id = $_GET['ID'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $depart = $_POST['depart'];

        $sql = "UPDATE employees SET username = '$name', email = '$email', phone = '$phone', department = '$depart' WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        header("Location:Employee.php");
    }


    ?>
                <form action="" method="post">
                    <div class="container-fluid">
                        <div class="container">
                            <br>
                            <br>
                            <div style="height: 500px;" class="sub-container">
                                <br>
                             <h1>Edit this Course</h1>
                             <br>
                             <input type="hidden" name="ID" id="">

                             <label style="margin-left: 20px;" for="Name">Name</label>
                             <br>
                             <input type="text" name="name" id=""  placeholder="Name" value="<?php echo $name ?>" required>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="Email">Email</label>
                             <input type="email" name="email" id=""  placeholder="Email" value="<?php echo $email ?>" required>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="Phone"> Phone</label>
                             <input type="number" name="phone" id="" placeholder="Phone" value="<?php echo $phone ?>" required>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="Department">Department</label>
                             <input type="text" name="depart" id="" placeholder="Department" value="<?php echo $depart ?>" required>
                             <br>
                             <br>
                             <button type='edit' name='edit'>Edit</button>
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