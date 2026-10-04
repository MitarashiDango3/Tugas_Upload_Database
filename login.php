<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - File Storage System</title>
    <!-- Bootstrap 5 CSS -->
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
// from this file, your mission is:
// 1. Create Post Requests from this form to doLogin.php
// 2. Make sure to include the following fields in the form: username, password
// 3. Include a link to register.php for users who don't have an account

$error = "";
$success = "";

if (isset($_GET["error"])) {
    $error = $_GET["error"];
}

if (isset($_GET["success"])) {
    $success = $_GET["success"];
}

?>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container d-flex justify-content-center">
        <div class="card shadow-lg border-secondary border-opacity-25" style="width: 24rem;">
            <div class="card-body p-5">
                <h3 class="card-title fw-semibold text-white mb-1 text-center">Welcome Back</h3>
                <p class="text-white-50 mb-4 text-center">Please login to your account.</p>

                <!--
                Kalau $error tidak kosong, berarti sebelumnya
                doLogin.php mengirimkan pesan error.

                Contoh:

                Wrong username or password combination

                htmlspecialchars() digunakan supaya teks dari
                parameter URL ditampilkan sebagai teks biasa
                dan tidak diperlakukan sebagai HTML.
                -->
                
                <?php if ($error != ""): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success != ""): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <form action="doLogin.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label text-white-50 small mb-1">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label text-white-50 small mb-1">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary fw-medium">Login</button>
                    </div>
                    <div class="text-center mt-4">
                        <span class="text-white-50 small">Don't have an account? </span>
                        <a href="register.php" class="text-decoration-none text-primary small">Register here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
