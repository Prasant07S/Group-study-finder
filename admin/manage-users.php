<?php
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Delete user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Only delete students; cascade memberships/sessions/reviews via FK or manual cleanup
    mysqli_query($conn, "DELETE FROM reviews WHERE user_id = $id");
    mysqli_query($conn, "DELETE FROM study_sessions WHERE created_by = $id");
    mysqli_query($conn, "DELETE FROM group_members WHERE user_id = $id");
    mysqli_query($conn, "DELETE FROM users WHERE id = $id AND role = 'student'");
    header("Location: manage-users.php");
    exit();
}

$users = mysqli_query($conn, "SELECT * FROM users WHERE role = 'student' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
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
        <h2 style="color: #2c3e50; margin-bottom: 20px;">👥 Manage Users</h2>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Semester</th>
                    <th>Phone</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($u = mysqli_fetch_assoc($users)) { ?>
                    <tr>
                        <td>#<?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['name']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>Sem <?= $u['semester'] ?></td>
                        <td><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
                        <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                        <td>
                            <a href="manage-users.php?delete=<?= $u['id'] ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
