<?php
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Delete group
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Clean related rows first (safe even with FK CASCADE)
    mysqli_query($conn, "DELETE FROM reviews WHERE group_id = $id");
    mysqli_query($conn, "DELETE FROM study_sessions WHERE group_id = $id");
    mysqli_query($conn, "DELETE FROM group_members WHERE group_id = $id");
    mysqli_query($conn, "DELETE FROM study_groups WHERE id = $id");
    header("Location: manage-groups.php");
    exit();
}

// Toggle status
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $g = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM study_groups WHERE id = $id"));
    $new = ($g['status'] == 'open') ? 'closed' : 'open';
    mysqli_query($conn, "UPDATE study_groups SET status = '$new' WHERE id = $id");
    header("Location: manage-groups.php");
    exit();
}

$groups = mysqli_query($conn, "SELECT g.*, s.subject_name, u.name as creator_name,
                                (SELECT COUNT(*) FROM group_members WHERE group_id = g.id) as member_count
                                FROM study_groups g
                                JOIN subjects s ON g.subject_id = s.id
                                JOIN users u ON g.created_by = u.id
                                ORDER BY g.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Groups</title>
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
                <li><a href="../logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <h2 style="color: #2c3e50; margin-bottom: 20px;">📚 Manage Study Groups</h2>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Group Name</th>
                    <th>Subject</th>
                    <th>Creator</th>
                    <th>Members</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($g = mysqli_fetch_assoc($groups)) { ?>
                    <tr>
                        <td>#<?= $g['id'] ?></td>
                        <td><?= htmlspecialchars($g['group_name']) ?></td>
                        <td><?= htmlspecialchars($g['subject_name']) ?></td>
                        <td><?= htmlspecialchars($g['creator_name']) ?></td>
                        <td><?= $g['member_count'] ?>/<?= $g['max_members'] ?></td>
                        <td><?= ucfirst($g['study_type']) ?></td>
                        <td>
                            <span class="badge <?= $g['status'] == 'open' ? 'badge-open' : 'badge-full' ?>">
                                <?= ucfirst($g['status']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="manage-groups.php?toggle=<?= $g['id'] ?>" class="btn-edit">
                                <?= $g['status'] == 'open' ? 'Close' : 'Open' ?>
                            </a>
                            <a href="manage-groups.php?delete=<?= $g['id'] ?>" 
                               class="btn-delete"
                               onclick="return confirm('Delete this group?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
