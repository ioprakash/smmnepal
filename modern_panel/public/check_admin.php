<?php
require_once '../core/Database.php';

$db = Database::getInstance()->getConnection();

$stmt = $db->prepare("SELECT * FROM clients WHERE username = 'admin'");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "User 'admin' exists in clients table.<br>";
    echo "Password Hash: " . $user['password'] . "<br>";
} else {
    echo "User 'admin' does NOT exist in clients table.<br>";
}

$stmt = $db->prepare("SELECT * FROM admins WHERE username = 'admin'");
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin) {
    echo "User 'admin' exists in admins table.<br>";
    echo "Password (Plain): " . $admin['password'] . "<br>";
} else {
    echo "User 'admin' does NOT exist in admins table.<br>";
}
