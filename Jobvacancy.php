<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Vacancies - MOM System</title>
    <link rel="stylesheet" href="Report.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color:darkblue;">
    <p style="margin-left:20px; margin-top:20px; color:white; cursor:pointer; font-size:20px;" onclick="location.href='EmployeeDashboard.php'">Back</p>
      
    <?php
    $conn = mysqli_connect('localhost', 'root', '', 'hrm');
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        $sql = "SELECT * FROM job_vacancies WHERE status = 'open' ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);
        
        if (!$result) {
            die("Error executing query: " . mysqli_error($conn));
        }

        echo "<h2 style='text-align:center; font-weight:bold; margin-top:30px; color:white;'>Jobs Available</h2>";
        echo "<br>";
        echo "<br>";
        
        if (mysqli_num_rows($result) == 0) {
            echo "<div class='container text-center'>";
            echo "<div style='background-color:aliceblue; border-radius:20px; padding:20px;'>";
            echo "<p>No job vacancies available at the moment.</p>";
            echo "</div>";
            echo "</div>";
        } else {
            while($rows = mysqli_fetch_assoc($result)){
                echo "<div style='background-color:darkblue;' class='container-fluid'>";
                echo "<div class='container text-center'>";
                echo "<div class='row'>";
                echo "<div style='background-color:aliceblue; border-radius:20px; padding:20px; margin-bottom:20px;' class='col'>";
                    echo "<h4 style='font-weight:bold;'>Title: " . htmlspecialchars($rows['title']) . "</h4>";
                    echo "<p><strong>Department:</strong> " . htmlspecialchars($rows['department']) . "</p>";
                    echo "<p><strong>Description:</strong> " . nl2br(htmlspecialchars($rows['description'])) . "</p>";
                    echo "<p><strong>Requirements:</strong> " . nl2br(htmlspecialchars($rows['requirements'])) . "</p>";
                    echo "<p><strong>Posted On:</strong> " . date('M d, Y', strtotime($rows['created_at'])) . "</p>";
                    echo "<button class='btn btn-primary' onclick='applyForJob(" . $rows['id'] . ")'>Apply Now</button>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }
    }
    ?>

    <script>
    function applyForJob(jobId) {
        // Add your job application logic here
        alert('Application feature coming soon!');
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>