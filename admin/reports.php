<?php
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Detailed reports
$reports = [
    'users_by_semester' => mysqli_query($conn, "SELECT semester, COUNT(*) as count FROM users WHERE role='student' GROUP BY semester"),
    'groups_by_subject' => mysqli_query($conn, "SELECT s.subject_name, COUNT(g.id) as count FROM subjects s LEFT JOIN study_groups g ON s.id = g.subject_id GROUP BY s.id"),
    'top_creators' => mysqli_query($conn, "SELECT u.name, COUNT(g.id) as group_count FROM users u LEFT JOIN study_groups g ON u.id = g.created_by WHERE u.role='student' GROUP BY u.id ORDER BY group_count DESC LIMIT 5"),
    'recent_sessions' => mysqli_query($conn, "SELECT ss.*, sg.group_name, u.name FROM study_sessions ss JOIN study_groups sg ON ss.group_id = sg.id JOIN users u ON ss.created_by = u.id ORDER BY ss.created_at DESC LIMIT 10"),
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">📚 Group Study Finder (Admin)</a>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="../logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <h2 style="color: #2c3e50; margin-bottom: 20px;">📊 System Reports</h2>

        <h3 style="margin: 20px 0 10px;">👥 Users by Semester</h3>
        <table class="data-table">
            <thead><tr><th>Semester</th><th>Total Students</th></tr></thead>
            <tbody>
                <?php while($r = mysqli_fetch_assoc($reports['users_by_semester'])) { ?>
                    <tr><td>Semester <?= $r['semester'] ?></td><td><?= $r['count'] ?></td></tr>
                <?php } ?>
            </tbody>
        </table>

        <h3 style="margin: 30px 0 10px;">📚 Groups by Subject</h3>
        <table class="data-table">
            <thead><tr><th>Subject</th><th>Total Groups</th></tr></thead>
            <tbody>
                <?php while($r = mysqli_fetch_assoc($reports['groups_by_subject'])) { ?>
                    <tr><td><?= htmlspecialchars($r['subject_name']) ?></td><td><?= $r['count'] ?></td></tr>
                <?php } ?>
            </tbody>
        </table>

        <h3 style="margin: 30px 0 10px;">🏆 Top Group Creators</h3>
        <table class="data-table">
            <thead><tr><th>Name</th><th>Groups Created</th></tr></thead>
            <tbody>
                <?php while($r = mysqli_fetch_assoc($reports['top_creators'])) { ?>
                    <tr><td><?= htmlspecialchars($r['name']) ?></td><td><?= $r['group_count'] ?></td></tr>
                <?php } ?>
            </tbody>
        </table>

        <h3 style="margin: 30px 0 10px;">📅 Recent Sessions</h3>
        <table class="data-table">
            <thead><tr><th>Group</th><th>Topic</th><th>Date</th><th>Created By</th></tr></thead>
            <tbody>
                <?php while($r = mysqli_fetch_assoc($reports['recent_sessions'])) { ?>
                    <tr>
                        <td><?= htmlspecialchars($r['group_name']) ?></td>
                        <td><?= htmlspecialchars($r['topic']) ?></td>
                        <td><?= date('M d, Y', strtotime($r['session_date'])) ?></td>
                        <td><?= htmlspecialchars($r['name']) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
