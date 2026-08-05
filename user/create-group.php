<?php
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name  = mysqli_real_escape_string($conn, $_POST['group_name']);
    $subject_id  = intval($_POST['subject_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location    = mysqli_real_escape_string($conn, $_POST['location']);
    // Whitelist study_type to prevent SQL injection
    $allowed_types = ['online', 'offline'];
    $study_type  = in_array($_POST['study_type'] ?? '', $allowed_types, true) ? $_POST['study_type'] : 'offline';
    $max_members = intval($_POST['max_members']);
    $user_id     = $_SESSION['user_id'];

    if ($max_members < 2) {
        $error = "❌ Maximum members must be at least 2!";
    } else {
        $sql = "INSERT INTO study_groups (group_name, subject_id, description, created_by, location, study_type, max_members) 
                VALUES ('$group_name', $subject_id, '$description', $user_id, '$location', '$study_type', $max_members)";

        if (mysqli_query($conn, $sql)) {
            $group_id = mysqli_insert_id($conn);
            // Auto-add creator as group creator
            $add = "INSERT INTO group_members (group_id, user_id, role) VALUES ($group_id, $user_id, 'creator')";
            mysqli_query($conn, $add);
            $success = "✅ Group created successfully! Group ID: #$group_id";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
}

// Fetch subjects
$subjects = mysqli_query($conn, "SELECT * FROM subjects ORDER BY subject_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Study Group</title>
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
        <div class="form-box" style="max-width: 600px;">
            <h2>➕ Create New Study Group</h2>
            <p class="subtitle">Bring students together for better learning</p>
            
            <form method="POST">
                <input type="text" name="group_name" placeholder="📝 Group Name (e.g., DSA Warriors)" required>
                
                <select name="subject_id" required>
                    <option value="">📘 Select Subject</option>
                    <?php while($s = mysqli_fetch_assoc($subjects)) { ?>
                        <option value="<?= $s['id'] ?>">
                            <?= htmlspecialchars($s['subject_name']) ?> (Sem <?= $s['semester'] ?>)
                        </option>
                    <?php } ?>
                </select>
                
                <textarea name="description" placeholder="📄 Describe your group's goals..." rows="4" required></textarea>
                <input type="text" name="location" placeholder="📍 Study Location (e.g., Library, Room 205)" required>
                
                <select name="study_type" required>
                    <option value="offline">🏫 Offline (In-person)</option>
                    <option value="online">💻 Online (Zoom/Meet)</option>
                </select>
                
                <input type="number" name="max_members" placeholder="👥 Maximum Members" min="2" max="50" value="10" required>

                <button type="submit">🚀 Create Group</button>
            </form>

            <?php if($success) echo "<p class='success-msg'>$success</p>"; ?>
            <?php if($error) echo "<p class='error-msg'>$error</p>"; ?>

            <p class="form-footer"><a href="dashboard.php">← Back to Dashboard</a></p>
        </div>
    </div>
</body>
</html>
