<?php
// ur mission is after create connection to database, you need to implement the following functionalities in this file:
// 1. Create a POST request to handle registration form submission
// 2. Validate the input fields: username, email, password, confirm_password make sure confirm_password matches password
// 3. Check if the username or email already exists in the database
// 4. If validation passes, hash the password and insert the new user into the database
// 5. If registration is successful, redirect to login.php with a success message

//NOTE:
// mysqli_connect() digunakan untuk menghubungkan PHP dengan
// database MySQL/MariaDB.

// Parameter yang digunakan:
// 1. localhost             = database berjalan di komputer sendiri
// 2. root                  = username database
// 3. ""                    = password database kosong
// 4. database_for_upload   = nama database yang digunakan

$connect = mysqli_connect(
    "localhost",
    "root",
    "",
    "database_for_upload"
);

// Kalau koneksi database gagal, program tidak bisa lanjut 
// karena semua proses registrasi membutuhkan database.

// !$connect artinya koneksi tidak berhasil.

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

//Cek Request Method
// Data seharusnya dikirim menggunakan POST dari register.php.

// Kita cek REQUEST_METHOD supaya proses registrasi hanya 
// dijalankan ketika halaman ini menerima request POST.

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // $_POST digunakan untuk mengambil data yang dikirim
    // melalui form dengan method="POST".
    // Nama yang digunakan di sini harus sama dengan atribut
    // name="" pada input di register.php.
    // Contoh: <input name="username">
    // akan diambil menggunakan: $_POST["username"]

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check username
    // Username tidak boleh kosong.
    // trim() yang kita gunakan sebelumnya menghilangkan
    // spasi kosong di bagian awal dan akhir.
    // Jadi input seperti: "     "
    // akan dianggap sebagai string kosong.

    if ($username == "") {
        header("Location: register.php?error=Username cannot be empty");
        exit();
    }

    // Check email
    // filter_var() dengan FILTER_VALIDATE_EMAIL digunakan
    // untuk mengecek apakah format email valid.
    // Contoh yang valid:
    // user@gmail.com
    // Contoh yang tidak valid:
    // usergmail.com
    // @gmail.com

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=Invalid email format");
        exit();
    }

    // Check username udah ada atau belum
    // Kita mencari username tersebut di database.
    // Tanda ? merupakan placeholder untuk nilai username.
    // Nilai sebenarnya dimasukkan menggunakan

    $query = "SELECT id FROM users WHERE username = ?";
    $statement = mysqli_prepare($connect, $query);

    // mysqli_stmt_bind_param().
    // Cara ini membuat input user tidak langsung ditempel
    // ke dalam SQL query.
    // "s" berarti nilai yang kita masukkan berupa string.

    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);

    $result = mysqli_stmt_get_result($statement);

    // Kalau jumlah baris lebih dari 0, berarti username
    // sudah digunakan oleh user lain.

    if (mysqli_num_rows($result) > 0) {
        header("Location: register.php?error=Username already exists");
        exit();
    }

    // Check email sudah ada yang terdaftar atau belum
    // Kita melakukan pengecekan yang sama untuk email.
    // Database juga mempunyai UNIQUE KEY untuk email,
    // sehingga email yang sama memang tidak boleh digunakan
    // oleh dua akun.

    $query = "SELECT id FROM users WHERE email = ?";
    $statement = mysqli_prepare($connect, $query);

    mysqli_stmt_bind_param($statement, "s", $email);
    mysqli_stmt_execute($statement);

    $result = mysqli_stmt_get_result($statement);

    if (mysqli_num_rows($result) > 0) {
        header("Location: register.php?error=Email already exists");
        exit();
    }

    // Check password length
    // strlen() digunakan untuk menghitung jumlah karakter.
    // Requirement tugas meminta password minimal 8 karakter.

    if (strlen($password) < 8) {
        header("Location: register.php?error=Password must be at least 8 characters");
        exit();
    }

    // Check password confirmation
    // Password yang dimasukkan pada dua input harus sama.
    // Kita membandingkan password asli yang dimasukkan user
    // sebelum password tersebut di-hash.

    if ($password != $confirm_password) {
        header("Location: register.php?error=Passwords do not match");
        exit();
    }

    // Hash password
    // Password jangan langsung disimpan ke database sebagai plaintext.
    // Misalnya user memasukkan: Password123
    // Kita tidak memasukkan "Password123" langsung ke kolom password.
    // password_hash() mengubah password menjadi hash.
    // Nantinya saat login, password_verify() digunakan untuk
    // mengecek password yang diberikan user terhadap hash yang tersimpan di database.

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    // Setelah semua validasi berhasil, kita memasukkan data
    // username, email, dan password hash ke tabel users.
    // created_at tidak perlu kita masukkan karena database
    // sudah mempunyai DEFAULT CURRENT_TIMESTAMP.

    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $statement = mysqli_prepare($connect, $query);

    // Ada tiga nilai string:
    // username
    // email
    // hashed password
    // Karena semuanya string, parameternya adalah "sss".

    mysqli_stmt_bind_param(
        $statement,
        "sss",
        $username,
        $email,
        $hashed_password
    );

    // Kalo semua syarat terpenuhi dia ter regist dan bisa login, jadi pindah ke page login.php
    // Kalo syarat ga terpenuhi ya gagal regist, balikin ke page register.php ulang

    if (mysqli_stmt_execute($statement)) {

        header("Location: login.php?success=Registration successful");
        exit();

    } else {

        header("Location: register.php?error=Registration failed");
        exit();

    }
}

?>