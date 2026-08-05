<?php
include 'db.php';

echo "<h2>🔐 Password Reset Tool</h2>";
echo "<hr>";

// Check database connection
if (!$conn) {
    die("❌ Database connection failed! Make sure MySQL is running in XAMPP.");
}

echo "✅ Database connected!<br><br>";

// Generate fresh hash for "admin123"
$new_hash = password_hash('admin123', PASSWORD_DEFAULT);
echo "Generated new hash: " . $new_hash . "<br><br>";

// Update admin password
$sql = "UPDATE users SET password = '$new_hash' WHERE email = 'admin@gsf.com'";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "✅ <b>Admin password updated successfully!</b><br><br>";
} else {
    echo "❌ Error updating admin: " . mysqli_error($conn) . "<br><br>";
}

// Update all student passwords
$sql2 = "UPDATE users SET password = '$new_hash' WHERE email IN ('prasant@gmail.com', 'ram@gmail.com', 'sita@gmail.com', 'hari@gmail.com', 'gita@gmail.com')";
$result2 = mysqli_query($conn, $sql2);

if ($result2) {
    echo "✅ <b>All student passwords updated successfully!</b><br><br>";
}

// Verify
echo "<h3>🔍 Verification:</h3>";
$check = mysqli_query($conn, "SELECT email, role FROM users");
echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
echo "<tr><th>Email</th><th>Role</th><th>Password</th></tr>";
while ($row = mysqli_fetch_assoc($check)) {
    $verify = password_verify('admin123', $new_hash) ? '✅ Works!' : '❌ Failed';
    echo "<tr>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['role'] . "</td>";
    echo "<td>$verify (use: admin123)</td>";
    echo "</tr>";
}
echo "</table>";

echo "<br><br>";
echo "<h3>🎯 Now try logging in:</h3>";
echo "<a href='login.php' style='background:#27ae60;color:white;padding:12px 24px;text-decoration:none;border-radius:5px;'>Go to Login Page</a>";
echo "<br><br>";
echo "<b>Admin Login:</b> admin@gsf.com / admin123<br>";
echo "<b>Student Login:</b> prasant@gmail.com / admin123";
?>