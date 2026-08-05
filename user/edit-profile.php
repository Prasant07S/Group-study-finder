<?php
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = $error = "";

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $semester = intval($_POST['semester']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $bio      = mysqli_real_escape_string($conn, $_POST['bio']);

    $sql = "UPDATE users SET name='$name', semester=$semester, phone='$phone', bio='$bio' WHERE id=$user_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['name'] = $name;
        $success = "✅ Profile updated successfully!";
        $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id"));
    } else {
        $error = "❌ Error updating profile!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">📚 Group Study Finder</a>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="../logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="form-box" style="max-width: 600px;">
            <h2>✏️ Edit Profile</h2>
            
            <form method="POST">
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                
                <select name="semester" required>
                    <?php for($i=1; $i<=8; $i++) echo "<option value='$i' " . ($user['semester'] == $i ? 'selected' : '') . ">Semester $i</option>"; ?>
                </select>
                
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="Phone Number">
                <textarea name="bio" placeholder="Bio" rows="4"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>

                <button type="submit">💾 Save Changes</button>
            </form>

            <?php if($success) echo "<p class='success-msg'>$success</p>"; ?>
            <?php if($error) echo "<p class='error-msg'>$error</p>"; ?>

            <p class="form-footer"><a href="profile.php">← Back to Profile</a></p>
        </div>
    </div>
</body>
</html>
