<?php
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$search = $_GET['search'] ?? '';
$subject_filter = $_GET['subject'] ?? '';
$type_filter = $_GET['type'] ?? '';

$sql = "SELECT g.*, s.subject_name, u.name as creator_name,
        (SELECT COUNT(*) FROM group_members WHERE group_id = g.id) as member_count
        FROM study_groups g
        JOIN subjects s ON g.subject_id = s.id
        JOIN users u ON g.created_by = u.id
        WHERE g.status = 'open'";

if ($search) {
    $search_esc = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (g.group_name LIKE '%$search_esc%' OR g.description LIKE '%$search_esc%')";
}
if ($subject_filter) {
    $sql .= " AND g.subject_id = " . intval($subject_filter);
}
if ($type_filter) {
    $type_esc = mysqli_real_escape_string($conn, $type_filter);
    $sql .= " AND g.study_type = '$type_esc'";
}
$sql .= " ORDER BY g.created_at DESC";

$result = mysqli_query($conn, $sql);
$subjects = mysqli_query($conn, "SELECT * FROM subjects ORDER BY subject_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Study Groups</title>
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
        <h2 style="color: #2c3e50; margin-bottom: 20px;">🔍 Find Your Study Group</h2>

        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="🔎 Search by name or description..." value="<?= htmlspecialchars($search) ?>">
            <select name="subject">
                <option value="">📚 All Subjects</option>
                <?php 
                mysqli_data_seek($subjects, 0);
                while($s = mysqli_fetch_assoc($subjects)) { ?>
                    <option value="<?= $s['id'] ?>" <?= $subject_filter == $s['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['subject_name']) ?>
                    </option>
                <?php } ?>
            </select>
            <select name="type">
                <option value="">🌐 All Types</option>
                <option value="online" <?= $type_filter == 'online' ? 'selected' : '' ?>>Online</option>
                <option value="offline" <?= $type_filter == 'offline' ? 'selected' : '' ?>>Offline</option>
            </select>
            <button type="submit">Search</button>
            <a href="find-groups.php" style="padding: 10px 20px; background: #95a5a6; color: white; border-radius: 6px; text-decoration: none;">Clear</a>
        </form>

        <?php if (mysqli_num_rows($result) == 0): ?>
            <div class="form-box" style="text-align: center;">
                <h3>😔 No groups found!</h3>
                <p>Try different keywords or <a href="create-group.php">create a new group</a>.</p>
            </div>
        <?php else: ?>
            <div class="group-list">
                <?php while($g = mysqli_fetch_assoc($result)) { 
                    // Check if user already joined
                    $check_join = mysqli_query($conn, "SELECT * FROM group_members WHERE group_id = {$g['id']} AND user_id = $user_id");
                    $joined = mysqli_num_rows($check_join) > 0;
                    $is_full = $g['member_count'] >= $g['max_members'];
                ?>
                    <div class="group-card">
                        <h3><?= htmlspecialchars($g['group_name']) ?></h3>
                        <p class="group-info">📘 <strong>Subject:</strong> <?= htmlspecialchars($g['subject_name']) ?></p>
                        <p class="group-info">📍 <strong>Location:</strong> <?= htmlspecialchars($g['location']) ?></p>
                        <p class="group-info">👥 <strong>Members:</strong> <?= $g['member_count'] ?> / <?= $g['max_members'] ?></p>
                        <p class="group-info">👤 <strong>Created by:</strong> <?= htmlspecialchars($g['creator_name']) ?></p>
                        <p class="group-info">📝 <?= htmlspecialchars(substr($g['description'], 0, 100)) ?>...</p>
                        <div style="margin-top: 10px;">
                            <span class="badge badge-<?= $g['study_type'] ?>"><?= ucfirst($g['study_type']) ?></span>
                            <span class="badge <?= $is_full ? 'badge-full' : 'badge-open' ?>"><?= $is_full ? 'Full' : 'Open' ?></span>
                        </div>
                        <div style="margin-top: 15px;">
                            <a href="group-details.php?id=<?= $g['id'] ?>" class="btn-view">View Details</a>
                            <?php if ($joined): ?>
                                <span style="color: #27ae60; font-weight: bold;">✓ Joined</span>
                            <?php elseif ($is_full): ?>
                                <span style="color: #e74c3c; font-weight: bold;">Group Full</span>
                            <?php else: ?>
                                <a href="join-group.php?id=<?= $g['id'] ?>" class="btn-join">Join Group</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
