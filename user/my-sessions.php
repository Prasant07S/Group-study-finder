<?php
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Upcoming sessions
$upcoming = mysqli_query($conn, "SELECT ss.*, sg.group_name FROM study_sessions ss 
                                  JOIN study_groups sg ON ss.group_id = sg.id
                                  JOIN group_members gm ON sg.id = gm.group_id
                                  WHERE gm.user_id = $user_id 
                                  AND ss.session_date >= CURDATE()
                                  ORDER BY ss.session_date ASC, ss.session_time ASC");

// Past sessions
$past = mysqli_query($conn, "SELECT ss.*, sg.group_name FROM study_sessions ss 
                              JOIN study_groups sg ON ss.group_id = sg.id
                              JOIN group_members gm ON sg.id = gm.group_id
                              WHERE gm.user_id = $user_id 
                              AND ss.session_date < CURDATE()
                              ORDER BY ss.session_date DESC, ss.session_time DESC
                              LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Sessions</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">📚 Group Study Finder</a>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="my-groups.php">My Groups</a></li>
                <li><a href="my-sessions.php">Sessions</a></li>
                <li><a href="../logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="color: #2c3e50;">📅 My Study Sessions</h2>
            <a href="schedule.php" class="btn-create">➕ Schedule New</a>
        </div>

        <h3 style="color: #27ae60; margin: 20px 0 10px;">🟢 Upcoming Sessions</h3>
        <?php if (mysqli_num_rows($upcoming) == 0): ?>
            <p style="color: #7f8c8d; padding: 20px; background: white; border-radius: 8px;">No upcoming sessions. <a href="schedule.php">Schedule one now!</a></p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Duration</th>
                        <th>Topic</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($s = mysqli_fetch_assoc($upcoming)) { ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($s['group_name']) ?></strong></td>
                            <td><?= date('M d, Y', strtotime($s['session_date'])) ?></td>
                            <td><?= date('h:i A', strtotime($s['session_time'])) ?></td>
                            <td><?= $s['duration_hours'] ?> hrs</td>
                            <td><?= htmlspecialchars($s['topic']) ?></td>
                            <td><?= htmlspecialchars($s['location']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h3 style="color: #95a5a6; margin: 30px 0 10px;">⚪ Past Sessions</h3>
        <?php if (mysqli_num_rows($past) == 0): ?>
            <p style="color: #7f8c8d; padding: 20px; background: white; border-radius: 8px;">No past sessions yet.</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Date</th>
                        <th>Topic</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($s = mysqli_fetch_assoc($past)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($s['group_name']) ?></td>
                            <td><?= date('M d, Y', strtotime($s['session_date'])) ?></td>
                            <td><?= htmlspecialchars($s['topic']) ?></td>
                            <td><?= htmlspecialchars($s['location']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
