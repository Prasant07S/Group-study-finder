<?php
include 'db.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['email']   = $user['email'];
        $_SESSION['role']    = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/dashboard.php");
        }
        exit();
    } else {
        $error = "❌ Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Group Study Finder</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-body">
    <div class="form-box">
        <h2>🔐 Welcome Back!</h2>
        <p class="subtitle">Login to continue your study journey</p>
        
        <form method="POST" action="">
            <input type="email" name="email" placeholder="📧 Email Address" required>
            <input type="password" name="password" placeholder="🔒 Password" required>
            <button type="submit">🚀 Login</button>
        </form>

        <?php if($error) echo "<p class='error-msg'>$error</p>"; ?>

        <p class="form-footer">Don't have an account? <a href="register.php">Register here</a></p>
        <p class="form-footer"><a href="index.php">← Back to Home</a></p>

        <div class="demo-credentials">
            <p><strong>Demo Admin:</strong> admin@gsf.com / admin123</p>
        </div>
    </div>
</body>
</html>
