<?php
// ur mission is after create connection to database, you need to implement the following functionalities in this file:
// 1. Create a POST request to handle file upload form submission & validate the user session to ensure the user is authenticated before allowing file upload
// 2. Validate the uploaded file to ensure it meets the required criteria (e.g., file type, size limit)
// try to limit the file size to 5MB and only allow certain file types (e.g., PDF, DOCX, JPG, PNG)
// 3. Move the uploaded file to a designated directory on the server (e.g., "uploads/")
// 4. Store the file information (e.g., file name, size, upload date, user ID) in the database for future reference

session_start();

// NOTE:
// User harus sudah login untuk melakukan upload.
// Kita menggunakan user_id dari session sebagai tanda bahwa user sudah berhasil login.

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$connect = mysqli_connect(
    "localhost",
    "root",
    "",
    "database_for_upload"
);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_FILES["fileUpload"])) {
        header("Location: upload.php?error=Please select a file");
        exit();
    }

    // Karena file dikirim menggunakan multipart/form-data, data file berada di dalam $_FILES.
    // Nama input pada upload.php adalah: name="fileUpload"
    // sehingga kita mengambil: $_FILES["fileUpload"]

    $file = $_FILES["fileUpload"];

    if ($file["error"] != 0) {
        header("Location: upload.php?error=File upload failed");
        exit();
    }

    $original_name = $file["name"];
    $file_size = $file["size"];

    // Maximum 5MB
    // Requirement tugas membatasi ukuran file maksimal 5 MB.
    // PHP menghitung ukuran file dalam byte.
    // 1 KB = 1024 byte
    // 1 MB = 1024 KB
    // Jadi: 5 MB = 5 * 1024 * 1024

    if ($file_size > 5 * 1024 * 1024) {
        header("Location: upload.php?error=File size cannot be more than 5MB");
        exit();
    }

    // pathinfo() digunakan untuk mengambil bagian tertentu dari nama file.
    // PATHINFO_EXTENSION berarti kita hanya mengambil extension file.
    // Contoh:
    // tugas.pdf
    //   ↓
    // pdf

    // strtolower() digunakan supaya extension selalu lowercase.
    // Jadi:PDF, Pdf, pDf
    // semuanya akan menjadi: pdf

    $extension = strtolower(
        pathinfo($original_name, PATHINFO_EXTENSION)
    );

    // Hanya extension yang ada di array ini yang boleh di-upload.
    $allowed_extensions = array(
        "pdf",
        "docx",
        "jpg",
        "png"
    );

    // in_array() mengecek apakah extension yang didapat dari file terdapat di dalam array $allowed_types.
    // Contoh:
    // extension = pdf
    // pdf ada di allowed_types → boleh.
    // extension = exe
    // exe tidak ada di allowed_types → ditolak.

    if (!in_array($extension, $allowed_extensions)) {
        header("Location: upload.php?error=File type is not allowed");
        exit();
    }

    // Kita menggunakan uniqid() untuk membuat nama file yang berbeda dari nama asli
    // Misalnya user meng-upload: tugas.pdf
    // File bisa disimpan sebagai:68f123abc4567.pdf
    // Extension tetap kita tambahkan di belakang agar file masih mempunyai extension yang sesuai

    $stored_name = uniqid() . "." . $extension;

    // File akan disimpan di folder: uploads/
    // Folder ini harus berada satu level dengan file PHP seperti index.php, upload.php, dan lainnya

    $upload_path = "uploads/" . $stored_name;

    // Saat file dikirim ke server, PHP awalnya menyimpannya di temporary location.
    // $file["tmp_name"] berisi lokasi sementara tersebut.
    // move_uploaded_file() kemudian memindahkannya ke: uploads/nama-file-baru
    if (!move_uploaded_file($file["tmp_name"], $upload_path)) {
        header("Location: upload.php?error=Failed to upload file");
        exit();
    }

    // user_id diambil dari session.
    // Ini penting karena kita tidak meminta user memasukkan user_id melalui form.
    // Kalau Dominick yang sedang login mempunyai:
    // $_SESSION["user_id"] = 5
    // maka file yang dia upload otomatis akan mempunyai user_id = 5

    $user_id = $_SESSION["user_id"];

    // File fisiknya sudah ada di folder uploads/.
    // Sekarang kita menyimpan metadata/informasinya ke tabel files.

    $query = "INSERT INTO files
              (user_id, original_name, stored_name, file_size, file_type)
              VALUES (?, ?, ?, ?, ?)";

    $statement = mysqli_prepare($connect, $query);

    // Tipe datanya:
    // user_id       = integer → i
    // original_name = string  → s
    // stored_name   = string  → s
    // file_size     = integer → i
    // file_type     = string  → s
    // Jadi: "issis"

    mysqli_stmt_bind_param(
        $statement,
        "issis",
        $user_id,
        $original_name,
        $stored_name,
        $file_size,
        $extension
    );

    // Setelah file berhasil dipindahkan dan informasi
    // berhasil dimasukkan ke database, user diarahkan
    // ke list.php untuk melihat file yang dimiliki.

    if (mysqli_stmt_execute($statement)) {
        header("Location: list.php");
        exit();
    } else {
        header("Location: upload.php?error=Failed to save file information");
        exit();
    }
}

?>