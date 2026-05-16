<?php
require_once 'includes/db.php';

// Set your desired admin credentials here
$new_email = 'admin.unitrade@gmail.com';
$new_password = 'password123'; // Change this to your preferred password

// Generate the secure hash
$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("UPDATE users SET email = ?, password = ? WHERE role = 'admin' LIMIT 1");
    $stmt->execute([$new_email, $hashed_password]);
    
    echo "<h2>Admin Credentials Updated!</h2>";
    echo "<p><b>Email:</b> " . htmlspecialchars($new_email) . "</p>";
    echo "<p><b>Password:</b> " . htmlspecialchars($new_password) . "</p>";
    echo "<br><a href='login.php'>Go to Login Page</a>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
