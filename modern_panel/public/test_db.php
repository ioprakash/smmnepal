<?php
$host = '127.0.0.1';
$db   = 'smmnepal';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "<h1>TEST PASSED</h1>";
    echo "<p>Successfully connected to database <b>$db</b> on <b>$host</b>.</p>";
} catch (\PDOException $e) {
    echo "<h1>TEST FAILED</h1>";
    echo "<p>Connection failed: " . $e->getMessage() . "</p>";
}
