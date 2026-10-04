<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - File Storage System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
        }
        .card {
            background-color: #1e1e1e;
        }
        .form-control {
            background-color: #2a2a2a;
            color: #fff;
            border-color: #444;
        }
        .form-control:focus {
            background-color: #2a2a2a;
            color: #fff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>

<?php
// your mission in this file:
// 1. Create Post Requests from this form to doRegister.php
// 2. Make sure to include the following fields in the form: username, email, password, confirm_password

$error = "";

if (isset($_GET["error"])) {
    $error = $_GET["error"];
}

?>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container d-flex justify-content-center">
        <div class="card shadow-lg border-secondary border-opacity-25" style="width: 26rem;">
            <div class="card-body p-5">
                <h3 class="card-title fw-semibold text-white mb-1 text-center">Create Account</h3>
                <p class="text-white-50 mb-4 text-center">Join us to store your files securely.</p>

                <?php if ($error != ""): ?>

                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>

                <?php endif; ?>
                
                <form action="doRegister.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label text-white-50 small mb-1">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Choose a username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-white-50 small mb-1">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-white-50 small mb-1">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="form-label text-white-50 small mb-1">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Repeat your password" required>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary fw-medium">Register</button>
                    </div>
                    <div class="text-center mt-4">
                        <span class="text-white-50 small">Already have an account? </span>
                        <a href="login.php" class="text-decoration-none text-primary small">Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
