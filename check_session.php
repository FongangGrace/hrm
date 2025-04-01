<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Login Form.php");
    exit();
}

if (!isset($_SESSION['role'])) {
    header("Location: Login Form.php");
    exit();
}

// Function to check if user has required role
function require_role($required_role) {
    if ($_SESSION['role'] !== $required_role) {
        header("Location: Login.php");
        exit();
    }
}
?> 