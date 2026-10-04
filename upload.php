<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File - File Storage System</title>
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
// your mission in this file:
// 1. Check session for user authentication if not authenticated redirect to login.php
// 2. If authenticated display the upload form with a file input and a submit button
// 3. The form should submit to doUpload.php using POST method and enctype="multipart/form-data"

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$error = "";

if (isset($_GET["error"])) {
    $error = $_GET["error"];
}

?>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container d-flex justify-content-center">
        <div class="card shadow-lg border-secondary border-opacity-25" style="width: 30rem;">
            <div class="card-body p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="card-title fw-semibold text-white mb-0">Upload File</h3>
                    <a href="index.php" class="btn btn-sm btn-outline-secondary text-white">Back to Home</a>
                </div>
                <p class="text-white-50 mb-4">Select a file to securely store it in your vault.</p>

                <?php if ($error != ""): ?>

                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>

                <?php endif; ?>
                
                <form action="doUpload.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="fileUpload" class="form-label text-white-50 small mb-1">Choose File</label>
                        <input class="form-control" type="file" id="fileUpload" name="fileUpload" required>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary fw-medium">Upload Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
