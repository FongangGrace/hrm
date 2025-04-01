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
        $sql2 = "SELECT * FROM Jobvacancies WHERE ID = '$id'";
        $result2 = mysqli_query($conn, $sql2);
       $rows = mysqli_fetch_assoc($result2);

       $name = $rows['Job_Title'];
       $email = $rows['Description'];
       $phone = $rows['Requirement'];
       $depart = $rows['DeadLine'];
    }

    if(isset($_POST['edit'])){
        $id = $_GET['ID'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone =$_POST['phone'];
        $depart = $_POST['depart'];
        $sql = "UPDATE Recruitment SET Job_Title = '$name', Description = '$email', Requirement = '$phone', DeadLine = '$depart' WHERE ID = '$id'";
        $result = mysqli_query($conn, $sql);
        header("Location:Jobvacancies.php");
    }


    ?>
                <form action="" method="post">
                    <div class="container-fluid">
                        <div class="container">
                            <br>
                            <br>
                            <div style="height: 720px;" class="sub-container">
                                <br>
                             <h1>Edit this Jobvacancies</h1>
                             <br>
                             <input type="hidden" name="ID" id="">
                           
                             <label style="margin-left: 20px;" for="Job_Title">Job_Title</label>
                             <br>
                             <input type="text" name="name" id=""  placeholder="Job_Title" value="<?php echo $name ?>" required>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="Description">Description</label>
                             <textarea value="<?php echo $email ?>" style="margin-left: 20px; border-radius: 10px;  background-color: rgb(9, 9, 79); color: white; text-align: center;" name="email" id="" cols="24" rows="5"></textarea>
                           
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="Requirement"> Requirement</label>
                             <textarea value="<?php echo $phone ?>" style="margin-left: 20px; border-radius: 10px;  background-color: rgb(9, 9, 79); color: white; text-align: center;" name="phone" id="" cols="24" rows="5"></textarea>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="DeadLine">DeadLine</label>
                             <br>
                             <input type="date" name="depart" id=""  placeholder="DeadLine" value="<?php echo $depart ?>" required>
                             <br>
                             <br>
                            
                             <button type='edit' name='edit'>Edit</button>
                             <br>
                             <br>
                             <p style="text-align: center;"><a style="color: black;" href="Jobvacancies.php">Cancel</a></p>
                            </div>
                            <br>
                         </div>  
                    </div>  
                </form> 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
</body>
</html>