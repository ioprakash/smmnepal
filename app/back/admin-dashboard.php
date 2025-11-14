<?php
if (!defined('BASEPATH')) {
  die('Direct access to the script is not allowed');
}
if ($admin["access"]["admin_access"] && $_SESSION["msmbilisim_adminlogin"] && $admin["client_type"] == 2) {
    // Gather dashboard stats
    $stats = [
        'users' => $conn->query("SELECT COUNT(*) FROM clients")->fetchColumn(),
        'orders' => $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
        'revenue' => $conn->query("SELECT SUM(amount) FROM payments")->fetchColumn(),
        'services' => $conn->query("SELECT COUNT(*) FROM services WHERE service_deleted=0")->fetchColumn(),
    ];

    // Recent activity (example: last 5 orders)
    $recent_activity = [];
    $stmt = $conn->prepare("SELECT username, order_id, created_at FROM orders JOIN clients ON orders.client_id = clients.client_id ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $recent_activity[] = $row['username'] . ' placed order #' . $row['order_id'] . ' at ' . $row['created_at'];
    }

    // Render the dashboard template
    echo $twig->render('admin-dashboard.twig', [
        'stats' => $stats,
        'recent_activity' => $recent_activity
    ]);
} else {
    header('Location: /admin/login');
    exit;
}
