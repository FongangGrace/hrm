<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  <link rel="stylesheet" href="Report.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body>
 
<?php


$conn = mysqli_connect('localhost', 'root' ,'', 'hrm');
if(!$conn){
    die("error" .$conn.mysqli_connect_error());

}

 if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $sql = "SELECT * FROM employees";
    $result = mysqli_query($conn, $sql);
    
   
    echo "<div class='container-fluid'>";
    echo "<h2 style='text-align:center; font-weight:bold; margin-top:30px;'>The Employee Report</h2>";
    echo "<br>";
    echo "<br>";
        echo "<div class='container'>";
    echo "<table class='table'>";
        echo "<thead>";
            echo "<th>Name</th>";
            echo "<th>Email</th>";
            echo "<th>Phone</th>";
            echo "<th>Department</th>";
            echo "</thead>";

            while($rows = mysqli_fetch_assoc($result)){
                echo "<tbody>";
                echo "<tr>";
                    echo "<td>" . $rows['username'] . "</td>";
                    echo "<td>" . $rows['email'] . "</td>";
                    echo "<td>" . $rows['phone'] . "</td>";
                    echo "<td>" . $rows['department'] . "</td>";
                   
                    echo "</tr>";
                    echo "</tbody>";    
            }

            echo "</table>";
            echo "</div>";
            echo "</div>";
 }

 

?>



<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
</body>
</html>