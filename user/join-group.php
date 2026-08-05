<?php
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$group_id = intval($_GET['id'] ?? 0);

if (!$group_id) {
    header("Location: find-groups.php");
    exit();
}

// Get group info
$group_q = mysqli_query($conn, "SELECT * FROM study_groups WHERE id = $group_id");
$group = mysqli_fetch_assoc($group_q);

if (!$group) {
    echo "<script>alert('Group not found!'); window.location='find-groups.php';</script>";
    exit();
}

// Check if already a member
$check = mysqli_query($conn, "SELECT * FROM group_members WHERE group_id = $group_id AND user_id = $user_id");
if (mysqli_num_rows($check) > 0) {
    echo "<script>alert('You are already a member!'); window.location='group-details.php?id=$group_id';</script>";
    exit();
}

// Check if full
$count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM group_members WHERE group_id = $group_id"));
if ($count >= $group['max_members']) {
    echo "<script>alert('Group is full!'); window.location='find-groups.php';</script>";
    exit();
}

// Add as member
$sql = "INSERT INTO group_members (group_id, user_id, role) VALUES ($group_id, $user_id, 'member')";
if (mysqli_query($conn, $sql)) {
    // Update group status if full
    $new_count = $count + 1;
    if ($new_count >= $group['max_members']) {
        mysqli_query($conn, "UPDATE study_groups SET status = 'full' WHERE id = $group_id");
    }
    echo "<script>alert('✅ Successfully joined the group!'); window.location='group-details.php?id=$group_id';</script>";
} else {
    echo "<script>alert('❌ Error joining group!'); window.location='find-groups.php';</script>";
}
?>
