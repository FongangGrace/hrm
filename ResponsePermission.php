<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Employee.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color:darkblue;">

<p style="margin-left:20px; margin-top:20px; color:white; cursor:pointer; font-size:20px;" onclick="location.href='EmployeeDashboard.php'">Back</p>
                           
<?php

$conn = mysqli_connect("localhost", 'root', '', 'hrm');
if(!$conn){
    die("error" .$conn.mysqli_connect_error());
}

  $sql = "SELECT * FROM permissions";
  $result = mysqli_query($conn, $sql);
  echo "<br>";
  echo "<h1 style='text-align:center; color:white; font-weight:bold;'>Permission Response</h1>";
  echo "<br>";
  echo "<br>";
  echo "<div style='background-color:darkblue;' class ='container-fluid'>";
    echo "<div style='background-color:darkblue;' class='container'>";
    echo "<table class='table'>";
        echo "<thead style='background-color:black;'>";
        echo "<th style='background-color:black; color:white; '>Employee_Name</th>";
        echo "<th style='background-color:black; color:white;'>Start_Date</th>";
        echo "<th style='background-color:black; color:white;'>End_Date</th>";
        echo "<th style='background-color:black; color:white;'>Department</th>";
        echo "<th style='background-color:black; color:white;'>Decision</th>";
        echo "</thead>";

        while($rows = mysqli_fetch_assoc($result)){
            echo "<tbody>";
                echo "<tr>";
                echo "<td>" .$rows['Name'] ."</td>";
                echo "<td>" .$rows['Start_Date']. "</td>";
                echo "<td>" .$rows['End_Date'] ."</td>";
                echo "<td>" .$rows['Department'] ."</td>";
                echo "<td>" .$rows['Status']. "</td>";
                echo "</tr>";
                echo "</tbody>";
        }

echo "</table>";
echo "</div>";
echo "</div>";

?>



<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
</body>
</html>