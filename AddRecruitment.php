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
if (!$conn) {
    die("error" . mysqli_connect_error());
}

// Handle recruitment (e.g., changing status)
if (isset($_POST['recruit'])) {
    $applicantId = $_POST['applicant_id'];
    $status = 'Recruited';

    $updateSql = "UPDATE Applicants SET Status = '$status' WHERE Applicant_ID = $applicantId";
    if (mysqli_query($conn, $updateSql)) {
        echo "<div class='alert alert-success'>Applicant recruited successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error recruiting applicant: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch applicants
$sql = "SELECT * FROM Applicants";
$result = mysqli_query($conn, $sql);

echo "<div class='container mt-4'>";
echo "<h2>Applicant List</h2>";
echo "<table class='table table-bordered'>";
echo "<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Job Applied</th><th>Status</th><th>Action</th></tr></thead>";
echo "<tbody>";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Applicant_ID'] . "</td>";
        echo "<td>" . $row['Name'] . "</td>";
        echo "<td>" . $row['Email'] . "</td>";
        echo "<td>" . $row['Phone'] . "</td>";
        echo "<td>" . $row['Job_Applied'] . "</td>";
        echo "<td>" . $row['Status'] . "</td>";
        echo "<td>";
        if ($row['Status'] != 'Recruited') {
            echo "<form method='post' style='display:inline;'>";
            echo "<input type='hidden' name='applicant_id' value='" . $row['Applicant_ID'] . "'>";
            echo "<button type='submit' name='recruit' class='btn btn-success btn-sm'>Recruit</button>";
            echo "</form>";
        } else {
            echo "Recruited";
        }
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No applicants found.</td></tr>";
}

echo "</tbody></table></div>";

// Job Application Form and Vacancies
echo "<div class='container mt-5'>";
echo "<h2>Job Application</h2>";

// Fetch Job Vacancies
$vacanciesSql = "SELECT * FROM JobVacancies"; // Replace with your actual table name
$vacanciesResult = mysqli_query($conn, $vacanciesSql);

if (mysqli_num_rows($vacanciesResult) > 0) {
    echo "<form method='post' action='apply.php'>"; // Create apply.php to handle application submissions
    echo "<div class='mb-3'>";
    echo "<label for='name' class='form-label'>Name</label>";
    echo "<input type='text' class='form-control' id='name' name='name' required>";
    echo "</div>";
    echo "<div class='mb-3'>";
    echo "<label for='email' class='form-label'>Email</label>";
    echo "<input type='email' class='form-control' id='email' name='email' required>";
    echo "</div>";
    echo "<div class='mb-3'>";
    echo "<label for='phone' class='form-label'>Phone</label>";
    echo "<input type='tel' class='form-control' id='phone' name='phone' required>";
    echo "</div>";
    echo "<div class='mb-3'>";
    echo "<label for='job_applied' class='form-label'>Job Applied</label>";
    echo "<select class='form-select' id='job_applied' name='job_applied' required>";
    while ($vacancyRow = mysqli_fetch_assoc($vacanciesResult)) {
        echo "<option value='" . $vacancyRow['Job_Title'] . "'>" . $vacancyRow['Job_Title'] . "</option>";
    }
    echo "</select>";
    echo "</div>";
    echo "<button type='submit' class='btn btn-primary'>Apply</button>";
    echo "</form>";
} else {
    echo "<p>No job vacancies available.</p>";
}

echo "</div>";

mysqli_close($conn);
?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>