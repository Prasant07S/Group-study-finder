<?php
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id"));

// Stats
$joined = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM group_members WHERE user_id = $user_id"));
$created = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM study_groups WHERE created_by = $user_id"));
$sessions = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM study_sessions WHERE created_by = $user_id"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">📚 Group Study Finder</a>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="my-groups.php">My Groups</a></li>
                <li><a href="../logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="form-box" style="max-width: 700px;">
            <div style="text-align: center; margin-bottom: 25px;">
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; font-weight: bold;">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
                <h2><?= htmlspecialchars($user['name']) ?></h2>
                <p style="color: #7f8c8d;"><?= htmlspecialchars($user['email']) ?></p>
            </div>

            <div class="dashboard-stats" style="margin-bottom: 25px;">
                <div class="stat-card">
                    <div class="stat-number"><?= $joined ?></div>
                    <div class="stat-label">Groups Joined</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $created ?></div>
                    <div class="stat-label">Groups Created</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $sessions ?></div>
                    <div class="stat-label">Sessions Scheduled</div>
                </div>
            </div>

            <table class="data-table">
                <tr><th>Full Name</th><td><?= htmlspecialchars($user['name']) ?></td></tr>
                <tr><th>Email</th><td><?= htmlspecialchars($user['email']) ?></td></tr>
                <tr><th>Semester</th><td>Semester <?= $user['semester'] ?></td></tr>
                <tr><th>Phone</th><td><?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></td></tr>
                <tr><th>Bio</th><td><?= htmlspecialchars($user['bio'] ?? 'No bio yet') ?></td></tr>
                <tr><th>Role</th><td><?= ucfirst($user['role']) ?></td></tr>
                <tr><th>Member Since</th><td><?= date('F d, Y', strtotime($user['created_at'])) ?></td></tr>
            </table>

            <div style="text-align: center; margin-top: 25px;">
                <a href="edit-profile.php" class="btn-create" style="padding: 12px 30px;">✏️ Edit Profile</a>
            </div>
        </div>
    </div>
</body>
</html>
