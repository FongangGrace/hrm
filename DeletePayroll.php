<?php

$conn = mysqli_connect("localhost", "root", "", "hrm");
if(!$conn){
    die("Error: " . mysqli_connect_error());
}

if(isset($_GET['ID'])){
    $id = $_GET['ID'];  

    $check_sql = "SELECT status FROM payroll WHERE id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        if($row['status'] === 'pending') {
            $delete_sql = "DELETE FROM payroll WHERE id = ?";
            $delete_stmt = mysqli_prepare($conn, $delete_sql);
            mysqli_stmt_bind_param($delete_stmt, "i", $id);
            
            if(mysqli_stmt_execute($delete_stmt)) {
                header("Location: Payroll.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error deleting payroll record: " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Cannot delete payroll record. Only pending records can be deleted.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Payroll record not found.</div>";
    }
}

?>