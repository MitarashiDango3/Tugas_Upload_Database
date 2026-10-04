<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Storage System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
        }
        .card {
            background-color: #1e1e1e;
        }
    </style>
</head>
<?php
// your mission in this file:
// 1. Check session for user authentication if not authenticated redirect to login.php
// 2. If authenticated display the following options: Upload File, View Uploads 

session_start();

// Check if user is authenticated
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Logout
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

?>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container d-flex justify-content-center">
        <div class="card shadow-lg border-secondary border-opacity-25" style="width: 24rem;">
            <div class="card-body text-center p-5">
                <h3 class="card-title fw-semibold text-white mb-3">File Management</h3>
                <p class="text-white-50 mb-4">Select an action below to manage your files.</p>
                
                <div class="d-grid gap-3">
                    <a href="upload.php" class="btn btn-primary btn-lg fw-medium">Upload File</a>
                    <a href="list.php" class="btn btn-outline-secondary text-white btn-lg fw-medium">View Uploads</a>
                    <a href="index.php?logout=true" class="btn btn-outline-danger btn-lg fw-medium">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
