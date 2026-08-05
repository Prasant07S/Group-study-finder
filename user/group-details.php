<?php
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$group_id = intval($_GET['id'] ?? 0);
$success = $error = "";

if (!$group_id) {
    header("Location: find-groups.php");
    exit();
}

// Handle leave group
if (isset($_GET['leave']) && $_GET['leave'] == '1') {
    $check = mysqli_query($conn, "SELECT role FROM group_members WHERE group_id = $group_id AND user_id = $user_id");
    $row = mysqli_fetch_assoc($check);
    if ($row && $row['role'] != 'creator') {
        mysqli_query($conn, "DELETE FROM group_members WHERE group_id = $group_id AND user_id = $user_id");
        // Re-open group if it was full
        mysqli_query($conn, "UPDATE study_groups SET status = 'open' WHERE id = $group_id AND status = 'full'");
        header("Location: my-groups.php");
        exit();
    } else {
        $error = "❌ Creators cannot leave. Delete the group from admin or transfer ownership.";
    }
}

// Handle review submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rating'])) {
    $rating  = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment'] ?? '');
    if ($rating < 1 || $rating > 5) {
        $error = "❌ Rating must be between 1 and 5.";
    } else {
        $is_mem = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM group_members WHERE group_id = $group_id AND user_id = $user_id")) > 0;
        if (!$is_mem) {
            $error = "❌ Only members can review this group.";
        } else {
            $exists = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM reviews WHERE group_id = $group_id AND user_id = $user_id")) > 0;
            if ($exists) {
                $sql = "UPDATE reviews SET rating = $rating, comment = '$comment' WHERE group_id = $group_id AND user_id = $user_id";
            } else {
                $sql = "INSERT INTO reviews (group_id, user_id, rating, comment) VALUES ($group_id, $user_id, $rating, '$comment')";
            }
            if (mysqli_query($conn, $sql)) {
                $success = "✅ Review saved!";
            } else {
                $error = "❌ Error: " . mysqli_error($conn);
            }
        }
    }
}

// Get group info
$group_q = mysqli_query($conn, "SELECT g.*, s.subject_name, u.name as creator_name 
                                  FROM study_groups g 
                                  JOIN subjects s ON g.subject_id = s.id 
                                  JOIN users u ON g.created_by = u.id 
                                  WHERE g.id = $group_id");
$group = mysqli_fetch_assoc($group_q);

if (!$group) {
    echo "<p>Group not found!</p>";
    exit();
}

// Check membership
$m_q = mysqli_query($conn, "SELECT * FROM group_members WHERE group_id = $group_id AND user_id = $user_id");
$membership = mysqli_fetch_assoc($m_q);
$is_member = $membership !== null;
$is_creator = $is_member && ($membership['role'] ?? '') === 'creator';
$member_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM group_members WHERE group_id = $group_id"));
$is_full = $member_count >= $group['max_members'];

// Get members
$members = mysqli_query($conn, "SELECT u.*, gm.role, gm.joined_at 
                                 FROM group_members gm 
                                 JOIN users u ON gm.user_id = u.id 
                                 WHERE gm.group_id = $group_id 
                                 ORDER BY gm.joined_at");

// Get sessions
$sessions = mysqli_query($conn, "SELECT * FROM study_sessions WHERE group_id = $group_id ORDER BY session_date DESC");

// Get reviews
$reviews = mysqli_query($conn, "SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.group_id = $group_id ORDER BY r.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($group['group_name']) ?> - Details</title>
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
        <div class="form-box" style="max-width: 800px; text-align: left;">
            <h2><?= htmlspecialchars($group['group_name']) ?></h2>
            <p class="subtitle">
                <span class="badge badge-<?= htmlspecialchars($group['study_type']) ?>"><?= ucfirst($group['study_type']) ?></span>
                <span class="badge <?= $group['status'] == 'open' ? 'badge-open' : 'badge-full' ?>"><?= ucfirst($group['status']) ?></span>
            </p>

            <?php if ($success) echo "<p class='success-msg'>$success</p>"; ?>
            <?php if ($error) echo "<p class='error-msg'>$error</p>"; ?>

            <p class="group-info">📘 <strong>Subject:</strong> <?= htmlspecialchars($group['subject_name']) ?></p>
            <p class="group-info">📍 <strong>Location:</strong> <?= htmlspecialchars($group['location'] ?? '-') ?></p>
            <p class="group-info">👥 <strong>Members:</strong> <?= $member_count ?> / <?= $group['max_members'] ?></p>
            <p class="group-info">👤 <strong>Created by:</strong> <?= htmlspecialchars($group['creator_name']) ?></p>
            <p class="group-info">📝 <?= htmlspecialchars($group['description'] ?? '') ?></p>

            <div style="margin: 20px 0;">
                <?php if (!$is_member && !$is_full && $group['status'] == 'open'): ?>
                    <a href="join-group.php?id=<?= $group_id ?>" class="btn-join">Join Group</a>
                <?php elseif ($is_member && !$is_creator): ?>
                    <a href="group-details.php?id=<?= $group_id ?>&leave=1" class="btn-delete"
                       onclick="return confirm('Leave this group?')">Leave Group</a>
                <?php endif; ?>
                <?php if ($is_member): ?>
                    <a href="schedule.php?group_id=<?= $group_id ?>" class="btn-create">📅 Schedule Session</a>
                <?php endif; ?>
                <a href="find-groups.php" class="btn-view">← Back</a>
            </div>
        </div>

        <h3 style="color:#2c3e50; margin: 30px 0 10px;">👥 Members</h3>
        <table class="data-table">
            <thead><tr><th>Name</th><th>Role</th><th>Semester</th><th>Joined</th></tr></thead>
            <tbody>
                <?php while ($m = mysqli_fetch_assoc($members)): ?>
                <tr>
                    <td><?= htmlspecialchars($m['name']) ?></td>
                    <td><?= ucfirst($m['role']) ?></td>
                    <td><?= $m['semester'] ? 'Sem ' . $m['semester'] : '-' ?></td>
                    <td><?= date('M d, Y', strtotime($m['joined_at'])) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3 style="color:#2c3e50; margin: 30px 0 10px;">📅 Sessions</h3>
        <?php if (mysqli_num_rows($sessions) == 0): ?>
            <p>No sessions scheduled yet.</p>
        <?php else: ?>
        <table class="data-table">
            <thead><tr><th>Date</th><th>Time</th><th>Topic</th><th>Location</th><th>Duration</th></tr></thead>
            <tbody>
                <?php mysqli_data_seek($sessions, 0); while ($s = mysqli_fetch_assoc($sessions)): ?>
                <tr>
                    <td><?= date('M d, Y', strtotime($s['session_date'])) ?></td>
                    <td><?= htmlspecialchars(substr($s['session_time'], 0, 5)) ?></td>
                    <td><?= htmlspecialchars($s['topic']) ?></td>
                    <td><?= htmlspecialchars($s['location'] ?? '-') ?></td>
                    <td><?= (int)$s['duration_hours'] ?>h</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <h3 style="color:#2c3e50; margin: 30px 0 10px;">⭐ Reviews</h3>
        <?php if (mysqli_num_rows($reviews) == 0): ?>
            <p>No reviews yet.</p>
        <?php else: ?>
        <div class="group-list">
            <?php mysqli_data_seek($reviews, 0); while ($r = mysqli_fetch_assoc($reviews)): ?>
                <div class="group-card">
                    <p><strong><?= htmlspecialchars($r['name']) ?></strong> — <?= str_repeat('⭐', (int)$r['rating']) ?></p>
                    <p><?= htmlspecialchars($r['comment'] ?? '') ?></p>
                    <p style="color:#7f8c8d; font-size:12px;"><?= date('M d, Y', strtotime($r['created_at'])) ?></p>
                </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>

        <?php if ($is_member): ?>
        <div class="form-box" style="max-width: 500px; margin-top: 30px;">
            <h3>Write a Review</h3>
            <form method="POST">
                <select name="rating" required>
                    <option value="">⭐ Select rating</option>
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <option value="<?= $i ?>"><?= $i ?> star<?= $i > 1 ? 's' : '' ?></option>
                    <?php endfor; ?>
                </select>
                <textarea name="comment" placeholder="Your feedback..." rows="3"></textarea>
                <button type="submit">Submit Review</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
