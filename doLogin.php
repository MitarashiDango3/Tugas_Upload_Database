<?php
// ur mission is after create connection to database, you need to implement the following functionalities in this file:
// 1. Create a POST request to handle login form submission
// 2. Validate the username and password against the database
// 3. If the credentials are valid, start a session and redirect to index.php
// 4. If the credentials are invalid, redirect back to login.php with an error message
// 5. Dont forget to include session_start() at the beginning of the file to manage user sessions

session_start();

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

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Kita mencari user berdasarkan username.
    // Kita tidak mencari berdasarkan password karena password yang tersimpan di database sudah berupa hash.

    $query = "SELECT * FROM users WHERE username = ?";
    $statement = mysqli_prepare($connect, $query);

    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);

    $result = mysqli_stmt_get_result($statement);

    // Kalau ditemukan tepat satu user, kita ambil datanya.

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        // $password adalah password yang baru saja diketik user di login.php.
        // $user["password"] adalah password HASH yang tersimpan di database.
        // password_verify() akan mengecek apakah password yang diberikan cocok dengan hash tersebut.
        // Kita tidak membandingkan:
        // $password == $user["password"] karena database tidak menyimpan password plaintext.

        if (password_verify($password, $user["password"])) {

            // Setelah login berhasil, kita membuat session ID baru.
            // Tujuannya agar session ID sebelum dan sesudah autentikasi tidak tetap sama.

            session_regenerate_id(true);

            // Kita tidak perlu memasukkan seluruh isi database ke session.
            // Cukup simpan informasi yang kita butuhkan untuk mengetahui user yang sedang login.
            // user_id sangat penting karena nanti digunakan oleh doUpload.php dan list.php.

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            header("Location: index.php");
            exit();

        } else {

            header("Location: login.php?error=Wrong username or password combination");
            exit();

        }

    } else {

        header("Location: login.php?error=Wrong username or password combination");
        exit();

    }
}

?>