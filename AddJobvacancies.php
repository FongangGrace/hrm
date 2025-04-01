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
                $title = $_POST['title'];
                $description = $_POST['description'];
                $requirements = $_POST['requirement'];
                $department = $_POST['department'];
                $status = 'open';
                $posted_by = 1;

                $sql = "INSERT INTO job_vacancies (title, department, description, requirements, status, posted_by) 
                        VALUES ('$title', '$department', '$description', '$requirements', '$status', $posted_by)";
                $result = mysqli_query($conn, $sql);
                
                if ($result) {
                    header("Location: Jobvacancies.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Error adding job vacancy: " . mysqli_error($conn) . "</div>";
                }
            }

 ?>
                <form action="" method="post">
                    <div class="container-fluid">
                        <div class="container">
                            <br>
                            <br>
                            <div style="height: 700px;" class="sub-container">
                                <br>
                             <h1>Add a New Job</h1>
                             <br>
                             <label style="margin-left: 20px;" for="title">Job_Title</label>
                             <br>
                             <input type="text" name="title" id=""  placeholder="Job_Title" required>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="department">Department</label>
                             <br>
                             <input type="text" name="department" id="" placeholder="Department" required>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="description">Description</label>
                            <textarea style="margin-left: 20px; border-radius: 10px;  background-color: rgb(62, 62, 212); color: white; text-align: center;" name="description" id="" cols="24" rows="5"></textarea>
                             <br>
                             <br>
                             <label style="margin-left: 20px;" for="Requirements">Requirement</label>
                             <textarea style="margin-left: 20px; border-radius: 10px;  background-color: rgb(62, 62, 212); color: white; text-align: center;" name="requirement" id="" cols="24" rows="5"></textarea>
                            <br>
                             <br>
                            
                             <label style="margin-left: 20px;" for="DeadLine">DeadLine</label>
                             <br>
                             <input type="date" name="deadline" id=""  placeholder="DeadLine" required>
                             <br>
                             <br>
                             <button type='submit' name='add'>Add</button>
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