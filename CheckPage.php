<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HR Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="assets/images/logo.jpg" alt="Logo" class="mb-4" style="width: 80px; height: 80px; border-radius: 50%;">
                            <h2 class="fw-bold">Welcome Back</h2>
                            <p class="text-muted">Please select your role to continue</p>
                        </div>

                        <div class="d-grid gap-3">
                            <a href="Login Form.php?role=admin" class="btn btn-primary btn-lg">
                                <i class="bi bi-shield-lock me-2"></i>
                                Admin Login
                            </a>
                            <a href="Login Form.php?role=hr" class="btn btn-info btn-lg text-white">
                                <i class="bi bi-person-badge me-2"></i>
                                HR Personnel Login
                            </a>
                            <a href="Login Form.php?role=employee" class="btn btn-success btn-lg">
                                <i class="bi bi-person me-2"></i>
                                Employee Login
                            </a>
                        </div>

                        <div class="text-center mt-4">
                            <p>Contact HR for account access</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card mt-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Quick Links</h5>
                        <div class="list-group list-group-flush">
                            <a href="Jobvacancy.php" class="list-group-item list-group-item-action">
                                <i class="bi bi-briefcase me-2"></i>
                                View Job Vacancies
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#helpModal">
                                <i class="bi bi-question-circle me-2"></i>
                                Need Help?
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Help & Support</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>Common Issues:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="bi bi-question-circle text-primary me-2"></i>
                            <strong>Forgot Password?</strong>
                            <p class="text-muted small ms-4 mb-0">Contact your HR department or system administrator.</p>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-question-circle text-primary me-2"></i>
                            <strong>Account Locked?</strong>
                            <p class="text-muted small ms-4 mb-0">Multiple failed login attempts will lock your account. Contact support.</p>
                        </li>
                        <li>
                            <i class="bi bi-question-circle text-primary me-2"></i>
                            <strong>Technical Issues?</strong>
                            <p class="text-muted small ms-4 mb-0">Email: support@gccreative.com<br>Phone: +237 123456789</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
