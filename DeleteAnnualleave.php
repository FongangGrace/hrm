<?php

$conn = mysqli_connect("localhost", "root", "", "hrm");
if(!$conn){
    die("Error: " . mysqli_connect_error());
}

if(isset($_GET['ID'])){
    $id = $_GET['ID'];  

    $check_sql = "SELECT * FROM leave_requests WHERE id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if(mysqli_num_rows($result) > 0) {
        $delete_sql = "DELETE FROM leave_requests WHERE id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $id);
        
        if(mysqli_stmt_execute($delete_stmt)) {
            header("Location: Annualleave.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error deleting leave request: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Leave request not found.</div>";
    }
}

?>