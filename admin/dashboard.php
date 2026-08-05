<?php
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Statistics
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='student'"));
$total_groups = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM study_groups"));
$total_sessions = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM study_sessions"));
$total_subjects = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM subjects"));
$open_groups = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM study_groups WHERE status='open'"));
$closed_groups = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM study_groups WHERE status='closed'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">📚 Group Study Finder (Admin)</a>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage-users.php">Users</a></li>
                <li><a href="manage-groups.php">Groups</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="../logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="welcome-banner">
            <h1>👨‍💼 Admin Dashboard</h1>
            <p>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</p>
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-number"><?= $total_users ?></div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $total_groups ?></div>
                <div class="stat-label">Total Groups</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $open_groups ?></div>
                <div class="stat-label">Open Groups</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $total_sessions ?></div>
                <div class="stat-label">Study Sessions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $total_subjects ?></div>
                <div class="stat-label">Subjects</div>
            </div>
        </div>

        <h2 style="margin: 30px 0 20px; color: #2c3e50;">⚙️ Admin Actions</h2>
        <div class="action-buttons">
            <a href="manage-users.php" class="action-btn">
                <div class="icon">👥</div>
                <h4>Manage Users</h4>
                <p>View, edit, delete users</p>
            </a>
            <a href="manage-groups.php" class="action-btn">
                <div class="icon">📚</div>
                <h4>Manage Groups</h4>
                <p>Monitor study groups</p>
            </a>
            <a href="reports.php" class="action-btn">
                <div class="icon">📊</div>
                <h4>Reports</h4>
                <p>View statistics</p>
            </a>
            <a href="../user/find-groups.php" class="action-btn">
                <div class="icon">🔍</div>
                <h4>Browse as User</h4>
                <p>View the site</p>
            </a>
        </div>
    </div>
</body>
</html>
