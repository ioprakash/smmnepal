<?php
require_once '../core/Database.php';

$db = Database::getInstance()->getConnection();

$username = 'admin';
$password = '1234567890';
$email = 'admin@admin.com';
$name = 'Admin';

// Hash password with MD5 as per current auth logic
$passwordHash = md5($password);

// Check if exists again to be safe
$stmt = $db->prepare("SELECT * FROM clients WHERE username = :username");
$stmt->execute(['username' => $username]);

if ($stmt->rowCount() == 0) {
    $sql = "INSERT INTO clients (name, username, email, password, admin_type, apikey, ref_code, register_date) VALUES (:name, :username, :email, :password, '1', 'admin_api_key', 'admin_ref', NOW())";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'username' => $username,
        'email' => $email,
        'password' => $passwordHash
    ]);
    echo "Admin user created in clients table.<br>";
    echo "Username: $username<br>";
    echo "Password: $password<br>";
} else {
    echo "Admin user already exists in clients table.<br>";
}
