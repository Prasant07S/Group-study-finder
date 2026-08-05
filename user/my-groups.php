<?php
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user's groups
$groups = mysqli_query($conn, "SELECT g.*, s.subject_name, u.name as creator_name, gm.role,
                                (SELECT COUNT(*) FROM group_members WHERE group_id = g.id) as member_count
                                FROM study_groups g
                                JOIN group_members gm ON g.id = gm.group_id
                                JOIN subjects s ON g.subject_id = s.id
                                JOIN users u ON g.created_by = u.id
                                WHERE gm.user_id = $user_id
                                ORDER BY gm.joined_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Groups</title>
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
                <li><a href="../logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="color: #2c3e50;">📚 My Study Groups</h2>
            <a href="create-group.php" class="btn-create">➕ Create New Group</a>
        </div>

        <?php if (mysqli_num_rows($groups) == 0): ?>
            <div class="form-box" style="text-align: center;">
                <h3>😔 You haven't joined any groups yet!</h3>
                <p style="margin: 15px 0;"><a href="find-groups.php" class="btn-view">Find Groups to Join</a></p>
                <p>or <a href="create-group.php" class="btn-create">Create Your Own Group</a></p>
            </div>
        <?php else: ?>
            <div class="group-list">
                <?php while($g = mysqli_fetch_assoc($groups)) { ?>
                    <div class="group-card">
                        <h3><?= htmlspecialchars($g['group_name']) ?></h3>
                        <?php if ($g['role'] == 'creator'): ?>
                            <span class="badge" style="background: #f39c12; color: white;">👑 Creator</span>
                        <?php else: ?>
                            <span class="badge" style="background: #3498db; color: white;">Member</span>
                        <?php endif; ?>
                        <p class="group-info">📘 <strong>Subject:</strong> <?= htmlspecialchars($g['subject_name']) ?></p>
                        <p class="group-info">📍 <strong>Location:</strong> <?= htmlspecialchars($g['location']) ?></p>
                        <p class="group-info">👥 <strong>Members:</strong> <?= $g['member_count'] ?> / <?= $g['max_members'] ?></p>
                        <p class="group-info">👤 <strong>Created by:</strong> <?= htmlspecialchars($g['creator_name']) ?></p>
                        <div style="margin-top: 15px;">
                            <a href="group-details.php?id=<?= $g['id'] ?>" class="btn-view">View Details</a>
                            <?php if ($g['role'] == 'creator'): ?>
                                <a href="schedule.php?group_id=<?= $g['id'] ?>" class="btn-create">📅 Schedule</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
