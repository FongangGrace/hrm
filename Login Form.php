<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", 'root', '', 'hrm');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
    $found = false;

    // Try admin login
    $query = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = 'admin';
        header("Location: AdminDashboard.php");
        exit();
    }

    // Try HR login
    $query = "SELECT * FROM hr_personnel WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = 'hr';
        header("Location: HumanResourceDashboard.php");
        exit();
    }

    // Try employee login (with hashed password)
    $query = "SELECT * FROM employees WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'employee';
            
            // Debug information
            error_log("Employee login successful. User ID: " . $user['id']);
            error_log("Redirecting to EmployeeDashboard.php");
            
            header("Location: EmployeeDashboard.php");
            exit();
        } else {
            error_log("Password verification failed for email: " . $email);
        }
    } else {
        error_log("No employee found with email: " . $email);
    }

    $error = "Invalid email or password";
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .login-form input {
            text-align: center;
            background-color: aliceblue;
            border: none;
            border-bottom: 2px solid darkblue;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-person-circle" style="font-size: 80px; color: darkblue;"></i>
                        <h2 class="card-title mb-4">Sign In</h2>
                        
                        <?php if(isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                        <?php endif; ?>

                        <form method="post" class="login-form">
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" required 
                                       placeholder="Email">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" required 
                                       placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                            
                            <p class="mt-3">
                                Contact HR for account access
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>