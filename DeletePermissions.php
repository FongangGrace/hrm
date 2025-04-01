<?php
session_start();
require_once 'includes/auth_check.php';
require_role('hr');

$conn = mysqli_connect("localhost", "root", "", "hrm");
if(!$conn){
    die("error" .$conn.mysqli_connect_error());
}

    $sql = "DELETE FROM ViewPermission";
    $result = mysqli_query($conn, $sql);
    header("Location:ViewPermission.php");
?>