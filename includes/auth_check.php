<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Login Form.php");
    exit();
}

// Check if user has required role (optional)
function require_role($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: Login Form.php?error=unauthorized");
        exit();
    }
}
?> 