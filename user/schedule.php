<?php
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = $error = "";

// Get user's groups where they are creator or member
$my_groups = mysqli_query($conn, "SELECT g.id, g.group_name FROM study_groups g 
                                   JOIN group_members gm ON g.id = gm.group_id 
                                   WHERE gm.user_id = $user_id");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_id   = intval($_POST['group_id']);
    $session_date = mysqli_real_escape_string($conn, $_POST['session_date'] ?? '');
    $session_time = mysqli_real_escape_string($conn, $_POST['session_time'] ?? '');
    $duration   = intval($_POST['duration_hours']);
    $topic      = mysqli_real_escape_string($conn, $_POST['topic']);
    $location   = mysqli_real_escape_string($conn, $_POST['location']);
    $notes      = mysqli_real_escape_string($conn, $_POST['notes']);

    // Basic format validation
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $session_date) || !preg_match('/^\d{2}:\d{2}/', $session_time)) {
        $error = "❌ Invalid date or time format!";
    }

    // Verify membership
    if ($error) {
        // skip further processing if format invalid
    } else {
    $verify = mysqli_query($conn, "SELECT * FROM group_members WHERE group_id = $group_id AND user_id = $user_id");
    if (mysqli_num_rows($verify) == 0) {
        $error = "❌ You are not a member of this group!";
    } elseif (strtotime($session_date) < strtotime(date('Y-m-d'))) {
        $error = "❌ Cannot schedule session in the past!";
    } else {
        $sql = "INSERT INTO study_sessions (group_id, session_date, session_time, duration_hours, topic, location, notes, created_by) 
                VALUES ($group_id, '$session_date', '$session_time', $duration, '$topic', '$location', '$notes', $user_id)";
        if (mysqli_query($conn, $sql)) {
            $success = "✅ Study session scheduled successfully!";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
    } // end format-ok check
}

$preselected_group = intval($_GET['group_id'] ?? 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule Session</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">📚 Group Study Finder</a>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="my-groups.php">My Groups</a></li>
                <li><a href="my-sessions.php">My Sessions</a></li>
                <li><a href="../logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="form-box" style="max-width: 600px;">
            <h2>📅 Schedule a Study Session</h2>
            <p class="subtitle">Plan your next productive study session</p>

            <?php if (mysqli_num_rows($my_groups) == 0): ?>
                <p class="error-msg">You need to join a group first! <a href="find-groups.php">Find Groups</a></p>
            <?php else: ?>
                <form method="POST">
                    <select name="group_id" required>
                        <option value="">📚 Select Group</option>
                        <?php while($g = mysqli_fetch_assoc($my_groups)) { ?>
                            <option value="<?= $g['id'] ?>" <?= $preselected_group == $g['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g['group_name']) ?>
                            </option>
                        <?php } ?>
                    </select>

                    <input type="date" name="session_date" min="<?= date('Y-m-d') ?>" required>
                    <input type="time" name="session_time" required>
                    <input type="number" name="duration_hours" placeholder="⏱ Duration (hours)" min="1" max="8" value="2" required>
                    <input type="text" name="topic" placeholder="📖 Topic (e.g., Binary Trees, SQL Joins)" required>
                    <input type="text" name="location" placeholder="📍 Location (e.g., Library, Zoom link)" required>
                    <textarea name="notes" placeholder="📝 Additional notes..." rows="3"></textarea>

                    <button type="submit">🚀 Schedule Session</button>
                </form>
            <?php endif; ?>

            <?php if($success) echo "<p class='success-msg'>$success</p>"; ?>
            <?php if($error) echo "<p class='error-msg'>$error</p>"; ?>

            <p class="form-footer"><a href="dashboard.php">← Back to Dashboard</a></p>
        </div>
    </div>
</body>
</html>
