<?php

// XAMPP Setup Script
// This script creates the database, imports the schema, and adds Nepali payment methods.

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'smmnepal';

try {
    // 1. Connect to MySQL Server (no DB selected yet)
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL server.<br>";

    // 2. Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '$dbname' created or already exists.<br>";

    // 3. Select Database
    $pdo->exec("USE `$dbname`");

    // 4. Import Schema from Database.sql
    $sqlFile = realpath(__DIR__ . '/../../Database.sql');
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        
        // Split SQL file into individual queries
        // Note: This is a basic splitter and might not handle complex cases, but works for standard dumps
        $queries = explode(';', $sql);

        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                try {
                    $pdo->exec($query);
                } catch (PDOException $e) {
                    // Ignore errors like "table already exists" for re-runs
                    // echo "Query failed: " . $e->getMessage() . "<br>";
                }
            }
        }
        echo "Database schema imported.<br>";
    } else {
        echo "Error: Database.sql not found at $sqlFile<br>";
    }

    // 5. Add Nepali Payment Methods
    // Khalti
    $stmt = $pdo->prepare("SELECT * FROM paymentmethods WHERE methodName = 'Khalti'");
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        $sql = "INSERT INTO paymentmethods (methodName, methodVisibleName, methodMin, methodMax, methodFee, methodStatus, methodPosition) VALUES ('Khalti', 'Khalti Digital Wallet', 10, 10000, 0, '1', 1)";
        $pdo->exec($sql);
        echo "Added Khalti payment method.<br>";
    }

    // eSewa
    $stmt = $pdo->prepare("SELECT * FROM paymentmethods WHERE methodName = 'eSewa'");
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        $sql = "INSERT INTO paymentmethods (methodName, methodVisibleName, methodMin, methodMax, methodFee, methodStatus, methodPosition) VALUES ('eSewa', 'eSewa Mobile Wallet', 10, 10000, 0, '1', 2)";
        $pdo->exec($sql);
        echo "Added eSewa payment method.<br>";
    }

    echo "<h1>Setup Complete!</h1>";
    echo "<p>You can now access the panel.</p>";
    echo "<a href='index.php'>Go to Home</a>";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
