<?php

$conn = mysqli_connect("localhost", "root", "", "hrm");
if(!$conn){
    die("Error: " . mysqli_connect_error());
}

if(isset($_GET['ID'])){
    $id = $_GET['ID'];  

    $check_applications_sql = "SELECT COUNT(*) as count FROM job_applications WHERE job_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_applications_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    $row = mysqli_fetch_assoc($result);

    if($row['count'] > 0) {
        echo "<div class='alert alert-warning'>Cannot delete job vacancy. There are existing applications for this position. Please handle the applications first.</div>";
    } else {
        $check_sql = "SELECT * FROM job_vacancies WHERE id = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "i", $id);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);

        if(mysqli_num_rows($result) > 0) {
            $delete_sql = "DELETE FROM job_vacancies WHERE id = ?";
            $delete_stmt = mysqli_prepare($conn, $delete_sql);
            mysqli_stmt_bind_param($delete_stmt, "i", $id);
            
            if(mysqli_stmt_execute($delete_stmt)) {
                header("Location: Jobvacancies.php");  
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error deleting job vacancy: " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Job vacancy not found.</div>";
        }
    }
}

?>