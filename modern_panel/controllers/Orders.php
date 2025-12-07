<?php

require_once '../core/Database.php';
require_once '../core/Session.php';

class Orders {
    private $db;

    public function __construct() {
        Session::init();
        if (!Session::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    public function newOrder() {
        $user_id = Session::get('user_id');

        // Fetch Categories
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE category_type = '2' AND category_deleted = '0' ORDER BY category_line ASC");
        $stmt->execute();
        $categories = $stmt->fetchAll();

        // Fetch Services (Grouped by Category for easier JS handling if needed, or just fetch all)
        // For simplicity, we'll fetch all active services
        $stmt = $this->db->prepare("SELECT * FROM services WHERE service_deleted = '0' ORDER BY service_line ASC");
        $stmt->execute();
        $services = $stmt->fetchAll();

        // Handle Form Submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Basic validation and insertion logic (Simplified)
            $service_id = $_POST['service_id'];
            $link = $_POST['link'];
            $quantity = $_POST['quantity'];
            
            // Calculate price (Simplified: fetch service price * quantity / 1000)
            $stmt = $this->db->prepare("SELECT * FROM services WHERE service_id = :id");
            $stmt->execute(['id' => $service_id]);
            $service = $stmt->fetch();
            
            if ($service) {
                $price = ($service['service_price'] / 1000) * $quantity;
                
                // Check Balance
                $stmt = $this->db->prepare("SELECT balance FROM clients WHERE client_id = :id");
                $stmt->execute(['id' => $user_id]);
                $balance = $stmt->fetch()['balance'];
                
                if ($balance >= $price) {
                    // Create Order
                    $stmt = $this->db->prepare("INSERT INTO orders (client_id, service_id, order_quantity, order_charge, order_url, order_create, order_status) VALUES (:client_id, :service_id, :quantity, :price, :url, NOW(), 'pending')");
                    if ($stmt->execute([
                        'client_id' => $user_id,
                        'service_id' => $service_id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'url' => $link
                    ])) {
                        // Deduct Balance
                        $stmt = $this->db->prepare("UPDATE clients SET balance = balance - :price, spent = spent + :price WHERE client_id = :id");
                        $stmt->execute(['price' => $price, 'id' => $user_id]);
                        
                        $success = "Order placed successfully!";
                    } else {
                        $error = "Failed to place order.";
                    }
                } else {
                    $error = "Insufficient balance.";
                }
            } else {
                $error = "Invalid service.";
            }
        }

        require_once '../views/neworder.php';
    }

    public function listOrders() {
        $user_id = Session::get('user_id');

        $stmt = $this->db->prepare("SELECT o.*, s.service_name FROM orders o LEFT JOIN services s ON o.service_id = s.service_id WHERE o.client_id = :id ORDER BY o.order_id DESC");
        $stmt->execute(['id' => $user_id]);
        $orders = $stmt->fetchAll();

        require_once '../views/orders.php';
    }
}
