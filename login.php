<?php
session_start();
include 'db_connection.php';  // Include your database connection here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mengecek apakah user ada di database
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['user'] = $email;
        // Redirect to index.html dan panggil JavaScript untuk menyembunyikan form
        echo "<script>window.location.href='index.html';</script>";
    } else {
        echo "Invalid credentials!";
    }
}
?>
