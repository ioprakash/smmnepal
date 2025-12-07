<?php

require_once '../core/Database.php';

$db = Database::getInstance()->getConnection();

// Add Khalti
$stmt = $db->prepare("SELECT * FROM paymentmethods WHERE methodName = 'Khalti'");
$stmt->execute();
if ($stmt->rowCount() == 0) {
    $sql = "INSERT INTO paymentmethods (methodName, methodVisibleName, methodMin, methodMax, methodFee, methodStatus, methodPosition) VALUES ('Khalti', 'Khalti Digital Wallet', 10, 10000, 0, '1', 1)";
    $db->exec($sql);
    echo "Added Khalti.<br>";
}

// Add eSewa
$stmt = $db->prepare("SELECT * FROM paymentmethods WHERE methodName = 'eSewa'");
$stmt->execute();
if ($stmt->rowCount() == 0) {
    $sql = "INSERT INTO paymentmethods (methodName, methodVisibleName, methodMin, methodMax, methodFee, methodStatus, methodPosition) VALUES ('eSewa', 'eSewa Mobile Wallet', 10, 10000, 0, '1', 2)";
    $db->exec($sql);
    echo "Added eSewa.<br>";
}

echo "Payment setup complete.";
