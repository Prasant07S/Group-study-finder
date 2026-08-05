<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "group_study_finder";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Important for correct mysqli_real_escape_string() behavior
mysqli_set_charset($conn, "utf8mb4");

// Start session globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
