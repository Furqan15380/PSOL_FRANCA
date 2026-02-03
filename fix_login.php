<?php
require_once 'includes/db_connect.php';

echo "<h1>Admin Login Fixer</h1>";

try {
    // 1. Check if table exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `email` varchar(255) NOT NULL UNIQUE,
      `password` varchar(255) NOT NULL,
      `reset_token` varchar(255) DEFAULT NULL,
      `reset_token_expires` datetime DEFAULT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    echo "<p style='color: green;'>✅ Users table checked/created.</p>";

    // 2. Remove existing admin to ensure clean state
    $stmt = $pdo->prepare("DELETE FROM users WHERE email = ?");
    $stmt->execute(['admin@psolfranca.com']);
    echo "<p style='color: green;'>✅ Cleared any existing admin user.</p>";

    // 3. Create new hash and insert user
    $password = 'admin123';
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute(['Admin User', 'admin@psolfranca.com', $hash]);

    echo "<div style='background: #f0fdf4; padding: 20px; border: 1px solid #4ade80; border-radius: 10px; margin-top: 20px;'>
        <h2 style='color: #16a34a; margin-top: 0;'>✅ Admin User Reset Successfully!</h2>
        <p>You can now login with:</p>
        <ul>
            <li><strong>Email:</strong> admin@psolfranca.com</li>
            <li><strong>Password:</strong> admin123</li>
        </ul>
        <br>
        <a href='login.php' style='background: #16a34a; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login Page</a>
    </div>";

} catch (PDOException $e) {
    echo "<div style='background: #fef2f2; padding: 20px; border: 1px solid #f87171; border-radius: 10px; margin-top: 20px;'>
        <h2 style='color: #dc2626; margin-top: 0;'>❌ Error Occurred</h2>
        <p>" . $e->getMessage() . "</p>
    </div>";
}
?>