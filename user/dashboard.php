<?php
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user info
$user_q = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_q);

// Stats
$my_groups = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM group_members WHERE user_id = $user_id"));
$created_groups = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM study_groups WHERE created_by = $user_id"));
$upcoming_sessions = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM study_sessions WHERE created_by = $user_id AND session_date >= CURDATE()"));
$total_groups = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM study_groups WHERE status = 'open'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Group Study Finder</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">📚 Group Study Finder</a>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="find-groups.php">Find Groups</a></li>
                <li><a href="my-groups.php">My Groups</a></li>
                <li><a href="my-sessions.php">Sessions</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="../logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="welcome-banner">
            <h1>👋 Welcome back, <?= htmlspecialchars($user['name']) ?>!</h1>
            <p>Semester <?= $user['semester'] ?> | <?= htmlspecialchars($user['email']) ?></p>
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-number"><?= $my_groups ?></div>
                <div class="stat-label">Groups Joined</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $created_groups ?></div>
                <div class="stat-label">Groups Created</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $upcoming_sessions ?></div>
                <div class="stat-label">Upcoming Sessions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $total_groups ?></div>
                <div class="stat-label">Available Groups</div>
            </div>
        </div>

        <h2 style="margin: 30px 0 20px; color: #2c3e50;">Quick Actions</h2>
        <div class="action-buttons">
            <a href="create-group.php" class="action-btn">
                <div class="icon">➕</div>
                <h4>Create Study Group</h4>
                <p>Start a new group</p>
            </a>
            <a href="find-groups.php" class="action-btn">
                <div class="icon">🔍</div>
                <h4>Find Groups</h4>
                <p>Browse available groups</p>
            </a>
            <a href="schedule.php" class="action-btn">
                <div class="icon">📅</div>
                <h4>Schedule Session</h4>
                <p>Plan a study session</p>
            </a>
            <a href="profile.php" class="action-btn">
                <div class="icon">👤</div>
                <h4>My Profile</h4>
                <p>View & edit profile</p>
            </a>
        </div>
    </div>
</body>
</html>
