<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: LoginHuman.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "hrm");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['application_id']) && isset($_POST['note'])) {
    $application_id = mysqli_real_escape_string($conn, $_POST['application_id']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    $created_at = date('Y-m-d H:i:s');

    // Insert note into database
    $query = "INSERT INTO application_notes (application_id, note, created_at) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iss", $application_id, $note, $created_at);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = "Note added successfully!";
    } else {
        $_SESSION['error_message'] = "Error adding note: " . mysqli_error($conn);
    }

    // Redirect back to the application view
    header("Location: ViewApplication.php?id=" . $application_id);
    exit();
} else {
    header("Location: Recruitment.php");
    exit();
}
?> 