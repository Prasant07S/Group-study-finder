<?php
include 'db.php';

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $semester = intval($_POST['semester']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $bio      = mysqli_real_escape_string($conn, $_POST['bio']);

    // Check if email exists
    $check = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        $error = "❌ Email already registered!";
    } else {
        $sql = "INSERT INTO users (name, email, password, semester, phone, bio) 
                VALUES ('$name', '$email', '$password', $semester, '$phone', '$bio')";
        if (mysqli_query($conn, $sql)) {
            $success = "✅ Registration successful! You can now login.";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Group Study Finder</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-body">
    <div class="form-box">
        <h2>📚 Create Your Account</h2>
        <p class="subtitle">Join the study community today!</p>
        
        <form method="POST" action="">
            <input type="text" name="name" placeholder="👤 Full Name" required>
            <input type="email" name="email" placeholder="📧 Email Address" required>
            <input type="password" name="password" placeholder="🔒 Password (min 6 chars)" required minlength="6">
            
            <select name="semester" required>
                <option value="">📘 Select Semester</option>
                <?php for($i=1; $i<=8; $i++) echo "<option value='$i'>Semester $i</option>"; ?>
            </select>
            
            <input type="text" name="phone" placeholder="📱 Phone Number">
            <textarea name="bio" placeholder="✍️ Short Bio (your interests, goals...)" rows="3"></textarea>
            
            <button type="submit">🚀 Register</button>
        </form>

        <?php if($success) echo "<p class='success-msg'>$success</p>"; ?>
        <?php if($error) echo "<p class='error-msg'>$error</p>"; ?>

        <p class="form-footer">Already have an account? <a href="login.php">Login here</a></p>
        <p class="form-footer"><a href="index.php">← Back to Home</a></p>
    </div>
</body>
</html>
