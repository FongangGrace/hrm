<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header('Location: CheckPage.php');
        exit();
    }
}

function checkRole($allowed_roles) {
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        header('Location: CheckPage.php');
        exit();
    }
}

function getUsername() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : '';
}

function getUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
}

function getRole() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : '';
}

function logout() {
    session_unset();
    session_destroy();
    header('Location: CheckPage.php');
    exit();
}
?>
